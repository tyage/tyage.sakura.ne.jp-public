<?= $form->create('User', array('action' => 'editPassword')); ?>
<?= $form->input('pre_password', array('label' => '現在のパスワード')); ?>
<?= $form->input('password', array('label' => '新しいパスワード')); ?>
<?= $form->end('編集'); ?>