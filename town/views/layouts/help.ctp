<!DOCTYPE html>
<html>
<head>
	<?= $html->charset(); ?>
	<title><?= $title_for_layout; ?></title>
	<?= $html->meta('icon'); ?>
	<?= $html->css('reset'); ?>
	<?= $html->css('basic'); ?>
	<?= $html->css('help'); ?>
	<?= $html->css('tree'); ?>

	<?= $html->script('html5.js'); ?>
	<?= $html->script('jquery/jquery.js'); ?>
	<?= $html->script('basic.js'); ?>
	<?= $html->script('tree.js'); ?>
	<?= $scripts_for_layout; ?>
</head>
<body>
	<div class='clearfix'>
		<nav id='side' class='inline'>
			<?= $folder->tree(VIEWS,DS.'helps',$current); ?>
		</nav>

		<div id='content' class='inline'>
			<h1><?= $title_for_layout ?></h1>

			<? $session->flash(); ?>

			<?= $content_for_layout; ?>
		</div>
	</div>

	<footer id='footer'>
		<address>Copyright &copy; ~2010 チャゲ All rights reserved.</address>
	</footer>

	<?= $cakeDebug; ?>

</body>
</html>