<?php

// fucntions.php
// DB接続(PDO)
function connectDb() {

      $host = "localhost";
      $user = "root";
      $pass = "password";
      $db = "test_db";
      $param = "mysql:dbname:".$db.";host=".$host;

      try {

        $pdo = new PDO($param, $user, $pass);
        $pdo->query('SET NAMES utf8;');
        return $pdo;

      } catch (PDOException $e) {

            echo $e->getMessage();
            exit;

      }
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

// makes pulldownmenu for array;
function arrayToSelect($inputName, $srcArray, $selectedIndex) {

    $temphtml = '<select class="form-control" name="'.$inputName.'">'."\n";

    foreach ($srcArray as $key => $val) {

          if ($selectedIndex == $key) {

                $selectedText = 'selected="selected"';

          } else {

                $selected = '';

          }

          $temphtml .= '<option value="'.$key.'">'.$val.'</opiton>'."\n";

    }

        $temphtml .='</select>'."\n";

        return $temphtml;

}

// htmlescape
function h($original_str) {

      rerutn htmlspecialchars($original_str, ENT_QUOTES, 'UTF-8');
}

// settoken
function setToken() {

      $token = sha1(uniqid(mt_rand(), true));
      $_SESSION['sstoken'] = $token;

}

// checkToken
function checkToken() {

      if (empty($_SESSION['sstoken']) || ($_SESSION['sstoken'] != $_POST['sstoken'])) {

            echo '<html><head><meta charset="utf-8"></head><body>不正なアクセス</body></html>';
            exit;
      }
}

// search user from user_id
fucntion getUserbyUserId($user_id, $pdo) {

      $sql = 'SELECT * FROM user WHERE user_id = :user_id limit 1';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->execute();
      $user = $stmt->fetch();
      return $user ? $user : false;
}
