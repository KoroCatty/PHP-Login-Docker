<?php
ob_start();
session_start();
require_once("includes/functions.php");
require_once("includes/db.php");

// Delete Cookie
if (isset($_COOKIE['_ucv_'])) {
    global $connection; // このfunctionのscope 内からアクセスできるようにする

    $selector = escape(base64_decode($_COOKIE['_ucv_'])); // クッキーの値をデコード
    // DB の is_expired を 1 に更新
    $query = "UPDATE remember_me SET is_expired = '-1' WHERE selector = '$selector' AND is_expired = 0";
    $query_con = mysqli_query($connection, $query);
    if (!$query_con) {
        die("Query Failed" . mysqli_error($connection));
    }

    setcookie('_ucv_', '', time() - 60 * 60); // 1時間マイナスして削除
}

// Delete Session
if (isset($_SESSION['login'])) {
    session_destroy();
    unset($_SESSION['login']); // session から login の値 を削除
}

header("Location: login.php");
