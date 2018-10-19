<?php

// logout.php

require_once 'config.php';
require_once 'functions.php';

session_start();

$pdo = connectDb();

// 自動ログイン情報をクリア
if (isset($_COOKIE['HOGEHOGE'])) {

      $auto_login_key = $_COOKIE['HOGEHOGE'];

      // clear cookie
      setcookie('HOGEHOGE', '', time()-86400, '/');

      // clear // DB
      $sql = 'DELETE FROM auto_login WHERE c_key = :c_key';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':c_key', $auto_login_key);
      $stmt->execute();
}

$_SESSION['USER'] = array();

if (isset($_COOKIE[session_name()])) {

      setcookie(session_name(), '', time()-86400, '/');

}

session_destroy();

unset($pdo);

header('Location:'.SITE_URL.'login.php');

?>
