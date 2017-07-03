<h1><?= $forum['Forum']['title']; ?></h1>

<table>
	<?= $html->tableHeaders(
		array('タイトル','作成者','更新','作成')
	); ?>
	<tbody>
		<? foreach ($threads as $thread): ?>
			<tr>
			  	<td><?= $html->link(
			  		$thread['Thread']['title'],
			  		'/threads/view/'.$thread['Thread']['id']
			  	); ?></td>
			  	<td>
			  		<?= $html->image('chara'.DS.$thread['User']['image']); ?>
			  		<?= $html->link(
						$thread['User']['username'],
						'/users/view/'.$thread['User']['id'],
						array('target' => '_blank')
					); ?>
				</td>
			  	<td><?= $thread['Thread']['updated']; ?></td>
			  	<td><?= $thread['Thread']['created']; ?></td>
		  	</tr>
		<? endforeach; ?>
	</tbody>
</table>

<?= $paginator->numbers(true); ?>

<?= $form->create('Thread',array('action' => 'add')); ?>
<?= $form->hidden('Forum.id',array('value' => $forum['Forum']['id'])); ?>
<?= $form->input('Thread.title',array('label' => 'タイトル')); ?>
<?= $form->end('作成'); ?>