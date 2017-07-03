<?= $form->create() ?>
<?= $form->input('Post.id', array('type' => 'text', 'label' => 'ID')) ?>
<?= $form->input('Post.title', array('label' => 'タイトル')) ?>
<?= $form->input('Post.username', array('label' => '名前')) ?>
<?= $form->input('Post.body', array('label' => '本文')) ?>
<?= $form->input('Post.password', array('label' => 'パスワード')) ?>
<?= $form->end('編集') ?>