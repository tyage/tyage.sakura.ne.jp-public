<? if (!empty($purchases)): ?>
	<h2>購入しました</h2>
	<table>
		<thead>
			<tr>
				<th>アイテム名</th>
				<th>金額</th>
				<th>個数</th>
				<th>支払額</th>
			</tr>
		</thead>
		<tbody>
			<? foreach($purchases as $purchase): ?>
				<tr>
					<td><?= $purchase['Item']['name']; ?></td>
					<td><?= $purchase['BuyerItem']['price']; ?>円</td>
					<td><?= $purchase['quantity']; ?>個</td>
					<td><?= $purchase['quantity'] * $purchase['BuyerItem']['price']; ?>円</td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
<? endif; ?>

<?= $form->create('Buyer', array('url' => '/buyers/buy/'.$buyerId.DS.$shopId)); ?>
<p class='error'><?= $form->error('Buyer.money'); ?></p>
<table>
	<thead>
		<tr>
			<th>アイテム名</th>
			<th>金額</th>
			<th>在庫</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($items as $key => $item): ?>
			<tr>
				<td>
					<?= $item['Item']['name']; ?>
					<?= $form->input(
						'BuyerItem.quantity.'.$item['BuyerItem']['id'],
						array('label' => false,'after' => '個','size' => 4)
					); ?>
				</td>
				<td><?= $item['BuyerItem']['price']; ?>円</td>
				<td><?= $item['BuyerItem']['stock']; ?>個</td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $form->submit('購入') ?>