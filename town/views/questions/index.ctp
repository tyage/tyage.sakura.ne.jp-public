<?= $html->link('追加する', '/questions/add/'); ?>

<table>
	<thead>
		<tr>
			<td>アンケート</td>
			<td>作成者</td>
			<td>作成日時</td>
			<td>最終更新日時</td>
			<td>アクション</td>
		</tr>
	</thead>
	<tbody>
		<? foreach($questions as $question): ?>
			<tr>
				<td><?= $html->link($question['Question']['title'], '/questions/view/'.$question['Question']['id']); ?></td>
				<td>
					<?= $html->image('chara'.DS.$question['User']['image']); ?>
					<?= $html->link(
						$question['User']['username'],
						'/users/view/'.$question['User']['id'],
						array('target' => '_blank')
					); ?>
				</td>
				<td><?= $question['Question']['created']; ?></td>
				<td><?= $question['Question']['updated']; ?></td>
				<td>
					<? if ($question['User']['id'] == $self['User']['id']): ?>
						<?= $html->link('編集', '/questions/edit/'.$question['Question']['id']); ?>
						<?= $html->link('削除', '/questions/delete/'.$question['Question']['id']); ?>
					<? endif ?>
				</td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>