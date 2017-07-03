<? $receives = $sends = array(); ?>
<? foreach ($mails['receives'] as $receive): ?>
	<? $receives[] = $mail->receive($receive); ?>
<? endforeach; ?>
<? foreach ($mails['sends'] as $send): ?>
	<? $sends[] = $mail->send($send); ?>
<? endforeach; ?>

<?= $js->object(
	array(
		'receives' => $receives,
		'sends' => $sends,
		'lastId' => $lastId
	)
); ?>