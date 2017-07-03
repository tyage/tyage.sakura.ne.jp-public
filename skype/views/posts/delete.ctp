<?= $form->create() ?>
<?= $form->input('Post.id', array('type' => 'text', 'label' => 'ID')) ?>
<?= $form->input('Post.password', array('label' => 'パスワード')) ?>
<?= $form->end('削除') ?>