<table>
	<tr>
		<th>ID</th>
		<th>ユーザー名</th>
		<th>IP</th>
		<th>日時</th>
	</tr>
	<? foreach ($accesses as $access) : ?>
		<tr>
			<td><?= $access['Access']['id']; ?></td>
			<td><?= $access['User']['username']; ?></td>
			<td><?= $access['Access']['ip']; ?></td>
			<td><?= $access['Access']['created']; ?></td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>