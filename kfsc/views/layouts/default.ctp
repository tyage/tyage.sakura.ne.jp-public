<?php
/*
 * $user : ログイン時のユーザー情報
 */
$isAdmin = (!is_null($user) and $user['User']['role'] === 'admin');
$prefix = $isAdmin ? '/admin' : '';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<?= $html->charset(); ?>
	<title><?= $title_for_layout; ?></title>
	<?= $html->css('default'); ?>
	<?= $javascript->link('html5'); ?>
</head>
<body>
	<header>
		<div>
			<h1><?= $html->link('関西生徒会連盟','/'); ?></h1>
			
			<div id='login'>
				<? if(empty($user)): ?>
					<?= $html->link('ログイン','/users/login/'); ?>
				<? else: ?>
					<?= $user['User']['username']; ?> さん
					<?= $html->link('ログアウト','/users/logout/'); ?>
				<? endif; ?>
			</div>
		</div>

		<nav>
			<ul>
				<li><?= $html->link('トップ','/'); ?></li>
				<li><?= $html->link('概要','/pages/about'); ?></li>
				<li><?= $html->link('加盟校','/pages/schools'); ?></li>
				<li><?= $html->link('連盟憲章','/pages/rule'); ?></li>
				<li><?= $html->link('連絡版',$prefix.'/topics/'); ?></li>
				<li><?= $html->link("What's new?",$prefix.'/news/'); ?></li>
				<li><?= $html->link('お知らせ',$prefix.'/reports/'); ?></li>
				<? if ($isAdmin): ?>
					<li><?= $html->link('ユーザー管理',$prefix.'/users/'); ?></li>
					<li><?= $html->link('ログイン履歴',$prefix.'/accesses/'); ?></li>
					<li><?= $html->link('ページ編集',$prefix.'/pages/'); ?></li>
				<? endif; ?>
			</ul>
		</nav>
	</header>

	<article>
		<?= $content_for_layout; ?>
	</article>

	<footer>
		<address>
			お問い合わせは<a href='mailto:kfsc1114@livedoor.com'>kfsc1114@livedoor.com</a>まで宜しくお願いします。
		</address>
	</footer>

	<?= $javascript->link('jquery'); ?>
	<?= $javascript->link('lavalamp'); ?>
	<?= $javascript->link('basic'); ?>
	<?= $scripts_for_layout; ?>
</body>
</html>