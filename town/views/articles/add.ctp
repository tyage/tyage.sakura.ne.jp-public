<?= $form->create('Article', array('url' => '/articles/add/'.$blog['Blog']['id'])); ?>
<?= $form->input('Article.title'); ?>
<?= $form->input('Article.body'); ?>
<?= $form->end('作成'); ?>