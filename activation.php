<?php
require_once("includes/db.php");
require_once("includes/functions.php");

if (isset($_GET['eid']) && isset($_GET['token']) && isset($_GET['expire'])) {
  // URL から取得
  $validation_key = $_GET['token'];
  $email = urldecode(base64_decode($_GET['eid'])); // decoding
  $expire = urldecode(base64_decode($_GET['expire'])); // decoding

 // Timezone を設定
  date_default_timezone_set('Asia/Tokyo');
  $current_date = date('Y-m-d H:i:s'); // 現在の日付

  // 現在の日付が有効期限を過ぎているか確認
  if ($current_date >= $expire) {
    echo "<div class='notification'>Link expired😅</div>";
    exit();
  } else {
    // Email, token, is_active = 1 が一致するか確認  
    $query1 = "SELECT * FROM users WHERE user_email = '$email' AND validation_key = '$validation_key' AND is_active = 1";

    $query_con1 = mysqli_query($connection, $query1);

    // Contingency
    if (!$query_con1) {
      die("Query Failed" . mysqli_error($connection));
    }

    // 数を数える
    $count = mysqli_num_rows($query_con1);
    echo '<br />';
    echo $count;

    // DBにあればエラー、なければアップデート
    if ($count == 1) {
      echo "<div class='notification'>Account already activated before😅</div>";
    } else {
      // UPDATE で is_active = 1 に変更
      $query = "UPDATE users SET is_active = 1 WHERE user_email = '$email' AND validation_key = '$validation_key'";

      $query_con = mysqli_query($connection, $query);
      if (!$query_con) {
        die("Query Failed" . mysqli_error($connection));
      } else {
        echo "<div class='notification'>Account activated successfully</div>";
      }
    }
  }


  echo $email;
  echo "<br>";
  echo $validation_key;
}
