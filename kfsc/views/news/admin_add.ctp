<?= $form->create('News',array('action' => 'add','class' => 'justify')); ?>
<?= $form->input('body',array('label' => '内容')); ?>
<?= $form->end('追加'); ?>