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