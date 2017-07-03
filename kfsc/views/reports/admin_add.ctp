<?= $form->create('Report',array('action' => 'add','class' => 'justify')); ?>
<?= $form->input('Report.title',array('label' => 'タイトル')); ?>
<?= $form->input('Report.body',array('label' => '内容')); ?>
<?= $form->input('News.body',array('label' => "What's new?")); ?>
<?= $form->end('追加'); ?>