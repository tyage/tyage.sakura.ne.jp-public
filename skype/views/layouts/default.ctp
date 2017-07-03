<!DOCTYPE html>
<html lang='ja'>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="「Skype×Skype（スカイプスカイプ）」は、Skype（スカイプ）を通じて通話・チャット・会議などの相手を安全かつ楽しく見つけられるスカイプ友達募集掲示板です。" />
		<meta name="keywords" content="スカイプ,skype,すかいぷ,掲示板,募集,スカイプスカイプ,すかいぷすかいぷ" />
		<?= $html->meta('icon'); ?>
		<title><?= $title_for_layout ?></title>
		
		<?= $html->css('reset'); ?>
		<?= $html->css('common'); ?>
		
		<?= $html->script('html5'); ?>
		<?= $scripts_for_layout ?>
		
		<script>
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-22310335-1']);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
	</head>
	<body>
		<header id='Header'>
			<div id="HeaderInner">
				<?= $html->image('logo.png', array('id' => 'Logo', 'url' => '/')) ?>
				
				<nav>
					<ul>
						<li><?= $html->link('ホーム', '/') ?></li>
						<li><?= $html->link('投稿', '/add/') ?></li>
						<li><?= $html->link('削除', '/delete/') ?></li>
						<li><?= $html->link('編集', '/edit/') ?></li>
						<li><?= $html->link('検索', '/search/') ?></li>
						<li><?= $html->link('お問い合わせ', '/contact/') ?></li>
					</ul>
				</nav>
			</div>
		</header>
		
		<div id='Content'>
			<div id="ContentInner">
				<?= $this->Session->flash() ?>
				<?= $content_for_layout ?>
			</div>
		</div>
		
		<footer id='Footer'>
		</footer>
	</body>
</html>
