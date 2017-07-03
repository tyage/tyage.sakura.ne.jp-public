<table>
	<tr>
		<th>内容</th>
		<th>作成日時</th>
	</tr>
	<? foreach ($news as $new) : ?>
		<tr>
			<td><?= $new['News']['body']; ?></td>
			<td><?= $new['News']['created'] ?></td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>