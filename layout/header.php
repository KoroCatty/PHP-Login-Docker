<?php ob_start();?>
<?php require_once('includes/db.php'); ?>
<?php require_once('includes/functions.php'); ?>

<?php 
// if(isset($_SESSION['login'])) {
//   header("Location: index.php");
//   }
   ?>

<?php
// login してたら index.php にリダイレクト
// if (isset($_SESSION['login'])) {
//   header("Location: index.php");
//   exit();
// };
?>



<?php
// all important variables in this file 
require_once("vendor/autoload.php");
// Dotenv used
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../'); // .env は一つ上の階層
$dotenv->load();
$emailAddress = $_ENV['emailAddress'];
$emailPassword = $_ENV['emailPassword'];

// import from PHPMailer namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($currentPage, ENT_QUOTES, 'UTF-8'); ?></title>
  <link rel="stylesheet" href="./build/css/main.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

  <!-- Original Minified CSS -->
  <link rel="stylesheet" href="./build/css/main.css">
</head>

<body>
  <?php
  // Create an instance; passing `true` enables exceptions
  $mail = new PHPMailer(true);

  $mail->isSMTP();
  $mail->SMTPAuth = true;
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  $mail->SMTPSecure = 'ssl';

  // エンコーディングをUTF-8に設定 (文字化け対策)
  $mail->CharSet = 'UTF-8';
  $mail->Encoding = 'base64';  // 日本語のエンコーディングは通常base64を使用

  $mail->Username = $emailAddress;
  $mail->Password = $emailAddress;
  $mail->Password = $emailPassword;

  $mail->SMTPAuth   = true; //Enable SMTP authentication
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; //Enable implicit TLS encryption

  $mail->setFrom($emailAddress, "Drumer");
  $mail->addReplyTo("no-reply@gmail.com");

  $mail->isHTML(true);
