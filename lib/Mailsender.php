<?php

namespace lib;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailsender
{

    private PHPMailer $mail;
    private string $domain; // sender domain (for phone numbers)


    public function __construct()
    {
        $config = require(__DIR__ . '/../config.php');
        if (!isset($config['mailName'], $config['mailPassword'])) {
            throw new Exception("mail name and mail password not defined");
        }

        $this->mail = new PHPMailer(true);
        //Server settings
        $this->mail->isSMTP();                                            //Send using SMTP
        $this->mail->Port = 465;
        $this->mail->Host = "smtp.gmail.com";
        $this->mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $this->mail->Username = $config['mailName'];                     //SMTP username
        $this->mail->Password = $config['mailPassword'];                               //SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
//        $this->mail->SMTPDebug = SMTP::DEBUG_SERVER;

    }

    /**
     * @throws Exception
     */
    public function send(string $sendTo, string $header, string $body)
    {

        $this->mail->IsHTML(true);
        $this->mail->AddAddress($sendTo, explode('@', $sendTo)[0]);
        $this->mail->Subject = $header;
        $this->mail->MsgHTML($body);
        if (!$this->mail->Send()) {
            echo "Error while sending Email. \r\n";
//            var_dump($this->mail);
        } else {
            echo "Email sent successfully \r\n";
        }
    }
}