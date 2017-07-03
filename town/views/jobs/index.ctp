<?= $form->create('Job', array('action' => 'get')); ?>
<table>
	<thead>
		<tr>
			<th><?= $paginator->sort('仕事', 'Job.name'); ?></th>
			<th><?= $paginator->sort('初給', 'Job.salary'); ?></th>
			<th><?= $paginator->sort('給料制度', 'Salary.name'); ?></th>
			<th><?= $paginator->sort('ボーナス', 'Job.bonus'); ?></th>
			<th><?= $paginator->sort('仕事場', 'Office.name'); ?></th>
			<th><?= $paginator->sort('最小BMI', 'Job.bmi_min'); ?></th>
			<th><?= $paginator->sort('最大BMI', 'Job.bmi_max'); ?></th>
			<th><?= $paginator->sort('性別', 'Job.max'); ?></th>
			<? foreach($abilities as $ability): ?>
				<th><?= $paginator->sort(__($ability, true), 'Job.'.$ability); ?></th>
			<? endforeach; ?>
		</tr>
	</thead>
	<tbody>
		<? foreach ($jobs as $job): ?>
			<tr>
				<td>
					<?= $form->radio(
						'Job.id',
						array($job['Job']['id'] => $job['Job']['name'] ),
						array('value' => 'Do Not Put Hidden!')
					); ?>
				</td>
				<td><?= $job['Job']['salary']; ?>円</td>
				<td><?= $job['Salary']['name']; ?></td>
				<td><?= $job['Job']['bonus']; ?>倍</td>
				<td><?= $job['Office']['name']; ?></td>
				<td><?= $job['Job']['bmi_min'] > 0 ? $job['Job']['bmi_min'] : ''; ?></td>
				<td><?= $job['Job']['bmi_max'] > 0 ? $job['Job']['bmi_max'] : ''; ?></td>
				<td><?= $job['Job']['sex']; ?></td>
				<? foreach($abilities as $ability): ?>
					<td><?= $job['Job'][$ability]; ?></td>
				<? endforeach; ?>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $paginator->numbers(true); ?>

<?= $form->submit('就職'); ?>