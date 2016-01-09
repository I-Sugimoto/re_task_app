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
  $body = $_POST['body'];
  //エラーチェック用の配列
  $errors = array();
  //バリデーション
  if($body == ''){
    $errors['body'] = '詳細を入力して下さい';
  }

  if($body == $post['body']){
    $errors['body'] = '詳細が変更されていません';
  }
  //エラーがいつもなければレコードを更新
  if(empty($errors)){
    $dbh = connectDb();
    $sql = "update tasks set body = :body, updated_at = now() where id = :id";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(":body", $body);
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
  <title>詳細画面</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
 <h2>詳細</h2>
 <?php echo h($post['body']) ?>
 <h2>詳細の編集</h2>
  <p>
    <form action="" method="post">
       <!--<textarea>はタグの間に値をいれるとそれが入力フォームの初期値になる。 -->
       <input type="text" name="body" value="<?php echo h($post['body']) ?>" >

      <!--<textarea>を利用すると、何故かバリデーションが無視される。原因は不明。 -->
     <!-- <textarea name="body" cols="30" rows="5"><?php echo h($post['body']) ?> </textarea> -->
       <input type="submit" value="編集">
       <span style="color:red;"><?php echo h($errors['body']); ?></span>
    </form>
  </p>
  <a href="index.php">[戻る]</a>
</body>
</html>