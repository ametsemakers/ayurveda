<?php

namespace AM\CoreBundle\SendMail;

class AMSendMail
{
    private function sendMail($data)
    {
        $contactMail = 'contactmail@mail.fr';
        $contactPassword = 'mailPassword';

        // example (Zoho)
        // http://ourcodeworld.com/articles/read/14/swiftmailer-send-mails-from-php-easily-and-effortlessly
        $transport = \Swift_SmtpTransport::newInstance('smtp.zoho.com', 465, 'ssl')
            ->setUsername($contactMail)
            ->setPassword($contactPassword)
        ;

        $mailer = \Swift_mailer::newInstance($transport);

        $message = \Swift_Message::newInstance("Formulaire de contact ".$data["subject"])
            ->setFrom(array($contactMail => "Message de ".$data["name"]))
            ->setTo(array($contactMail => $contactMail))
            ->setBody($data["message"]."<br>ContactMail :".$data["email"])
        ;

        $mailer->send($message);

        return true;
    }
}

