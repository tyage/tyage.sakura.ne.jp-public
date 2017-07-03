<?= $form->create('Store', array('url' => '/stores/out/'.$store['Store']['id'])); ?>
<table>
	<thead>
		<tr>
			<th>アイテム</th>
			<th>価値</th>
			<th>耐久</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($items as $key => $item): ?>
			<tr>
				<td>
					<?= $form->checkbox(
						'StoreItem.id.'.$key,
						array('value' => $item['StoreItem']['id'])
					); ?>
					<?= $item['Item']['name']; ?>
				</td>
				<td><?= $item['StoreItem']['price'] * ($item['StoreItem']['rest'] / $item['Item']['count']); ?>円</td>
				<td><?= $item['StoreItem']['rest']; ?>回</td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $form->end('引き出す') ?>