<table>
	<tr>
		<th>議題</th>
		<th>作成日時</th>
		<th>更新日時</th>
	</tr>
	<? foreach ($topics as $topic) : ?>
		<tr>
			<td><?= $html->link($topic['Topic']['title'],'/topics/view/'.$topic['Topic']['id']); ?></td>
			<td><?= $topic['Topic']['created']; ?></td>
			<td><?= $topic['Topic']['updated']; ?></td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>