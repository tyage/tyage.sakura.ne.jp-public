<?php
require_once('/home/tyage/lib.php');
$pdo = Database::connect();

$stmt = $pdo->prepare('INSERT INTO `redsheets` (`text`, `created`) VALUES (:text, now())');
$stmt->bindValue(':text', $_POST['text']);
$stmt->execute();

$stmt = $pdo->prepare('SELECT LAST_INSERT_ID() as `id`');
$stmt->execute();
$fetch = $stmt->fetch();
$id = $fetch['id'];

header('HTTP/1.1 301 Moved Permanently');
header('Location: http://tyage.sakura.ne.jp/dev/study/view?id='.$id);
exit();
?>