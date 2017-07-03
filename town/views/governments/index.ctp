<p><?= $html->link('ユーザーランキング', '/users/rank/'); ?></p>
<p><?= $html->link('アイテム一覧', '/items/'); ?></p>

<table>
	<thead>
		<tr>
			<th>内容</th>
			<th>日時</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($news as $new): ?>
			<tr>
				<td><?= $new['News']['body']; ?></td>
				<td><?= $new['News']['created']; ?></td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $html->link('最近のニュース', '/news/index/') ?>