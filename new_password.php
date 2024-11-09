<?php $currentPage = 'New Password Page'; ?>
<?php include 'layout/header.php'; ?>
<?php require_once('includes/db.php'); ?>

<div class="container">
    <div class="content">
        <h2 class="heading">New Password</h2>

        <?php
        // URL から値を取得 (encode された値)
        if (isset($_GET['eid']) && isset($_GET['token']) && isset($_GET['expire'])) {

            // Decoding here
            $user_email = urldecode(base64_decode($_GET['eid']));
            $validation_key = urldecode(base64_decode($_GET['token']));
            $expire_date = urldecode(base64_decode($_GET['expire']));

            // 現在の日時
            date_default_timezone_set("asia/tokyo");
            $current_date = date("Y-m-d H:i:s"); // 2021-08-01 12:00:00

            if ($expire_date <= $current_date) {
                echo "<div class='notification'>This link has expired</div>";
                exit(); // フォームを表示させない
            }

            // フォームを表示するかどうかのフラグ
            $check = true;

            // 送信ボタンが押された時
            if (isset($_POST['submit'])) {
                // DB に入れるものなのでエスケープ処理
                $user_password = escape($_POST['new-password']);
                $user_confirm_password = escape($_POST['confirm-new-password']);

                // パスワードのバリデーション (DBと照合)
                if ($user_password == $user_confirm_password) {
                    $pattern_up = "/^.*(?=.{4,56})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/";

                    // preg_match() でパスワードが条件を満たしているか確認
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

                //! 成功時
                // まずは DB のデータを取得
                $query = "SELECT * FROM users WHERE user_email = '$user_email'AND validation_key = '$validation_key' AND is_active = 1";
                $query_con = mysqli_query($connection, $query);
                if (!$query_con) {
                    die("Query Failed" . mysqli_error($connection));
                }

                //? UPDATE
                if (mysqli_num_rows($query_con) == 1) {
                    $password = password_hash($user_password, PASSWORD_BCRYPT, ['cost' => 12]); // パスワードをハッシュ化
                    $queryUpdate = "UPDATE users SET user_password = '$password' WHERE validation_key = '$validation_key' AND user_email = '$user_email' AND is_active = 1";
                    $query_conUpdate = mysqli_query($connection, $queryUpdate);
                    if (!$query_conUpdate) {
                        die("Query Failed" . mysqli_error($connection));
                    } else {
                        // 成功時
                        $query2 = "UPDATE users SET validation_key = 0 WHERE user_email = '$user_email' AND validation_key = '$validation_key' AND is_active = 1";
                        $query_con2 = mysqli_query($connection, $query2);
                        if (!$query_con2) {
                            die("Query Failed" . mysqli_error($connection));
                        }
                        echo "<div class='notification'>Password updated successfully. <a href='login.php'>login now</a></div>";
                        header("Refresh: 3; url=login.php");
                    }
                } else {
                    echo "<div class='notification'>Invalid Link. Plz reset again and receive resubmit from a new Email😅</div>";
                }
            }

            echo $user_email; // メールアドレス
            echo '<br>';
            echo $validation_key; // トークン
            echo '<br>';
            echo $expire_date; // 有効期限
        } else {
            echo "<div class='notification'>Invalid request</div>";
        }

        // エラー時
        if (isset($errPass)) {
            echo "<div class='notification'>$errPass</div>";
        }

        ?>
        <form action="" method="POST">
            <div class="input-box">
                <input type="password" class="input-control" placeholder="New password" name="new-password" required <?php echo !isset($check) ? "disabled": ""; ?>>
            </div>
            <div class="input-box">
                <input type="password" class="input-control" placeholder="Confirm new password" name="confirm-new-password" required <?php echo !isset($check) ? "disabled": ""; ?>>
            </div>
            <!-- submit -->
            <div class="input-box">
                <input type="submit" class="input-submit" value="SUBMIT" name="submit" <?php echo !isset($check) ? "disabled": ""; ?>>
            </div>
        </form>
    </div>
</div>

<?php include 'layout/footer.php'; ?>