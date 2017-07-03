<table id='sends'>
	<thead>
		<tr>
			<th>送信先</th>
			<th>タイトル</th>
			<th>日付</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($sends as $send): ?>
			<?= $mail->send($send); ?>
		<? endforeach; ?>
	</tbody>
</table>

<?= $paginator->numbers(true); ?>