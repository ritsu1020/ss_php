<?php

// setting.php

require_once 'config.php';
require_once 'fucntions.php';

session_start();

if (!isset($_SESSION['USER'])) {

      header('Location:'.SITE_URL.'login.php');
      exit;

}

$user = $_SESSION['USER'];

if ($_SERVER['REQUEST_MEHOD'] != 'POST') {

} else {

      $delivery_hour = $_POST['delivery_hour'];

      $pdo = connectDb();

      // input check;
      $err = array();
      $complete_msg = '';

      if ($delivery_hour == '') {

            $err['delivery_hour'] = '通知時刻を設定してください';

      }

      if (empty($err)) {

            $sql = 'UPDATE user SET delivery_hour = :delivery_hour, updated_at = now() WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':delivery_hour', $delivery_hour, PDO::PARAM_STR);
            $stmt->bindValue(':id'=>$user['id'], PDO::PARAM_INT);
            $stmt->execute();

            // session updated.
            $user['delivery_hour'] = $delivery_hour;
            $_SESSION['USER'] = $user;

            $complete_msg = 'success! updated.';
      }

      unset($pdo);

}

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>setting | <?php echo SITE_TITLE; ?></title>
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
      <h1>setting</h1>
      <form method="POST" class="panel panel-default panel-body">
        <div class="form-group <?php if ($err['delivery_hour'] != '') echo 'has-error'; ?>">

        <label>メール通知</label>

        <?php

         arrayToSelect('delivery_hour', $delivery_hour_array, $user['delivery_hour']);

         ?>

        <span class="help-block"><?php echo $err['delivery_hour']; ?></span>

      </div>

      <div class="form-group">

        <input class="btn btn-success btn-block" type="submit" value="save">

      </div>
      </form>
      <hr>
      <footer class="footer">
        <p><?php echo COPYRIGHT; ?></p>
      </footer>
</div><!--/.container-->
</body>
</html>
