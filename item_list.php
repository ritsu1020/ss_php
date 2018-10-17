<?php

// item_list.php

require_once 'config.php';
require_once 'functions.php';

session_start();

if (!isset($_SESSION['USER'])) {

      header('Location:'.SITE_URL.'login.php');
      exit;
}

$user = $_SESSION['USER'];

$pdo = connectDb();

$items = array();

$sql = 'SELECT * FROM items WHERE user_id = :user_id ORDER BY created_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':user_id', $user['id'], PDO::PARAM_INT);
$stmt->execute();

foeach ($stmt->fetchAll() as $row) {

      array_push($items, $row);

}

unset($pdo);

?><!DOCTYPE html>
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
      <h1>item_list</h1>
      <?php if(!$items): ?>
        <div class="alert alert-danger">アイテムは登録されていません。</div>
      <?php endif; ?>
    <ul class="list-group">
      <?php foreach ($items as $item):?>
        <li class="list-group-item">
          <?php echo $item['item_text']; ?>
          <a href="item_edit.php?id=<?php echo $item['id']; ?>">edit</a>
          <a href="javascript:void(0);" onclick="var ok=confirm('削除してもよろしいですか？');

          if (ok) location.href='delete.php?id=<?php echo $item['id']; ?>'; return false;">
            delete</a>
        </li>
      <?php endforeach; ?>
    </ul>
    <a href="./index.php">back</a>
      <hr>
      <footer class="footer">
        <p><?php echo COPYRIGHT; ?></p>
      </footer>
</div><!--/.container-->
</body>
</html>
