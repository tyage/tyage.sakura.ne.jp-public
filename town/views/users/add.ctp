<?= $form->create(); ?>
<?= $form->error('Ip.0.ip'); ?>
<?= $form->input('User.username',array('label' => '名前')); ?>
<?= $form->input('User.password',array('label' => 'パスワード')); ?>
<?= $form->input('Profile.sex',array('label' => '性別','options' => array('男','女'))); ?>
<div>
	<?= $form->label('Profile.birthday','誕生日'); ?>
	<?= $form->year('Profile.born',date('Y') - 70,date('Y'),NULL,array('empty' => false)); ?>年
	<?= $form->month('Profile.born',NULL,array('monthNames' => false,'empty' => false)); ?>月
	<?= $form->day('Profile.born',NULL,array('empty' => false)); ?>日
</div>
<div>
	<?= $form->label('User.image','画像'); ?>
	<?= $exform->imageSelector(
		'User.image',
		$images,
		array('base' => 'chara'.DS),
		array('value' => '1.gif','legend' => '　','line' => 10)
	); ?>
</div>
<?= $form->end('登録'); ?>