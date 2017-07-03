<table>
	<thead>
		<tr>
			<th>アイテム</th>
			<th>金額</th>
			<th>残り</th>
			<? foreach ($abilities as $ability): ?>
				<th><? __($ability) ?></th>
			<? endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<? foreach($items as $key => $item): ?>
			<tr>
				<td>
					<?= $html->link('使', '/user_items/consume/'.$item['UserItem']['id']); ?>
					<?= $html->link('売', '/user_items/sell/'.$item['UserItem']['id']); ?>
					<?= $item['Item']['name']; ?>
				</td>
				<td><?= $item['UserItem']['price'] * ($item['UserItem']['rest'] / $item['Item']['count']); ?>円</td>
				<td><?= $item['UserItem']['rest']; ?>回</td>
				<? foreach ($abilities as $ability): ?>
					<td><?= $item['Item'][$ability]; ?></td>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>