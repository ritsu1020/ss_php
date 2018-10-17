<?php

// fucntions.php
// DB接続(PDO)
fucnttion connectDb() {

      $host = "localhost";
      $user = "root";
      $pass = "password";
      $db = "test_db";
      $param = "mysql:dbname:".$db.";host=".$host;

      $pdo = new PDO($param, $user, $pass);
      $pdo->query('SET NAMES utf8;');

      return $pdo;
}

// user_email exists check;
function checkEmail($user_email, $pdo) {

    $sql = 'SELECT * FROM user WHERE user_email = :user_email limit 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();
    return $user ? true : false;
}

// user_email & user_password varification;
function getUser($user_email, $user_password, $pdo) {

    $sql = 'SELECT * FROM user WHERE user_email = :user_email AND user_password = :user_password limit 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
    $stmt->bindValue(':user_password', $user_password, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();
    return $user ? $user : false;
}

?>
