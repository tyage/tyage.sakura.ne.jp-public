<table>
	<thead>
		<tr>
			<th>内容</th>
			<th>入出金</th>
			<th>残高</th>
			<th>日付</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($logs as $log): ?>
			<tr>
				<td><?= $log['Bank']['work']; ?></td>
				<td><?= $log['Bank']['amount']; ?>円</td>
				<td><?= $log['Bank']['rest']; ?>円</td>
				<td><?= $log['Bank']['created']; ?></td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>

<?= $paginator->numbers(true); ?>