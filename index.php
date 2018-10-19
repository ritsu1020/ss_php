<?php

// index.php

require_once 'config.php';
require_once 'functions.php';

session_start();

if (!isset($_SESSIN['USER'])) {

      header('Location:'.SITE_URL.'login.php');
      exit;

}

$user = $_SESSION['USER'];

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

      setToken();

} else {

      checkToken();

      // DB(PDO)
      $pdo = connectDb();

      // POST variable
      $item = $_POST['item'];

      $err = array();

      $complete_msg = '';

      // input check
      if ($item == '') {

            $err['item'] = 'アイテムを入力してください';

      }

      if (empty($err)) {

            $sql = 'INSERT INTO items (item_text, user_id, created_at, updated_at) VALUES(:item_text, :user_id, now(), now())';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':item_text', $item, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $user['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            $complete_msg = 'success! insert item!';
            $item = '';
       }

      unset($pdo);
    }

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>HOME | <?php echo SITE_TITLE; ?></title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
    <link href="css/mykakugen.css" rel="stylesheet">
  </head>

  <body id="main">
  <div class="navbar navbar-inverse navbar-fixed-top" role="navigation" >
    <div class="container">
      <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">ナビゲーションの切替</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        <a class="navbar-brand" href="<?php echo SITE_URL; ?>"><?php echo SITE_TITLE; ?></a>
      </div>
    <div class="container">
        <div id="gnavi" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li><a href="./index.php">add</a></li>
                <li class="active"><a href="./item_list.php">birthday-list</a></li>
                <li><a href="./setting.php">setting</a></li>
                <li><a href="./logout.php">logout</a></li>
            </ul>
        </div>
     </div>
   </div>
 </div><!-- /.navbar -->
<div class="container">
      <h1>HOME</h1>
      <?php if ($complete_msg): ?>
        <div class="alert alert-success">
            <?php echo $complete_msg; ?>
        </div>
      <?php endif; ?>
      <form method="POST" class="panel panel-default panel-body">
          <div class="form-group <?php if ($err['item'] != '') echo 'has-error'; ?>">
          <label>add birthday</label>
          <input class="form-control" type="text" name="item" value="<?php echo h($item); ?>"  placeholder="add birthday" />
          <span class="help-block"><?php echo $err['item']; ?></span>
          </div>
          <div class="form-group">
          <input class="btn btn-success btn-block" type="submit" value="add">
          </div>
          <input type="hidden" name="token" value="<?php echo h($_SESSION['sstoken']); ?>">
      </form>
      <hr>
      <footer class="footer">
        <p><?php echo COPYRIGHT; ?></p>
      </footer>
</div><!--/.container-->
</body>
</html>
