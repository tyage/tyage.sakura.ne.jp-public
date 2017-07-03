<?= $form->create('Mail', array('action' => 'add')); ?>
<?= $form->input('Mail.to', array('label' => '宛先')); ?>
<?= $form->input('Mail.title', array('label' => 'タイトル')); ?>
<?= $form->input('Mail.body', array('label' => '本文')); ?>
<?= $form->end('送信'); ?>

<a href='#' id='reloadMail'>メール更新</a>

<section>
	<h1><?= $html->link('受信一覧','/mails/receives/'); ?></h1>
	<table id='receives'>
		<thead>
			<tr>
				<th>送信主</th>
				<th>タイトル</th>
				<th>日付</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($mails['receives'] as $receive): ?>
				<?= $mail->receive($receive); ?>
			<? endforeach; ?>
		</tbody>
	</table>
</section>

<section>
	<h1><?= $html->link('送信一覧','/mails/sends/'); ?></h1>
	<table id='sends'>
		<thead>
			<tr>
				<th>送信先</th>
				<th>タイトル</th>
				<th>日付</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($mails['sends'] as $send): ?>
				<?= $mail->send($send); ?>
			<? endforeach; ?>
		</tbody>
	</table>
</section>

<?= $html->script('mail'); ?>
<?= $html->scriptBlock('$mail.lastId='.$lastId.';'); ?>