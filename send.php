<?php

// send.php

require_once 'config.php';
require_once 'functions.php';

// get & post not access;
if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {

      echo '<html><head><meta charset="utf-8"></head><body>不正なアクセス</body></html>';
      exit;
} else {

      $pdo = connectDb();

      // 現在の時刻を設定しているユーザ-を全て抽出
      $sql = 'SELECT * FROM user WHERE delivery_hou = :delivery_hour';
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':delivery_hour', date('G'));
      $stmt->execute();

      // 抽出したユーザ-でループ
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // 対象ユーザ-(自分を含め現在の時刻を設定しているユーザー)のitemを全て取得
            $sql2 = 'SELECT * FROM items WHERE user_id = :user_id';
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindValue(':user_id', $row['id'], PDO::PARAM_INT);
            $stmt2->execute();
            $items = $stmt2->fetchAll();

            // 取得したitemからランダムに1件抽出
            if ($items) {

                  $rand_no = array_rand($items);
                  $target_item = $items[$rand_no];

                  // mb_send_mail
                  $mail_title = 'happybirthday!';
                  $mail_body = $target_item['item_text'].PHP_EOL;

                  if (!empty($mail_body)) {

                        mb_language('japanese');
                        mb_internal_encoding('UTF-8');

                        mb_send_mail($row['user_email'], $mail_title, $mail_body);
                  }
            }
      }

      unset($pdo);
      exit;
}

?>
