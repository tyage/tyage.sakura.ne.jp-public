<?php
$title = 'ひなプラス';

require_once('/home/tyage/lib.php');
$pdo = Database::connect();

$stmt = $pdo->prepare('SELECT * FROM `hinaplus` WHERE `id` = :id');
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$data = $stmt->fetch();

$hungry = $data['hungry'] + ($_GET['glass'] ? -10 : 10);
if ($hungry > 100) $hungry = 100;
if ($hungry < 0) $hungry = 0;
$stmt = $pdo->prepare('UPDATE `hinaplus` SET `hungry` = :hungry WHERE `id` = :id');
$stmt->bindValue(':id', $data['id']);
$stmt->bindValue(':hungry', $hungry);
$stmt->execute();

if ($hungry < 10 or $hungry > 90) {
	$stmt = $pdo->prepare('DELETE FROM `hinaplus` WHERE `id` = :id');
	$stmt->bindValue(':id', $data['id']);
	$stmt->execute();
	$die = true;
}
?>

<p style='font-size:60px;'><?= $data['chara'] ?></p>

<? if ($die): ?>
	<p>あなたのかわいいひなたんは<?= ($hungry > 90 ? 'エビフライを喉につまらせて' : '') ?>死んでしまいました！(´；ω；｀)ﾌﾞﾜｯ</p>
<? else: ?>
	<? if ($_GET['glass']): ?>
		<? if ($data['hungry'] >= 80): ?>
			<p>お腹ｽｯｷﾘ!</p>
		<? elseif ($data['hungry'] >= 50): ?>
			<p>お腹が減って力がでない・・・</p>
		<? elseif ($data['hungry'] >= 20): ?>
			<p>お腹が空っぽ・・・</p>
		<? else: ?>
			<p>ｳｸﾞｯ...ｳｳｯ</p>
		<? endif ?>
	<? else: ?>
		<? if ($data['hungry'] >= 80): ?>
			<p>く...え..ない..</p>
			<p style='color:red'>胸焼け防止のため、雑草を用意してください</p>
		<? elseif ($data['hungry'] >= 70): ?>
			<p>腹八分目じゃー</p>
		<? elseif ($data['hungry'] >= 40): ?>
			<p>エビ！エビ！</p>
		<? else: ?>
			<p>もっと食わせろ！</p>
		<? endif ?>
	<? endif ?>

	<a href='feed.php?id=<?= $data['id'] ?>'>エビフライ</a>
	<a href='feed.php?id=<?= $data['id'] ?>&amp;glass=true'>雑草</a>
<? endif ?>