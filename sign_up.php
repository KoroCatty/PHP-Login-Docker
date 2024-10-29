<!-- For title -->
<?php $currentPage = 'sign-up'; ?>

<!-- 環境変数ライブラリ -->
<?php // require 'vendor/autoload.php'; ?>

<?php include 'layout/header.php'; ?>

<div class="container">
    <div class="content">
        <h2 class="heading">Sign Up</h2>

        <!-- Google Recaptcha  Dotenv used-->
        <?php
        use Dotenv\Dotenv;
        $dotenv = Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        $public_key = $_ENV['recapthca'];
        $private_key = $_ENV['recapthcaSecret'];
        $url = $_ENV['recaptchaURL'];
        ?>

        <?php
        // 送信ボタンがクリックされた場合
        if (isset($_POST['sign-up'])) {

            // Google recaptcha 
            $response_key = $_POST['g-recaptcha-response'];

            // Google reCAPTCHA API にリクエストを送信するための URL
            // https://www.google.com/recaptcha/api/siteverify?secret=$response_key&remoteip=currentScriptIpAddress

            // file_get_contents() 関数を使って、Google reCAPTCHA API にリクエストを送信
            $response = file_get_contents($url . "?secret=" . $private_key . "&response=" . $response_key . "&remoteip=" . $_SERVER['REMOTE_ADDR']);

            // JSON形式のデータをデコード
            $response = json_decode($response);

            if ($response->success == false) {
                $errCaptcha = "Wrong reCAPTCHA";
            }

            // POST
            // functions.php で定義した escape 関数を使い、xss 対策を行う
            $first_name = escape($_POST['first_name']);
            $last_name = escape($_POST['last_name']);
            $user_name = escape($_POST['user_name']);
            $user_email = escape($_POST['user_email']);
            $user_password = escape($_POST['user_password']);
            $user_confirm_password = escape($_POST['user_confirm_password']);

            //! Validation with Regex
            // First name
            $pattern_fn = "/^[a-zA-Z ]{3,}$/";
            if (!preg_match($pattern_fn, $first_name)) {
                $errFn = "Must be at least 3 characters";
            }
            // last name 
            $pattern_ln = "/^[a-zA-Z ]{3,}$/";
            if (!preg_match($pattern_ln, $last_name)) {
                $errLn = "Must be at least 3 characters";
            }
            // User name
            // More than 3, letter, number & underscore only
            $pattern_un = "/^[a-zA-Z0-9_]{3,}$/";
            if (!preg_match($pattern_un, $user_name)) {
                $errUn = "Must be at least 3, letter, number & underscore only";
            }
            // Email
            //filter_var($user_email, FILTER_VALIDATE_EMAIL);
            // example@gmail.com
            $pattern_ue = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i";
            if (!preg_match($pattern_ue, $user_email)) {
                $errUe = "Invalid format of email";
            }
            // Password & matching password
            // At least 4 characters, 1 upper case, 1 lower case letter and 1 number exist
            // ^.*(?=.{4,56})：4～56文字の長さであることを確認
            // (?=.*[a-z])：少なくとも1つの小文字が含まれていること
            // (?=.*[A-Z])：少なくとも1つの大文字が含まれていること
            // (?=.*[0-9])：少なくとも1つの数字が含まれていること
            if ($user_password == $user_confirm_password) {
                $pattern_up = "/^.*(?=.{4,56})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/";

                if (!preg_match($pattern_up, $user_password)) {
                    // パスワードが条件を満たしていない場合のエラーメッセージ
                    $errPass = "Must be at least 4 characters long, with 1 uppercase, 1 lowercase letter, and 1 number";
                } else {
                    echo 'Passwords match 😊';
                }
            } else {
                // パスワードが一致しない場合のエラーメッセージ
                $errPass = "Passwords do not match";
                echo $errPass; // エラーメッセージを出力
            }

            // 一つでも空やエラーがある場合
            if (!isset($errFn) && !isset($errLn) && !isset($errUn) && !isset($errUe) && !isset($errPass) && !isset($errCaptcha)) {
                // パスワードをハッシュ化
                $hash = password_hash($user_password, PASSWORD_BCRYPT, ['cost' => 12]);

                // 日付を取得
                $timeZone = date_default_timezone_set('Asia/Tokyo');
                $registration_date = date('Y-m-d H:i:s');

                //! Email Confirmation (Sending Email)
                $mail->addAddress($_POST['user_email']); // ユーザーが入力したもの
                $mail->Subject = "Verify your Email 😊";
                $mail->Body = "
                <h1>Thank you for signing up</h1>
                <a href=''>Click here to verify</a>
                <p>This link is valid for 20 mins only</p>
                ";

                // メールが送信された場合
                if ($mail->send()) {
                    // ユーザーを登録
                    $query = "INSERT INTO users (first_name, last_name, user_name, user_email, user_password, validation_key, registration_date, is_active) VALUES ('$first_name', '$last_name', '$user_name', '$user_email', '$hash', '$user_confirm_password', '$registration_date', 0)";

                    // クエリを実行
                    $query_conn = mysqli_query($connection, $query);
                    if (!$query_conn) {
                        die("Query Failed" . mysqli_error($connection));
                    } else {
                        echo "<div class='notification'>Sign up successful. Check your email for activation link</div>";
                    }
                } else {
                    echo "<div class='notification'>Email not sent</div>";
                }
            }
        }
        ?>
        <form action="sign_up.php" method="POST">
            <!-- First Name -->
            <div class="input-box">
                <input type="text" class="input-control" placeholder="First name" name="first_name" autocomplete="off">
                <?php echo isset($errFn)
                    ? "<span class='error'>$errFn</span>"
                    : ''; ?>
            </div>

            <!-- Last Name -->
            <div class="input-box">
                <input type="text" class="input-control" placeholder="Last name" name="last_name" autocomplete="off">
                <?php echo isset($errLn)
                    ? "<span class='error'>$errLn</span>"
                    : ''; ?>
            </div>

            <!-- User Name -->
            <div class="input-box">
                <input type="text" class="input-control" placeholder="Username" name="user_name" autocomplete="off">
                <?php echo isset($errUn)
                    ? "<span class='error'>$errUn</span>"
                    : ''; ?>
            </div>

            <!-- Email -->
            <div class="input-box">
                <input type="email" class="input-control" placeholder="Email address" name="user_email" autocomplete="off">
                <?php echo isset($errUe)
                    ? "<span class='error'>$errUe</span>"
                    : ''; ?>
            </div>

            <!-- Password -->
            <div class="input-box">
                <input type="password" class="input-control" placeholder="Enter password" name="user_password" autocomplete="off">
                <?php echo isset($errPass)
                    ? "<span class='error'>$errPass</span>"
                    : ''; ?>
            </div>

            <!-- Confirm Password -->
            <div class="input-box">
                <input type="password" class="input-control" placeholder="Confirm password" name="user_confirm_password" autocomplete="off">
                <?php echo isset($errPass)
                    ? "<span class='error'>$errPass</span>"
                    : ''; ?>
            </div>

            <!-- reCaptcha -->
            <div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>
            <?php echo isset($errCaptcha)
                ? "<span class='error'>$errCaptcha</span>"
                : ''; ?>

            <div class="input-box">
                <input type="submit" class="input-submit" value="SIGN UP" name="sign-up">
            </div>

            <!--  -->
            <div class="sign-up-cta"><span>Already have an account?</span> <a href="login.php">Login here</a></div>
        </form>

    </div>
</div>

<?php include 'layout/footer.php'; ?>