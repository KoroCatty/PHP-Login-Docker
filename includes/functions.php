<?php 

// この関数を使い回す
function escape($string) {
  global $connection; // DB 接続をグローバルスコープにする
   // シングルクォート ' やダブルクォート " など）をエスケープする
  return mysqli_real_escape_string($connection, $string );
}