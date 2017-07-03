<? if ($rest > 0): ?>
	<p>残り<?= $rest ?>秒</p>
<? endif; ?>

<?= $form->create('School', array('action' => 'study')); ?>
<table>
	<thead>
		<tr>
			<th>科目</th>
			<th>金額</th>
			<th>所要時間</th>
			<? foreach ($abilities as $ability): ?>
				<th><?= __($ability); ?></th>
			<? endforeach ?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($schools as $school): ?>
			<tr>
				<td>
					<?= $form->radio(
						'School.id',
						array($school['School']['id'] => $school['School']['name']),
						array(
							'value' => 'DO NOT PUT HIDDEN!',
							'disabled' => (
								$rest > 0 or
								$user['Profile']['money'] < $school['School']['price'] or
								$user['Profile']['energy'] < -$school['School']['energy'] or
								$user['Profile']['spirit'] < -$school['School']['spirit']
							)
						)
					); ?>
				</td>
				<td><?= $school['School']['price']; ?>円</td>
				<td><?= $school['School']['time']; ?></td>
				<? foreach ($abilities as $ability): ?>
					<td><?= $school['School'][$ability]; ?></td>
				<? endforeach ?>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $form->end('受講'); ?>

<?= $paginator->numbers(true); ?>