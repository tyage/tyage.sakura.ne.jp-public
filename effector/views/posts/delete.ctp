<?= $form->create('Post', array('url' => 
	array('controller' => 'posts', 'action' => 'delete', $id)
)) ?>
<?= $form->input('Post.password', array('label' => 'パスワード')) ?>
<?= $form->end('投稿') ?>