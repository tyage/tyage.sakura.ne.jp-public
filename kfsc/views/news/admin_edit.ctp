<?= $form->create('News', array('action' => 'edit')); ?>
<?= $form->input('id', array('type'=>'hidden')); ?>
<?= $form->input('body', array('label' => '内容')); ?>
<?= $form->end('保存');?>