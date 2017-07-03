<?= $html->link('追加','/admin/news/add') ?>

<table>
	<tr>
		<th>ID</th>
		<th>内容</th>
		<th>作成日時</th>
		<th>更新日時</th>
		<th></th>
	</tr>
	<? foreach ($news as $new): ?>
		<tr>
			<td>
				<?= $new['News']['id']; ?>
			</td>
			<td><?= $new['News']['body']; ?></td>
			<td><?= $new['News']['created']; ?></td>
			<td><?= $new['News']['updated']; ?></td>
			<td class='actions'>
				<?= $html->link(
					'編集',
					array('action' => 'edit', $new['News']['id'])
				); ?>
				<?= $html->link(
					'削除',
					array('action' => 'delete', $new['News']['id'])
				); ?>
			</td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>