<table>
	<thead>
		<tr>
			<th rowspan='2'>
				<?= $paginator->sort('アイテム', 'Item.name'); ?>
			</th>
			<th><?= $paginator->sort('カテゴリ', 'Item.category'); ?></th>
			<th><?= $paginator->sort('種類', 'Item.type'); ?></th>
			<th><?= $paginator->sort('価格', 'Item.price'); ?></th>
			<th><?= $paginator->sort('耐久回数', 'Item.count'); ?></th>
			<th><?= $paginator->sort('耐久日数', 'Item.limit'); ?></th>
			<th><?= $paginator->sort('使用間隔', 'Item.span'); ?></th>
			<th colspan='5'><?= $paginator->sort('概要', 'Item.explain'); ?></th>
			<th><?= $paginator->sort('身長', 'Item.height'); ?></th>
			<th><?= $paginator->sort('体重', 'Item.weight'); ?></th>
			<th><?= $paginator->sort('特殊効果', 'Item.special'); ?></th>
		</tr>
		<tr>
			<? foreach ($abilities as $ability): ?>
				<th><?= $paginator->sort(__($ability, true), 'Item.'.$ability); ?></th>
			<? endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($items as $item): ?>
			<tr>
				<td rowspan='2'>
					<a href="<?= (
						$item['Item']['asin'] ? 
						'http://www.amazon.co.jp/dp/'.$item['Item']['asin'] 
						: 'http://www.amazon.co.jp/s/ref=nb_sb_noss?__mk_ja_JP=%83J%83%5E%83J%83i&url=search-alias%3Daps&field-keywords='.urlencode(mb_convert_encoding($item['Item']['name'], 'SJIS', "UTF-8")).'&x=18&y=19'
					); ?>" target="_blank">
						<?= $html->image($item['Item']['image']); ?>
					</a>
					<?= $item['Item']['name']; ?>
				</td>
				<td><?= $item['Item']['category']; ?></td>
				<td><?= $item['Item']['type']; ?></td>
				<td><?= $item['Item']['price']; ?>円</td>
				<td><?= $item['Item']['count']; ?>回</td>
				<td><?= $item['Item']['limit']; ?>日</td>
				<td><?= $item['Item']['span']; ?>分</td>
				<td colspan='5'><?= $item['Item']['explain']; ?></td>
				<td><?= $item['Item']['height']; ?>cm</td>
				<td><?= $item['Item']['weight']; ?>kg</td>
				<td><?= $item['Item']['special']; ?></td>
			</tr>
			<tr>
				<? foreach ($abilities as $ability): ?>
					<td><?= $item['Item'][$ability]; ?></td>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>

<?= $paginator->numbers(true); ?>