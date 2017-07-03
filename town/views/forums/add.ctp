<?= $form->create('Forum', array('url' => '/forums/add/'.$houseId)); ?>
<?= $form->input('title', array('label' => 'タイトル')); ?>
<?= $form->end('作成'); ?>