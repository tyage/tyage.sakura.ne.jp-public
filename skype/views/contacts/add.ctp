<?= $form->create() ?>
<?= $form->input('Contact.post_id', array('label' => '投稿のID', 'type' => 'text')) ?>
<?= $form->input('Contact.body', array('label' => '本文', 'rows' => 6)) ?>
<?= $form->end('送信') ?>