<?php
$styles = array('/css/reset', '/css/default');
$scripts = array('/js/html5');
$template = true;

ob_start();
$url = $_GET['url'] === 'index' ? 'home' : $_GET['url'];
require($url.'.php');
$content = ob_get_clean();

if ($title) $title .= ' - チャゲラ';
else $title = 'チャゲラ';
?>
<? if ($template): ?>
<!DOCTYPE html>
<html lang='ja'>
	<head>
		<meta charset='utf-8' />
		<title><?= $title ?></title>
		
		<? foreach ($styles as $style): ?>
			<link rel='stylesheet' href='<?= $style ?>.css' />
		<? endforeach ?>
		
		<script src='http://www.google.com/jsapi'></script>
		<script>
			google.load('jquery', '1');
		</script>
		<? foreach ($scripts as $script): ?>
			<script src='<?= $script ?>.js'></script>
		<? endforeach ?>
	</head>
	<body>
		<div id='Content'>
			<?= $content ?>
		</div>
		
		<footer id='Footer'>
			<p>&copy;2010 <a href='/'>チャゲラ</a></p>
		</footer>
	</body>
</html>
<? else: ?>
<?= $content ?>
<? endif ?>
