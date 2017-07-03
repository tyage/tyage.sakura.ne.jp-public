<!DOCTYPE html>
<html>
<head>
	<?= $html->charset(); ?>
	<title><?= $title_for_layout; ?></title>
	<?= $html->meta('icon'); ?>

	<?= $html->css('reset'); ?>
	<?= $html->css('basic'); ?>
	<?= $html->css('default'); ?>

	<?= $html->script('html5.js'); ?>
	<?= $html->script('jquery/jquery.js'); ?>
	<?= $html->script('basic.js'); ?>
	<?= $html->script('menu.js'); ?>
	<?= $scripts_for_layout; ?>
	<!--[if IE 6 ]>
		<?= $html->css('ie6'); ?>
	<![endif]-->
</head>
<body>

<header id='header' class='clearfix'>
	<?= $this->element('entry'); ?>

	<nav id='topic'>
		<ul>
			<? foreach ($links as $link): ?>
				<li><?= $html->link($link['title'], $link['url']); ?></li>
			<? endforeach; ?>
		</ul>
	</nav>
</header>

<div id='side'>
	<nav>
		<? if(!empty($self)): ?>
			<ul id='side-content-menu'>
				<li>
					<?= $html->image(
						'chara/'.$self['User']['image'],
						array(
							'url' => '/users/view/'.$self['User']['id'],
							'class' => 'navi',
							'name' => 'ステータス',
							'title' => 'あなたのステータスを表示します。'
						)
					); ?>
				</li>
				<li>
					<?= $html->image(
						'item.gif',
						array(
							'url' => '/user_items/index',
							'class' => 'navi',
							'name' => 'アイテム',
							'title' => '所持しているアイテムを表示します。'
						)
					); ?>
				</li>
				<li>
					<?= $html->image(
						'mail.gif',
						array(
							'url' => '/mails/index',
							'class' => 'navi',
							'name' => 'メール',
							'title' => 'メールを表示します。'
						)
					); ?>
				</li>
				<li>
					<?= $html->image(
						'chat.gif',
						array(
							'url' => '/chats/index',
							'class' => 'navi',
							'name' => 'チャット',
							'title' => 'チャットを表示します。'
						)
					); ?>
				</li>
				<li>
					<?= $html->image(
						'config.gif',
						array(
							'url' => '/users/edit',
							'class' => 'navi',
							'name' => 'ユーザー設定',
							'title' => 'ユーザー情報を設定します。'
						)
					); ?>
				</li>
				<li>
					<?= $html->image(
						'house/1.gif',
						array(
							'url' => '/houses/index',
							'class' => 'navi',
							'name' => '家設定',
							'title' => '家の情報を設定します。'
						)
					); ?>
				</li>
			</ul>
		<? endif; ?>

		<ul>
			<li>
				<?= $html->image(
					'reload.gif',
					array(
						'url' => $html->url('',true),
						'class' => 'navi',
						'name' => '更新',
						'title' => '画面を更新します。'
					)
				); ?>
			</li>
			<? if(!empty($self)): ?>
				<li>
					<?= $html->image(
						'exit.gif',
						array(
							'url' => '/users/logout',
							'class' => 'navi',
							'name' =>'ログアウト',
							'title' => 'ログアウトします。お疲れ様でした。'
						)
					); ?>
				</li>
			<? endif; ?>
			<li>
				<?= $html->image(
					'book.gif',
					array(
						'url' => '/helps/0.トップ',
						'class' => 'navi',
						'name' => 'ヘルプ',
						'title' => '困ったときにお読みください。'
					)
				); ?>
			</li>
		</ul>

		<ul>
			<? foreach ($links as $link): ?>
				<li><?= $html->link($link['title'], $link['url']); ?></li>
			<? endforeach; ?>
		</ul>
	</nav>

	<div id='side-content'>
		<header>
			<a href='#' target='_blank' id='new-window'>新しいウィンドウで開く</a>
			<a href='#' id='close-side'><<<</a>
		</header>
	</div>
</div>

<div id='content' class='clearfix'>
	<?= $session->flash(); ?>

	<?= $content_for_layout; ?>
</div>

<footer id='footer'>
	<address>Copyright &copy; ~2010 チャゲ All rights reserved.</address>
</footer>

<aside id='messages'>
</aside>

<?= $this->element('sql_dump'); ?>

</body>
</html>