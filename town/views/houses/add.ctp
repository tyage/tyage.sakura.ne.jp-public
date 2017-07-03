<?= $form->create(); ?>

<section>
	<h2>街を選択してください</h2>
	<?= $form->input('House.town_id', array('options' => $towns,'label' => '街')); ?>
	<?= $this->element('map', array('location' => $town['Town']['id'])); ?>
</section>

<section>
	<h2>場所を選択してください</h2>
	<?= $form->input('House.x'); ?>
	<?= $form->input('House.y'); ?>
	<div id='towns'>
		<?= $this->element('town',array('town' => $town)); ?>
	</div>
</section>

<section>
	<h2>画像を選択してください</h2>
	<?= $exform->imageSelector(
		'House.image',
		$images,
		array('base' => 'house'.DS),
		array('line' => 10)
	); ?>
</section>

<?= $form->end('建設'); ?>

<?= $html->script('builder', true); ?>
<?= $html->css('town', null, array('inline' => false)); ?>