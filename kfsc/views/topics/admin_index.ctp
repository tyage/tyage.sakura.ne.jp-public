<?= $html->link('追加','/admin/topics/add') ?>

<table>
	<tr>
		<th>ID</th>
		<th>議題</th>
		<th>作成日時</th>
		<th>更新日時</th>
		<th></th>
	</tr>
	<? foreach ($topics as $topic): ?>
		<tr>
			<td>
				<label>
					<?= $topic['Topic']['id']; ?>
				</label>
			</td>
			<td>
				<?= $html->link(
					$topic['Topic']['title'],
					'/topics/view/'.$topic['Topic']['id']
				); ?>
			</td>
			<td><?= $topic['Topic']['created']; ?></td>
			<td><?= $topic['Topic']['updated']; ?></td>
			<td class='actions'>
				<?= $html->link(
					'編集',
					array('action' => 'edit',$topic['Topic']['id'])
				); ?>
				<?= $html->link(
					'削除',
					array('action' => 'delete',$topic['Topic']['id'])
				); ?>
			</td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>