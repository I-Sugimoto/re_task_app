<?php

require_once('config.php');
require_once('functions.php');

//DBに接続
$id = $_GET['id'];
$body = $_GET['body'];
$dbh = connectDb();

$sql = "select * from posts where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":id", $id);
$stmt->execute();

$post = $stmt->fetch(PDO::FETCH_ASSOC);

// $postがみつからないときはindex.phpにとばす
if (!$post)
{
  header('Location: index.php');
  exit;
}

$sql = "update tasks set body replace( body, body= :body, '') where id = :id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(":body", $body);
$stmt->bindParam(":id", $id);
$stmt->execute();

header('Location: index.php');
exit;