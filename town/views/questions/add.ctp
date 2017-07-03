<?= $form->create(); ?>
<?= $form->input('Question.title', array('label' => 'タイトル')); ?>
<?= $form->input('Question.message', array('label' => 'メッセージ')); ?>
<?= $form->input('Question.いくつ選択可能か', array('label' => 'choice')); ?>
<?= $form->input('Question.いくつ追加可能か', array('label' => 'add')); ?>
<?= $form->end('追加'); ?>