<? $data = array(
	'lastId' => $lastId,
	'messages' => array()
); ?>
<? foreach ($messages as $message): ?>
	<? $data['messages'][] = $chat->message($message) ?>
<? endforeach; ?>
<?= $js->object($data); ?>