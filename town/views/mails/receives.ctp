<table id='receives'>
	<thead>
		<tr>
			<th>送信主</th>
			<th>タイトル</th>
			<th>日付</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($receives as $receive): ?>
			<?= $mail->receive($receive); ?>
		<? endforeach; ?>
	</tbody>
</table>

<?= $paginator->numbers(true); ?>