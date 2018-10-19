<?php

// item_edit.php

require_once 'config.php';
require_once 'fucntions.php';

session_start();

if (!isset($_SESSION['USER'])) {

   header('Location:'.SITE_URL.'login.php');
   exit;

}

$user = $_SESSION['USER'];
$id = $_GET['id'];

$pdo = connectDb();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

      $sql = 'SELECT * FROM items WHERE id = :id limit 1';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $item = $stmt->fetch();
      $item_text = $item['item_text'];

      setToken();

} else {

      checkToken();

      $item_text = $_POST['item'];
      // input check;
      $err = array();
      $complete_msg = '';

      if ($item_text == '') {

            $err['item_text'] = 'アイテムを入力してください';

      } else {

            if (strlen(mb_conver_encoding($item_text, 'SJIS', 'UTF-8')) > 200) {

                  $err['item_text'] = '200byte以下で入力してください';
            }
      }

      if (empty($err)) {

            $sql = 'UPDATE items SET item_text = :item_text, updated_at = now() WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $complete_msg = 'success! item saved.';
      }
}

unset($pdo);

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>item_edit | <?php echo SITE_TITLE; ?></title>
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
      <h1>item_edit</h1>
      <?php if($complete_msg): ?>
        <div class="alert alert-success">
          <?php echo $complete_msg; ?>
        </div>
      <?php endif; ?>
      <form method="POST" class="panel panel-default panel-body">
          <div class="form-group <?php if($err['item_text'] != '') echo 'has-error'; ?>">
          <label>add birthday</label>
          <input class="form-control" type="text" name="item" value="<?php echo h($item_text); ?>" />
          <span class="help-block"><?php echo $err['item_text']; ?></span>
          </div>
          <div class="form-group">
          <input type="hidden" name="token" value="<?php echo h($_SESSION['sstoken']); ?>">
          <input class="btn btn-success btn-block" type="submit" value="edit">
          </div>
      </form>
      <hr>
      <footer class="footer">
        <p><?php echo COPYRIGHT; ?></p>
      </footer>
</div><!--/.container-->
</body>
</html>
