<div id='towns' class='inline'>
	<?= $this->element('town',array('town' => $town)); ?>
</div>

<div class='inline'>
	<section>
		<h2>ログイン</h2>
		<? if ($session->check('Message.auth')): ?>
			<?= $session->flash('auth'); ?>
		<? endif; ?>
		<?= $form->create('User',array('action' => 'login')); ?>
		<?= $form->input('username',array('label' => '名前')); ?>
		<?= $form->input('password',array('label' => 'パスワード')); ?>
		<?= $form->end('ログイン'); ?>
	</section>

	<section>
		<h2>新規登録</h2>
		<?= $html->link('新規登録','/users/add/'); ?>
	</section>

	<section>
		<h2>最新情報</h2>

		<p><a href="http://tyage.sakura.ne.jp/blog/?p=910">TOWN ver 1.6.0</a></p>
		<p><a href="http://tyage.sakura.ne.jp/blog/?p=703">TOWN ver 1.5.0</a></p>
		<p><a href="http://tyage.sakura.ne.jp/blog/?p=546">TOWN ver 1.4.1</a></p>
		<p><a href="http://tyage.sakura.ne.jp/blog/?p=455">TOWN ver 1.4.0</a></p>
		<p><a href="http://tyage.sakura.ne.jp/blog/?p=264">TOWN ver 1.3.1</a></p>
		<p><a href="http://tyage.sakura.ne.jp/blog/?p=243">TOWN ver 1.3.0</a></p>
	</section>
</div>

<?= $html->css('town', null, array('inline' => false)); ?>