<?= $form->create('Answer', array('action' => 'add')); ?>
<?= $form->hidden('Question.id', array('value' => $question['Question']['id'])); ?>
<?= $form->input('Answer.body', array('label' => '回答')); ?>
<?= $form->end('追加'); ?>

<?= $form->create('Question', array('action' => 'vote')); ?>
<?= $form->hidden('Question.id', array('value' => $question['Question']['id'])); ?>
<table>
	<thead>
		<tr>
			<td>回答</td>
			<td>投票数</td>
			<td>作成者</td>
			<td>作成日時</td>
		</tr>
	</thead>
	<tbody>
		<? foreach($answers as $key => $answer): ?>
			<tr>
				<td>
					<?= $form->checkbox(
						'Answer.id.'.$key,
						array(
							'value' => $answer['Answer']['id'],
							'checked' => in_array($answer['Answer']['id'],$choices)
						)
					); ?>
					<?= h($answer['Answer']['body']); ?>
				</td>
				<td><?= count($answer['Vote']); ?></td>
				<td>
					<?= $html->image('chara'.DS.$answer['User']['image']); ?>
					<?= $html->link(
						$answer['User']['username'],
						'/users/view/'.$answer['User']['id'],
						array('target' => '_blank')
					); ?>
				</td>
				<td><?= $answer['Answer']['created']; ?></td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $form->end('投票'); ?>