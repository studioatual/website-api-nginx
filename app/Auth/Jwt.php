<?php

namespace StudioAtual\Auth;

class Jwt
{
    private $domain;
    private $key;
    private $fields;
    private $model;
    private $bucket;
    private $user;

    public function __construct($domain, $key, $fields, $model, $bucket)
    {
        $this->domain = $domain;
        $this->key = $key;
        $this->fields = $fields;
        $this->model = $model;
        $this->bucket = $bucket;
    }

    public function generateToken($user)
    {
        $header = $this->makeHeader();
        $payload = $this->makePayload($user);
        $signature = $this->makeSignature($header, $payload);

        return "$header.$payload.$signature";
    }

    private function makeHeader()
    {
        return base64_encode(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT'
        ]));
    }

    private function makePayload($user)
    {
        $payload['iss'] = $this->domain;
        foreach ($this->fields as $field) {
            $payload[$field] = $user->{$field};
        }
        return base64_encode(json_encode($payload));
    }

    private function makeSignature($header, $payload)
    {
        return base64_encode(hash_hmac(
            'sha256',
            $header.$payload,
            $this->key,
            true
        ));
    }

    public function checkToken($token)
    {
        $token = trim(str_replace("Bearer", "", $token));

        if (!$this->checkTokenIsNotEmpty($token)) {
            return false;
        }

        if (!$parts = $this->checkTokenParts($token)) {
            return false;
        }

        if (!$this->checkHeader($parts)) {
            return false;
        }

        if (!$payload = $this->checkPayload($parts)) {
            return false;
        }

        if (!$user = $this->checkUserExists($payload)) {
            return false;
        }

        if (!$this->checkTokenIsValid($token, $user)) {
            return false;
        }

        $this->user = $user;

        return true;
    }

    private function checkTokenIsNotEmpty($token)
    {
        if ($token == "" || $token == null) {
            return false;
        }

        return true;
    }

    private function checkTokenParts($token)
    {
        $parts = explode('.', $token);
        if (count($parts) != 3) {
            return false;
        }

        return $parts;
    }

    private function checkHeader($parts)
    {
        if ($parts[0] !== $this->makeHeader()) {
            return false;
        }

        return true;
    }

    private function checkPayload($parts)
    {
        $payload = json_decode(base64_decode($parts[1]));

        if (!isset($payload->iss)) {
            return false;
        }

        foreach ($this->fields as $field) {
            if (!isset($payload->{$field})) {
                return false;
            }
        }

        return $payload;
    }

    private function checkUserExists($payload)
    {
        $search = [];
        foreach ($this->fields as $field) {
            $search[] = [$field, $payload->{$field}];
        }
        return $this->model::where($search)->first();
    }

    private function checkTokenIsValid($token, $user)
    {
        return $token === $this->generateToken($user);
    }

    public function getUser()
    {
        return $this->user;
    }
}
