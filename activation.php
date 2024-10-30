<?php

require_once("includes/db.php");
require_once("includes/functions.php");

if (isset($_GET['eid']) && isset($_GET['token'])) {
  // URL から取得
  $validation_key = $_GET['token'];
  $email = urldecode(base64_decode($_GET['eid'])); // decoding

  // decoding 
  // $email = urldecode(base64_decode($_GET['eid']));
  // $validation_key = urldecode(base64_decode($_GET['token']));

  echo $email;
  echo "<br>";
  echo $validation_key;

  // Email, token, is_active = 1 が一致するか確認  
  $query1 = "SELECT * FROM users WHERE user_email = '$email' AND validation_key = '$validation_key' AND is_active = 1";

  $query_con1 = mysqli_query($connection, $query1);

  if (!$query_con1) {
    die("Query Failed" . mysqli_error($connection));
  }


  $count = mysqli_num_rows($query_con1);
  echo '<br />';
  echo $count;

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
