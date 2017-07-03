<?= $form->create('Store', array('url' => '/stores/in/'.$store['Store']['id'])); ?>
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
						'UserItem.id.'.$key,
						array('value' => $item['UserItem']['id'])
					); ?>
					<?= $item['Item']['name']; ?>
				</td>
				<td><?= $item['UserItem']['price'] * ($item['UserItem']['rest'] / $item['Item']['count']); ?>円</td>
				<td><?= $item['UserItem']['rest']; ?>回</td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $form->end('預ける') ?>