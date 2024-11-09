<?php 

// この関数を使い回す
function escape($string) {
  global $connection; // このfunction scope 内からグローバル変数を使えるようにする
   // シングルクォート ' やダブルクォート " など）をエスケープする
  return mysqli_real_escape_string($connection, $string );
}

// 数値を与えて、その数値の分だけランダムな文字列を生成
function getToken($length) {
  $rand_str = md5(uniqid(mt_rand(), true)); // 一意のIDを生成
  $base64_encode = base64_encode($rand_str); // base64エンコード
  
   //  + と = を削除 第一は置換される文字、第二は置換する文字、第三は対象の文字列
  $modified_base64_encode = str_replace(array('+', '='), array('',''), $base64_encode);

  $token = substr($modified_base64_encode, 0, $length); // トークンの長さを指定
  return $token;
}

// クッキーを確認し,それが存在していれば、DBに格納された情報と照合し、期限が切れていないかを判断
function isAlreadyLoggedIn() {
  global $connection;

  date_default_timezone_set("asia/tokyo");
  $current_date = date("Y-m-d H:i:s"); // 2021-08-01 12:00:00
  if (isset($_COOKIE['_ucv_'])) {
    $selector = escape(base64_decode($_COOKIE['_ucv_']));

    $query = "SELECT * FROM remember_me WHERE selector = '$selector' AND is_expired = 0";
    $query_con = mysqli_query($connection, $query);
    if (!$query_con) {
      die("Query Failed" . mysqli_error($connection));
    }

    // クエリ結果から1行分のデータを連想配列として取得
    $result = mysqli_fetch_assoc($query_con);
    // 該当するレコードが1件のみである場合のみ
    if (mysqli_num_rows($query_con) == 1 ) {
      $expire_date = $result['expire_date'];

      // 現在の日付よりも前であるかを確認し、過ぎていれば true を返します。つまり、期限が切れている場合には true を返し、ログイン状態と見なす
      if ($expire_date >= $current_date) {
        return true;
      } 
    }
  }
}