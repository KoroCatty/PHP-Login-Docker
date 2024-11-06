<?php $currentPage = 'Password Recovery'; ?>
<?php include 'layout/header.php'; ?>



<div class="container">
    <div class="content">
        <h2 class="heading">Password Recovery</h2>

        <?php
        if (isset($_POST['password_recovery'])) {
            $user_name = escape($_POST['user_name']);
            $user_email = escape($_POST['user_email']);


            $query = "SELECT * FROM users WHERE user_name = '$user_name' AND user_email = '$user_email' AND is_active = 1";
            $query_con = mysqli_query($connection, $query);
            if(!$query_con) {
                die("Query Failed" . mysqli_error($connection));
            }



   if (mysqli_num_rows($query_con) == 1) {

            // Check if user exists
            if (!isset($_COOKIE['_unp_'])) {
                // ユーザーが入力した値を取得
                $user_name = $_POST['user_name'];
                $user_email = $_POST['user_email'];

                // タイムゾーンを設定
                date_default_timezone_set("asia/tokyo");

                // メールを送信
                $mail->addAddress($_POST['user_email']);
                $token = getToken(32);

                $token = base64_encode($token); // URLに醸されるので

                $email = base64_encode(urlencode($_POST['user_email']));

                // Expiring in 20 minutes & Encoding it (URLに醸されるので)
                $expire_date = date("Y-m-d H:i:s", time() + 60 * 20);
                $expire_date = base64_encode(urlencode($expire_date));

                // DB の validation_key を更新
                $query = "UPDATE users SET validation_key = '$token' WHERE user_name = '$user_name' AND user_email = '$user_email' AND is_active = 1";

                $query_con = mysqli_query($connection, $query);
                if (!$query_con) {
                    die("Query Failed" . mysqli_error($connection));
                } else {
                    $mail->Subject = "Password reset request";
                    $mail->Body = "
                        <h2>Follow the link to create a new password</h2>
                        <a href='http://localhost:8080/new_password.php?eid={$email}&token={$token}&expire={$expire_date}'>Click here to verify</a>
                        <p>This link is valid for 5 minutes</p>
                        ";

                    // メールが送られたら5分間のクッキーをセット & Random token
                    if ($mail->send()) {
                        setcookie('_unp_', getToken(16), time() + 60 * 5, '', '', '', true);
                        echo "<div class='notification'>Check your email for password reset</div>";
                    }
                }
            } else {
                echo "<div class='notification'>You must be wait at lest 5 minutes for another request for Email Plz 😅</div>";
            }



        } else {
            echo "Sorry! User not found";
        }
    }


        // fafasfsad3D
        ?>


        <form action="" method="POST">
            <div class="input-box">
                <input type="text" class="input-control" placeholder="Username" name="user_name" required>
            </div>
            <div class="input-box">
                <input type="email" class="input-control" placeholder="Email address" name="user_email" required>
            </div>
            <div class="input-box">
                <input type="submit" class="input-submit" value="RECOVER PASSWORD" name="password_recovery" required>
            </div>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>