<?php
require_once "./PHPMailer/src/Exception.php";
require_once "./PHPMailer/src/PHPMailer.php";
require_once "./PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    //Create an instance; passing `true` enables exceptions
    private $mail = new PHPMailer(true);

    public function __constructor()
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'ssl://smtp.gmail.com';                 //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'webdevprog8701@gmail.com';             //SMTP username
        $mail->Password = 'xdib fmjz sxpe nytc';                  //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('noreply@primemart.com', 'PrimeMart');
        $this->mail = $mail;
    }

    public function send($receiverEmail, $reciverName, $subjet, $body)
    {
        try {
            //Server settings
            $this->mail->addAddress($receiverEmail, @$reciverName);     //Add a recipient

            //Content
            $this->mail->isHTML(true);                                  //Set email format to HTML
            $this->mail->Subject = $subjet;
            $this->mail->Body = $body;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}

?>