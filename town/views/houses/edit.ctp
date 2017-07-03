<?= $form->create(); ?>
<?= $form->hidden('House.id'); ?>
<?= $form->input('House.title', array('label' => 'タイトル')); ?>
<?= $exform->imageSelector(
	'House.image',
	$images,
	array('base' => 'house'.DS),
	array('line' => 10)
); ?>
<?= $form->end('編集'); ?>

<? foreach ($rooms as $key => $room): ?>
	<?= $html->link($room['name'].'を追加する', $room['add'].$houseId); ?><br />
<? endforeach; ?>