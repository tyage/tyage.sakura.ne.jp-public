<table>
	<thead>
		<tr>
			<th><?= $paginator->sort('ユーザー', 'User.username'); ?></th>
			<th><?= $paginator->sort('登録日', 'User.created'); ?></th>
			<th><?= $paginator->sort('持ち金', 'Profile.money'); ?></th>
			<th><?= $paginator->sort('銀行資産', 'Profile.bank'); ?></th>
			<th><?= $paginator->sort('コイン', 'Profile.coin'); ?></th>
			<th><?= $paginator->sort('身長', 'Profile.height'); ?></th>
			<th><?= $paginator->sort('体重', 'Profile.weihght'); ?></th>
			<th><?= $paginator->sort('性別', 'Profile.sex'); ?></th>
			<? foreach ($abilities as $ability): ?>
				<th><?= $paginator->sort(__($ability, true), 'Profile.'.$ability); ?></th>
			<? endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($users as $user): ?>
			<tr>
				<td>
					<?= $html->image('chara'.DS.$user['User']['image']); ?>
					<?= $html->link(
						$user['User']['username'],
						'/users/view/'.$user['User']['id'],
						array('target' => '_blank')
					); ?>
				</td>
				<td><?= $user['User']['created']; ?></td>
				<td><?= $user['Profile']['money']; ?>円</td>
				<td><?= $user['Profile']['bank']; ?>円</td>
				<td><?= $user['Profile']['coin']; ?>枚</td>
				<td><?= $user['Profile']['height']; ?>cm</td>
				<td><?= $user['Profile']['weight']; ?>kg</td>
				<td><?= $user['Profile']['sex']; ?></td>
				<? foreach ($abilities as $ability): ?>
					<td><?= $user['Profile'][$ability] ?></td>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>