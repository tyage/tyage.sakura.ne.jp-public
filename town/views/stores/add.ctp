<?= $form->create('Store', array('url' => '/stores/add/'.$houseId)); ?>
<?= $form->input('title', array('label' => 'タイトル')); ?>
<?= $form->end('作成'); ?>