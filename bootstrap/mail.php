<?php

$container['mailer'] = function($container) {
    if ($container->settings['mail']['driver'] == 'smtp') {
        $transport = (new Swift_SmtpTransport($container->settings['mail']['host'], $container->settings['mail']['port']))
            ->setEncryption($container->settings['mail']['encryption'])
            ->setAuthMode($container->settings['mail']['auth'])
            ->setUsername($container->settings['mail']['username'])
            ->setPassword($container->settings['mail']['password']);
    } else {
        $transport = new Swift_SendmailTransport("/usr/sbin/sendmail -bs");
    }
    return new StudioAtual\Mail\Mailer($container->view, new Swift_Mailer($transport));
};
