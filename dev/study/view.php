<?php
$styles[] = 'style';
$scripts[] = 'script';

require_once('/home/tyage/lib.php');
$pdo = Database::connect();

$stmt = $pdo->prepare('SELECT `id`, `text`, `created` FROM `redsheets` WHERE `id` = :id');
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$data = $stmt->fetch();

$pattern = '/\?((.|\n)*?)\?/';
$replacement = '<span class="hide">$1</span>';
$text = preg_replace($pattern, $replacement, h($data['text']));

$title = ellipsis($data['text']);
?>

<nav>
	<p><a href='#' id='viewSource'>ソース</a></p>
	<textarea id='source'><?= h($data['text']) ?></textarea>
</nav>

<div class='text'>
	<pre class='wrap'><?= $text ?></pre>
	<p class='time'><time><?= $data['created'] ?></time></p>
</div>

<p><a href='./'>赤シート2.0</a></p>
