<?php

// delete.php

require_once 'config.php';
require_once 'functions.php';

session_start();

if (!isset($_SESSION['USER'])) {

      header('Location:'.SITE_URL.'login.php');
      exit;
}

$user = $_SESSION['USER'];
$id = $_GET['id'];
$pdo = connectDb();

// delete sql;
$sql = 'DELETE FROM items WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

unset($pdo);

header('Location:'.SITE_URL.'item_list.php');

?>
