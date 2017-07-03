<?php
$title = 'ひなプラス';

require_once('/home/tyage/lib.php');
$pdo = Database::connect();

$stmt = $pdo->prepare('SELECT * FROM `hinaplus` WHERE `id` = :id');
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$data = $stmt->fetch();
?>

<p>あなたのひなたんです。</p>
<p>かわいがってね！</p>

<p style='font-size:60px;'><?= $data['chara'] ?></p>
<a href='feed.php?id=<?= $data['id'] ?>'>エビフライ</a>