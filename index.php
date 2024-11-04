<?php 
    ob_start();
    session_start();
?>

<!-- 環境変数ライブラリ -->
<?php require 'vendor/autoload.php'; ?>

<?php $currentPage = 'index Page😊'; ?>

<?php include 'layout/header.php'; ?>


<?php
include 'connect.php';
?>

<main>
    <div style="font-size: 2rem">
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

        <?php
        // Login しているかどうかで切り分ける
        if (isset($_SESSION['login'])) {
            echo "<div class='notification'>
        You are logged in 😊
        <a href='logout.php'>Logout</a>
        </div>";
        } else {
            echo "<div class='notification'>
        You are not logged in
        <a href='login.php'>Login now</a>
        </div>";
        };
        ?>
    </div>


</main>

<?php include 'layout/footer.php'; ?>