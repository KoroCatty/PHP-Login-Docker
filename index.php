<!-- 環境変数ライブラリ -->
<?php require 'vendor/autoload.php'; ?>

<?php include 'layout/header.php'; ?>


<?php
include 'connect.php';
?>


<main>
<div>
        You are not logged in <a href='login.php'>Login now</a>
    </div> 
    <!-- <div>
        You are logged in <a href='logout.html'>Logout</a>
    </div>         -->


    <!-- 環境変数 -->
    <?php
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // 環境変数にアクセスする
    echo $_ENV['DB_HOST'];
    echo '<br />';
    echo $_ENV['DB_USER'];
    echo '<br />';
    echo $_ENV['DB_PASS'];
    ?>


</main>

<?php include 'layout/footer.php'; ?>