<?php
// all important variables in this file 
require_once("vendor/autoload.php");

// Google Recaptcha  Dotenv used
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$emailAddress = $_ENV['emailAddress'];
$emailPassword = $_ENV['emailPassword'];

// import from PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'ssl';

$mail->Username = $emailAddress;
$mail->Password = $emailAddress;
$mail->Password = $emailPassword;

$mail->SMTPAuth   = true; //Enable SMTP authentication
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption

$mail->setFrom($emailAddress, "Drumer");
$mail->addReplyTo("no-reply@gmail.com");

// recipient 
$mail->addAddress($emailAddress);
$mail->isHTML(true);
$mail->Subject = "Sending from Localhost";
$mail->Body = "<h1>Hi, I am localhost</h1>";

// もし無事メールが送信された場合
if ($mail->send()) {
  echo "Mail sent";
} else {
  echo "Error: " . $mail->ErrorInfo;
}

// if ($mail->send()) {
//   $_SESSION['status'] = "Thanks for contacting us. We will get back to you shortly."; // セッションメッセージを設定
//   header('Location: ' . $_SERVER['HTTP_REFERER']); // ユーザーを元のページにリダイレクト
//   exit(); // リダイレクト後にスクリプトが実行されないように終了
  
// } else {
//   $_SESSION['status'] = "Message could not be sent.送信不可。 😅Mailer Error: {$mail->ErrorInfo}"; // Set a session message
//   header('Location: index.php');
// }