<?php

namespace Core\Mailer;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    public PHPMailer $mailer;

    private static ?self $_instance = null;

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Mailer();
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = 'sandbox.smtp.mailtrap.io';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Port = 2525;
        $this->mailer->Username = 'c41bceba08090d';
        $this->mailer->Password = 'b9f7a4343d030b';
    }
}
