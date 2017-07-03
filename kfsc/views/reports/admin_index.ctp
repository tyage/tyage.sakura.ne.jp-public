<?= $html->link('追加','/admin/reports/add') ?>

<table>
	<tr>
		<th>ID</th>
		<th>タイトル</th>
		<th>内容</th>
		<th>作成日時</th>
		<th>更新日時</th>
		<th></th>
	</tr>
	<? foreach ($reports as $report): ?>
		<tr>
			<td>
				<?= $report['Report']['id']; ?>
			</td>
			<td><?= $report['Report']['title']; ?></td>
			<td>
				<pre><?= $report['Report']['body']; ?></pre>
			</td>
			<td><?= $report['Report']['created']; ?></td>
			<td><?= $report['Report']['updated']; ?></td>
			<td class='actions'>
				<?= $html->link(
					'編集',
					array('action' => 'edit',$report['Report']['id'])
				); ?>
				<?= $html->link(
					'削除',
					array('action' => 'delete',$report['Report']['id'])
				); ?>
			</td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>