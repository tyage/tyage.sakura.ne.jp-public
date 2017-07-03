<?= $form->create('Topic',array('action' => 'add','class' => 'justify')); ?>
<?= $form->input('title',array('label' => 'タイトル')); ?>
<?= $form->end('追加'); ?>