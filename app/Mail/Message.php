<?php

namespace StudioAtual\Mail;

class Message extends \Swift_Message
{
    public function subject($data)
    {
        $this->setSubject($data);
    }

    public function body($data)
    {
        $this->setBody($data);
    }

    public function type($data)
    {
        $this->setContentType($data);
    }

    public function from($data)
    {
        $this->setFrom($data);
    }

    public function to($data)
    {
        $this->setTo($data);
    }

    public function copy($data)
    {
        $this->setCc($data);
    }

    public function blindCopy($data)
    {
        $this->setBcc($data);
    }

    public function replyTo($data)
    {
        $this->setReplyTo($data);
    }

    public function file($file, $filename=null)
    {
        if (!$filename) {
            $this->attach(\Swift_Attachment::fromPath($file));
        } else {
            $this->attach(\Swift_Attachment::fromPath($file)->setFilename($filename));
        }
    }
}
