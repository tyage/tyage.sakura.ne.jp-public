<?= $form->create('Chat', array('action' => 'add')); ?>
<?= $form->input('body', array('label' => false,'div' => false)); ?>
<?= $form->submit('投稿', array('div' => false)); ?>
<?= $form->end(); ?>

<?= $html->link('チャット更新', '/chats/', array('id' => 'reloadChat')); ?>

<dl id='chatMessage'>
	<? foreach ($messages as $message): ?>
		<?= $chat->message($message); ?>
	<? endforeach; ?>
</dl>

<?= $html->script('chat'); ?>
<?= $html->scriptBlock('$chat.lastId='.$lastId.';'); ?>