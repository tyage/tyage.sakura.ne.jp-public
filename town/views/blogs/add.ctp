<?= $form->create('Blog', array('url' => '/blogs/add/'.$houseId)); ?>
<?= $form->input('title', array('label' => 'タイトル')); ?>
<?= $form->end('作成'); ?>