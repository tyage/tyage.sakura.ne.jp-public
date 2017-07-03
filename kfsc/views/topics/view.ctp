<?php
$addAble = $topic['Topic']['created'] > $user['User']['created'];
?>

<h2><?= $html->link($topic['Topic']['title'],'/topics/view/'.$topic['Topic']['id']); ?></h2>

<dl>
<? foreach ($schools as $school): ?>
	<? $opinion = array_shift($opinions[$school['user_id']]); ?>
	<dt class='comment'>
		<?= $comment->last(
			$opinion,
			$opinion['User']['id'] == $user['User']['id'],
			!empty($opinions[$school['user_id']])
		); ?>
	</dt>

	<? foreach ($opinions[$school['user_id']] as $opinion) : ?>
		<dd class='comment'>
			<?= $comment->past($opinion); ?>
		</dd>
	<? endforeach; ?>

	<? if ($addAble and $opinion['User']['id'] == $user['User']['id']): ?>
		<? $addAble = false; ?>
		<dd id='addOpinion'>
			<?= $form->create('Opinion',array('action' => 'add')); ?>
			<fieldset>
				<legend>追加</legend>
				<?= $form->input('body',array('label' => 'コメント')); ?>
				<?= $form->input('key',array('label' => '投稿キー')); ?>
				<?= $form->hidden('Topic.id',array('value' => $topic['Topic']['id'])); ?>
				<?= $form->submit('追加'); ?>
			</fieldset>
			<?= $form->end(); ?>
		</dd>
	<? endif; ?>

<? endforeach; ?>
</dl>

<? if ($addAble): ?>
<?= $form->create('Opinion',array('action' => 'add')); ?>
<fieldset>
	<legend>追加</legend>
	<?= $form->input('body',array('label' => 'コメント')); ?>
	<?= $form->input('key',array('label' => '投稿キー')); ?>
	<?= $form->hidden('Topic.id',array('value' => $topic['Topic']['id'])); ?>
	<?= $form->submit('追加'); ?>
</fieldset>
<?= $form->end(); ?>
<? endif; ?>

<?= $javascript->link('opinion',false); ?>