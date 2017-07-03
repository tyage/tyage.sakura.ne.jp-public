<?= $form->create(); ?>
<?= $form->input('User.id', array('type'=>'hidden')); ?>
<?= $form->input('User.username', array('label' => '名前')); ?>
<?= $form->input('User.password', array('label' => 'パスワード')); ?>
<?= $form->input('User.profile', array('label' => 'プロフィール')); ?>
<?= $form->input('User.role', 
	array(
		'label' => '権限',
		'options' => array('school' => '学校','admin' => '管理人')
	)
); ?>
<?= $form->input('User.post_key', array('label' => '投稿キー')); ?>
<?= $form->end('保存');?>