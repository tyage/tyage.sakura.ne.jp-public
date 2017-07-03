<?= $form->create('User', array('action' => 'edit','class' => 'ajax')); ?>
<?= $form->input('User.username', array('label' => 'ユーザー名')); ?>
<?= $form->input('Profile.email', array('label' => 'メールアドレス')); ?>
<div>
	<?= $form->label('User.image','画像'); ?>
	<?= $exform->imageSelector(
		'User.image',
		$images,
		array('base' => 'chara'.DS),
		array('legend' => '　','line' => 10)
	); ?>
</div>
<?= $form->end('編集'); ?>

<?= $html->link('パスワードの編集', '/users/editPassword'); ?>