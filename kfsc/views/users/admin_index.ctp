<?= $html->link('追加','/admin/users/add') ?>

<table>
	<tr>
		<th>ID</th>
		<th>名前</th>
		<th>パスワード</th>
		<th>プロフィール</th>
		<th>投稿キー</th>
		<th>権限</th>
		<th></th>
	</tr>
	<? foreach ($users as $user): ?>
		<tr>
			<td>
				<label><?= $user['User']['id']; ?></label>
			</td>
			<td><?= $user['User']['username']; ?></td>
			<td><?= $user['User']['password']; ?></td>
			<td><?= $user['User']['profile']; ?></td>
			<td><?= $user['User']['post_key']; ?></td>
			<td><?= $user['User']['role']; ?></td>
			<td class='actions'>
				<?= $html->link(
					'編集',
					array('action' => 'edit',$user['User']['id'])
				); ?>
				<?= $html->link(
					'削除',
					array('action' => 'delete',$user['User']['id'])
				); ?>
			</td>
		</tr>
	<? endforeach; ?>
</table>

<?= $paginator->numbers(true); ?>