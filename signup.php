<?php

// signup
require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

      // getでアクセスしたときの処理

} else {

      // formからsubmitされたときの処理
      // POSTされた値を変数に保存
      $user_name = $_POST['user_name'];
      $user_email = $_POST['user_email'];
      $user_password = $_POST['user_password'];

      // DB接続(PDO)
      $pdo = connectDb();

      // 入力チェック
      $err = array();

      // user_name Check
      if ($user_name == '') {

            $err['user_name'] = 'user_nameを入力してください';

      } else {

            if (strlen(mb_convert_encoding($user_name, 'SJIS', 'UTF-8')) > 30) {

                  $err['user_name'] = 'user_nameが長すぎます。30byte以下で入力してください';

            }
      }

      // user_email check
      if ($user_email == '') {

            $err['user_email'] = 'user_emailを入力してください';

      } else {

            if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {

                  $err['user_email'] = 'user_emailの形式が不正です';

            } else {

              // mail_address存在チェック
              $sql = 'SELECT * FROM user WHERE user_email = :user_email limit 1';
              $stmt = $pdo->prepare($sql);
              $stmt->bindValue(':user_email', $user_email, PDO::PARAM_STR);
              $stmt->execute();
              $user = $stmt->fetch();

              if ($user) {

                    $err['user_email'] = 'このメールアドレスは既に登録されています';
              }
          }
      }

      // 入力内容にエラーがなければDBにINSERT
      if (empty($err)) {

            $sql = 'INSERT INTO user (user_name, user_password, user_email) VALUES (:user_email, :user_password, :user_email)';
            $stmt = $pdo->prepare($sql);
            $params = array(':user_name'=>$user_name, ':user_password'=>$user_password, ':user_email'=>$user_email);
            $stmt->execute($params);

            // auto login user_email & user_password varification
            $user = getUser($user_email, $user_password, $pdo);

            // user data save session;
            $_SESSION['USER'] = $user;

            unset($pdo);

            // signup_complete.php
            header('Location:'.SITE_URL.'signup_complete.php');
            exit;
      }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>add user | <?php echo SERVICE_NAME; ?></title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body id="main">
    <div class="nav navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php echo SERVICE_SHORT_NAME; ?></a>
            </div>
        </div>
    </div>
    <div class="container">
        <h1>signup</h1>

        <form method="POST" class="sidebar-nav panel panel-default">
            <br />
            <div class="form-group <?php if($err['user_name'] != '') echo 'has-error'; ?>">
                <label>name</label>
                <input type="text" class="form-control" has-error name="user_name" value="<?php echo $user_name; ?>" placeholder="氏名" />
                <span class="help-block"><?php echo $err['user_name']; ?></span>
            </div>

            <div class="form-group <?php if($err['user_email'] != '') echo 'has-error'; ?>">
                <label>email</label>
                <input type="text" class="form-control" name="user_email" value="<?php echo $user_email; ?>" placeholder="メールアドレス" />
                <span class="help-block"><?php echo $err['user_email']; ?></span>
            </div>

            <div class="form-group <?php if($err['user_password'] != '') echo 'has-error'; ?>">
                <label>password</label>
                <input type="password" class="form-control" name="user_password" value="" placeholder="パスワード" />
                <span class="help-block"><?php echo $err['user_password']; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success btn-block" value="アカウントを作成する">
            </div>

            <input type="hidden" name="token" value="" />
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
