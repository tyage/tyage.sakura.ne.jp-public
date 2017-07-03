<?= $form->create('Shop', array('url' => '/shops/add/'.$houseId)); ?>
<?= $form->input('title', array('label' => 'タイトル')); ?>
<?= $form->end('作成'); ?>