<?php
if (!defined('_INCODE')) {
    die('Access denied');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . "/layouts/$layoutName.php")) {
        require_once _WEB_PATH_TEMPLATE . "/layouts/$layoutName.php";
    }
}

function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'kietminh070502@gmail.com';                     //SMTP username
        $mail->Password   = 'pyfogqbjgpfzvxlo';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP

        //Recipients
        $mail->setFrom('kietminh070502@gmail.com', 'Unicode Training');
        $mail->addAddress($to);     //Add a recipient
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML

        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $content;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
