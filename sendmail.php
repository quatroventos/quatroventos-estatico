<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
//use PHPMailer\PHPMailer\Exception;
//
////Load Composer's autoloader
//require 'vendor/autoload.php';

$name = $_POST['name'];
$email = $_POST['email'];
$company = $_POST['company'];
$wpp = $_POST['wpp'];
$service = $_POST['service'];
$message = $_POST['message'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.uni5.net';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'no-reply@quatroventos.com.br';                     //SMTP username
    $mail->Password   = 'd5cA#516F902fa';                               //SMTP password
//    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 25;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('no-reply@quatroventos.com.br', $name);
    $mail->addReplyTo($email, $name);
    $mail->addAddress('contato@quatroventos.com.br', 'Quatro Ventos');     //Add a recipient


    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Contato através do site';
    $mail->Body    = 'Nome: '.$name.'<br>';
    $mail->Body    .= 'Empresa: '.$company.'<br>';
    $mail->Body    .= 'E-mail: '.$email.'<br>';
    $mail->Body    .= 'Whatsapp: '.$wpp.'<br>';
    $mail->Body    .= 'Serviço: '.$service.'<br>';
    $mail->Body    .= 'Mensagem: '.$message.'<br>';

    $mail->AltBody    = 'Nome: '.$name;
    $mail->AltBody    .= 'Empresa: '.$company;
    $mail->AltBody    .= 'E-mail: '.$email;
    $mail->AltBody    .= 'Whatsapp: '.$wpp;
    $mail->AltBody    .= 'Serviço: '.$service;
    $mail->AltBody    .= 'Mensagem: '.$message;

    $mail->send();
    header("Location: obrigado.php");
    exit();
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}