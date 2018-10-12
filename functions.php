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

?>
