<?php session_start(); ?>
<?php $currentPage = 'login'; ?>
<?php include 'layout/header.php'; ?>


<!-- Google Recaptcha  Dotenv used-->
<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$public_key = $_ENV['recapthca'];
$private_key = $_ENV['recapthcaSecret'];
$url = $_ENV['recaptchaURL'];
?>

<div class="container">
    <div class="content">
        <h2 class="heading">Login</h2>
        <!-- <div class='notification'>Logged In Successfull</div> -->

        <?php
        if (isset($_POST['login'])) {

            //! Google recaptcha 
            $response_key = $_POST['g-recaptcha-response'];
            // file_get_contents() 関数を使って、Google reCAPTCHA API にリクエストを送信
            $response = file_get_contents($url . "?secret=" . $private_key . "&response=" . $response_key . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
            // JSON形式のデータをデコード
            $response = json_decode($response);
            if ($response->success == false) {
                $errCaptcha = "Wrong reCAPTCHA";
            }

            //! ユーザーが入力した値を取得
            $user_name = escape($_POST['user_name']);
            $user_email = escape($_POST['user_email']);
            $user_password = escape($_POST['user_password']);

            echo "User name : " . $user_name . "<br>";

            // 入力されたやつをデータベースと照合 & email verification が必要
            $query = "SELECT  * FROM users WHERE user_name = '$user_name' AND user_email = '$user_email' AND is_active = 1";
            $query_con = mysqli_query($connection, $query);
            if (!$query_con) {
                die("Query Failed" . mysqli_error($connection));
            }

            $result = mysqli_fetch_assoc($query_con); // 連想配列で取得

            // verify password (typed one and the one in the DB)
            if (password_verify($user_password, $result['user_password'])) {
                if (!isset($errCaptcha)) {
                    echo "<div class='notification'>Logged In Successful😊</div>";
                    $_SESSION['login'] = 'success';
                    // Refresh:2 sets a delay of 2 seconds before the redirect occurs
                    header("Refresh:2;url=index.php");
                    exit();
                }
            } else {
                echo "<div class='notification'>Password or username or email or incorrect</div>";
            }
        }
        ?>

        <form action="login.php" method="POST">
            <div class="input-box">
                <input type="text" class="input-control" placeholder="Username" name="user_name" required>
            </div>
            <div class="input-box">
                <input type="email" class="input-control" placeholder="Email address" name="user_email" required>
            </div>
            <div class="input-box">
                <input type="password" class="input-control" placeholder="Enter password" name="user_password" required>
            </div>
            <div class="input-box rm-box">
                <div>
                    <input type="checkbox" id="remember-me" class="remember-me" name="remember-me">
                    <label for="remember-me">Remember me</label>
                </div>
                <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
            </div>

            <!-- reCaptcha -->
            <div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>
            <?php echo isset($errCaptcha)
                ? "<span class='error'>$errCaptcha</span>"
                : ''; ?>

            <div class="input-box">
                <input type="submit" class="input-submit" value="LOGIN" name="login">
            </div>
            <div class="login-cta"><span>Don't have an account?</span> <a href="sign_up.php">Sign up here</a></div>
        </form>

    </div>
</div>

<?php include 'layout/footer.php'; ?>


<!-- fafasfsad3D
dr  -->