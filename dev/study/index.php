<?
$title = '赤シート2.0';
$styles[] = 'style';
$styles[] = '/css/sexybuttons';
?>

<header class='clearfix'>
	<h1><span class='red'>赤</span>シート<span class='ver'>2.0</span></h1>
	<h2 class='red'><a href='usage'>? How to use ?</a></h2>
</header>

<form action='create' method='POST' id='textForm'>
<textarea name='text'></textarea>
<button type='submit' class="sexybutton sexysimple sexylarge"><span class="ok">投稿</span></button>
</form>

<?php
require_once('/home/tyage/lib.php');

$pdo = Database::connect();
$stmt = $pdo->prepare('SELECT `id`, `text`, `created` FROM `redsheets` ORDER BY `created` DESC LIMIT 5');
$stmt->execute();
?>
<ul id='recent' class='clearfix'>
	<? while ($data = $stmt->fetch()): ?>
		<li>
			<a href='view?id=<?= $data['id'] ?>'><?= ellipsis($data['text']) ?></a>
		</li>
	<? endwhile ?>
</ul>