<h1><?= $shop['Shop']['title']; ?></h1>

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
					<td><?= $purchase['ShopItem']['price']; ?>円</td>
					<td><?= $purchase['quantity']; ?>個</td>
					<td><?= $purchase['quantity'] * $purchase['ShopItem']['price']; ?>円</td>
				</tr>
			<? endforeach; ?>
		</tbody>
	</table>
<? endif; ?>

<? $abilities = $this->requestAction('/users/abilities') ?>
<?= $form->create('Shop', array('url' => '/shops/buy/'.$shop['Shop']['id'])); ?>
<p class='error'><?= $form->error('Shop.money'); ?></p>
<table>
	<thead>
		<tr>
			<th><?= $paginator->sort('アイテム名', 'Item.name'); ?></th>
			<th><?= $paginator->sort('金額', 'Item.price'); ?></th>
			<th><?= $paginator->sort('在庫', 'Item.stock'); ?></th>
			<? foreach ($abilities as $ability): ?>
				<th><?= $paginator->sort(__($ability, true), 'Item.'.$ability); ?></th>
			<? endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<? foreach($items as $key => $item): ?>
			<tr>
				<td>
					<?= $item['Item']['name']; ?>
					<?= $form->input(
						'ShopItem.quantity.'.$item['ShopItem']['id'],
						array('label' => false,'after' => '個','size' => 4)
					); ?>
				</td>
				<td><?= $item['ShopItem']['price']; ?>円</td>
				<td><?= $item['ShopItem']['stock']; ?>個</td>
				<? foreach ($abilities as $ability): ?>
					<td><?= $item['Item'][$ability] ?></td>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $form->submit('購入') ?>

<?= $paginator->numbers(true); ?>