<?php

// 設定ファイルと関数ファイルを読み込む
require_once('config.php');
require_once('functions.php');

// DBに接続 connectDb()で統一。
$dbh = connectDb(); // 特にエラー表示がなければOK

//レコードの取得 table name = tasks
$sql = "select * from tasks";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

  $title = $_POST['title'];

  $erroes = array();

  if($title == ''){
    $errors['title'] = 'タスク名を入力して下さい';
  }

  if(empty($errors)){
    $dbh = connectDb();
    //bodyは別ページで詳細を入力するため、今回はコードに表示せず。
    $sql = "insert into tasks (title, created_at, updated_at) values (:title, now(), now())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->execute();

    //index.phpに戻る
    header('Location:  index.php');
    exit;
  }
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>タスク管理</title>
</head>
<body>
<h1>タスク管理アプリ</h1>
  <p>
      <form action="" method="post">
        <input type ="text" name ="title">
        <input type ="submit" value="追加">
        <span style ="color:red;"><?php echo h($errors['title']) ?></span>
      </form>
  </p>

<h2>未完了タスク</h2>
      <?php if ($tasks['status'] == 'done') : ?>
  <h3>未完了タスクは残り<?php echo h(count($tasks['status'] == 'notyet')); ?>です。</h3>
   <?php endif; ?>
  <ul>
    <?php foreach ($tasks as $task) : ?>
      <?php if ($task['status'] == 'notyet') : ?>
        <li>
          <a href="done.php?id=<?php echo h($task['id']); ?>">[完了]</a>
          <?php echo h($task['title']); ?>
          <a href="edit.php?id=<?php echo h($task['id']); ?>">[編集]</a>
          <a href="delete.php?id=<?php echo h($task['id']); ?>">[削除]</a>
          <a href="show.php?id=<?php echo h($task['id']); ?>">[詳細]</a>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
<hr>

<h2>完了したタスク</h2>
  <?php foreach ($tasks as $task) : ?>
      <?php if ($task['status'] == 'done') : ?>
  <h3>完了したタスクは残り<?php echo h(count($task['id'])); ?>です。</h3>
      <?php endif; ?>
  <?php endforeach; ?>
<ul>
    <?php foreach ($tasks as $task) : ?>
      <?php if ($task['status'] == 'done') : ?>
        <li>
          <a href="return.php?id=<?php echo h($task['id']); ?>">[未完了に戻す]</a>
            <?php echo h($task['title']); ?>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>

</body>
</html>