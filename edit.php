<?php

require_once('config.php');
require_once('functions.php');

//index.phpから受け取ったレコードのid
$id = $_GET['id'];

//データベースの接続
$dbh = connectDb();

//SQLの準備と実行
$sql = "select * from tasks where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

//結果の取得
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  //受け取ったデータ
  $title = $_POST['title'];
  //エラーチェック用の配列
  $errors = array();
  //バリデーション
  if($title == ''){
    $errors['title'] = 'タスク名を入力して下さい';
  }

  if($title == $post['title']){
    $errors['title'] = 'タスク名が変更されていません';
  }
  //エラーがいつもなければレコードを更新
  if(empty($errors)){
    $dbh = connectDb();
    $sql = "update tasks set title = :title, updated_at = now() where id = :id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":title", $title);
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;

  }


}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
  <title>編集画面 tag</title>
</head>
<body>
 <h2>タスクの編集</h2>
  <p>
    <form action="" method="post">
       <!--input タグのvalue属性に値を入れるとそれがフォームの初期値となる。 -->
       <input type="text" name="title" value="<?php echo h($post['title']) ?>" >
       <input type="submit" value="編集">
       <span style="color:red;"><?php echo h($errors['title']); ?></span>
    </form>
  </p>
  <a href="index.php">[戻る]</a>
</body>
</html>