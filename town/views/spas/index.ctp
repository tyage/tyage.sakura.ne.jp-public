<table>
	<thead>
		<tr>
			<th>温泉</th>
			<th>入浴料</th>
			<th>体力回復速度</th>
			<th>精神力回復速度</th>
			<th>体力全快まで</th>
			<th>精神力全快まで</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($spas as $spa): ?>
			<tr>
				<td>
					<?= $html->link(
						$spa['Spa']['name'],
						'/spas/bath/'.$spa['Spa']['id']
					); ?>
				</td>
				<td><?= $spa['Spa']['cost']; ?></td>
				<td><?= $spa['Spa']['energy'] ?>/秒</td>
				<td><?= $spa['Spa']['spirit'] ?>/秒</td>
				<td><?= ($profile['Profile']['maxEnergy'] - $profile['Profile']['energy']) / $spa['Spa']['energy'] ?>秒</td>
				<td><?= ($profile['Profile']['maxSpirit'] - $profile['Profile']['spirit']) / $spa['Spa']['spirit'] ?>秒</td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>