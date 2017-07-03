<!DOCTYPE html>
<html lang='ja'>
	<head>
		<meta charset="UTF-8">
		<?= $html->meta('icon'); ?>
		<title><?= $title_for_layout ?></title>
		
		<?= $html->css('reset'); ?>
		<?= $html->css('common'); ?>
		
		<?= $html->script('html5'); ?>
		<?= $scripts_for_layout ?>
		
	</head>
	<body>
		<header id='Header'>
			<h1><?= $html->link('エフェクターレビュー', '/') ?></h1>
		</header>
		
		<div id='Content'>
			<div id="ContentInner">
				<?= $this->Session->flash() ?>
				<?= $content_for_layout ?>
			</div>
		
			<footer id='Footer'>
				<p>作った人： <a href="http://twitter.com/tyage">@tyage</a></p>
				<p>原案: <a href="http://twitter.com/keirful">@keirful</a></p>
			</footer>
		
		</div>
		
		<?php echo $this->element('sql_dump'); ?>
	</body>
</html>
