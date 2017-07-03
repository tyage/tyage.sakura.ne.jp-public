<?= $form->create('Topic', array('action' => 'edit')); ?>
<?= $form->input('id', array('type'=>'hidden')); ?>
<?= $form->input('title', array('label' => 'タイトル')); ?>
<?= $form->end('保存');?>