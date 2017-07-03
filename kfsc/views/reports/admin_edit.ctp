<?= $form->create('Report', array('action' => 'edit')); ?>
<?= $form->input('id', array('type'=>'hidden')); ?>
<?= $form->input('title', array('label' => 'タイトル')); ?>
<?= $form->input('body', array('label' => '内容')); ?>
<?= $form->end('保存');?>