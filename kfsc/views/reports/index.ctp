<table>
	<tr>
		<th>タイトル</th>
		<th>更新日時</th>
		<th>作成日時</th>
	</tr>
	<? foreach ($reports as $report) : ?>
		<tr>
			<td><?= $html->link(
				$report['Report']['title'],
				'/reports/view/'.$report['Report']['id']
			); ?></td>
			<td><?= $report['Report']['updated']; ?></td>
			<td><?= $report['Report']['created']; ?></td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>