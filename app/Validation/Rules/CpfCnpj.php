<?php

namespace StudioAtual\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class CpfCnpj extends AbstractRule
{
    public function validate($input)
    {
        $c = preg_replace('/\D/', '', $input);

        if (strlen($c) == 14) {
            $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

            for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);

            if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                return false;
            }

            for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);

            if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
                return false;
            }

            return true;
        }

        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }
}