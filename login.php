<?php

// login.php

require_once 'config.php';
require_once 'funcitons.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

      // 自動ログイン情報があるかどうかcookieをチェック
      if (isset($_COOKIE['HOGEHOGE'])) {

            // 自動ログイン情報があればキーを取得
            $auto_login_key = $_COOKIE['HOGEHOGE'];

            // 自動ログインキーをDBに照合
            $sql = 'SELECT * FROM auto_login WHERE c_key = :c_key AND expire >= :expire limit 1';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':c_key', $auto_login_key);
            $stmt->bindValue(':expire', date('Y-m-d H:i:s'));
            $stmt->execute();
            $row = $stmt->fetch();

            // 照合に成功したら自動ログイン
            if ($row) {

                  $user = getUserbyUserId($row['user_id'], $pdo);

                  session_regenerate_id(true);
                  $_SESSION['USER'] = $user;

                  header('Location:'.SITE_URL.'index.php');
                  unset($pdo);
                  exit;
            }
      }

      setToken();

} else {

      checkToken();

      // formからsubmitされたときの処理

      // POSTされた値を変数に保存
      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];
      $auto_login = $_POST['auto_login'];

      // DB(PDO)
      $pdo = connectDb();

      // 入力チェック
      $err = array();

      if ($user_email == '') {

            $err['user_email'] = 'メールアドレスを入力してください';

      } else {

      if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {

            $err['user_email'] = 'メールアドレスが不正です';

      } else {

        if (!checkEmail($user_email, $pdo)) {

              $err['user_email'] = 'このメールアドレスは登録されていません';
        }
      }
    }
        if ($user_password == '') {

              $err['user_password'] = 'パスワードを入力してください';
        }

        $user = getUser($user_email, $user_password, $pdo);

        if (!$user) {

              $err['user_password'] = 'メールアドレスかパスワードが正しくありません';
        }

        // errorがなければHOME画面に遷移
        if (empty($err)) {

          // userデータをセッションに保存
          session_regenerate_id(true);
          $_SESSION['USER'] = $user;

          // 自動ログイン情報を一度クリアする
          if (isset($_COOKIE['HOGEHOGE'])) {

                $auto_login_key = $_COOKIE['HOGEHOGE'];
                setcooke('HOGEHOGE', '', time()-86400, '/');

                // DB delete
                $sql = 'DELETE FROM auto_login WHERE c_key = :c_key';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':c_key', $auto_login_key);
          }

          // 自動ログイン希望の場合はcookieとDBに情報を登録する
          if ($auto_login) {

                // auto_login_key
                $auto_login_key = sha1(uniqid(mt_rand(), true));

                // cookie saved
                setcookie('HOGEHOGE', $auto_login_key, time()+3600*24*365, '/');

                // DB saved
                $sql = 'INSERT INTO auto_login (user_id, c_key, expire, created_at, updated_at)
                Values (:user_id, :c_key, :expire, now(), now())';
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $user['id']);
                $stmt->bindValue(':c_key', $auto_login_key);
                $stmt->bindValue(':expire', date('Y-m-d H:i:s', time()+3600*24*365));
                $stmt->execute();

          }

              header('Location:'.SITE_URL);
              unset($pdo);
              exit;
        }

           unset($pdo);

 }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>ログイン画面 | <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body id="main">
    <div class="nav navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php echo SITE_TITLE; ?></a>
            </div>
        </div>
    </div>
    <div class="container">
        <h1>signup</h1>

        <form method="POST" class="sidebar-nav panel panel-default">
            <div class="form-group">
                <label>email</label>
                <input type="text" class="form-control" name="user_email" value="<?php echo h($user_email); ?>" placeholder="メールアドレス" />
                <span class="help-block"></span>
            </div>

            <div class="form-group">
                <label>password</label>
                <input type="password" class="form-control" name="user_password" value="" placeholder="パスワード" />
                <span class="help-block"></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success btn-block" value="ログイン">
            </div>

            <div class="form-group">
                <input type="checkbox" name=auto_login"">次回から自動ログイン
            </div>

            <input type="hidden" name="token" value="<?php echo h($_SESSION['sstoken']); ?>" />
        </form>
        <hr>
        <footer class="footer">
            <p><?php echo COPYRIGHT; ?></p>
        </footer>
    </div>

    <script src="//code.jquery.com/jquery.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
