<table>
	<thead>
		<tr>
			<th>アイテム</th>
			<th>金額</th>
			<th>在庫</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($items as $key => $item): ?>
			<tr>
				<td>
					<?= $html->link(
						 $item['Item']['name'],
						'/shop_items/edit/'.$item['ShopItem']['id']
					); ?>
				</td>
				<td><?= $item['ShopItem']['price']; ?>円</td>
				<td><?= $item['ShopItem']['stock']; ?>個</td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>