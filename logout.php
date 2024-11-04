<?php
    ob_start();
    session_start();

    if(isset($_SESSION['login'])) {
        session_destroy();
        unset($_SESSION['login']); // session から login の値 を削除
        header("Location: login.php");
    }

?>