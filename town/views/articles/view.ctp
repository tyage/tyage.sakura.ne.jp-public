<h1><?= $article['Article']['title']; ?></h1>

<pre><?= $article['Article']['body']; ?></pre>

<dl>
	<? foreach ($comments as $comment): ?>
		<dt>
			<?= $html->image('chara'.DS.$comment['User']['image']); ?>
			<?= $html->link(
				$comment['User']['username'],
				'/users/view/'.$comment['User']['id'],
				array('target' => '_blank')
			); ?>
		</dt>
		<dd>
			<pre><?= $comment['Comment']['body']; ?></pre>
		</dd>
	<? endforeach; ?>
</dl>

<?= $form->create('Comment', array('action' => 'add')); ?>
<?= $form->hidden('Comment.article_id', array('value' => $article['Article']['id'])); ?>
<?= $form->input('Comment.body', array('label' => '内容')); ?>
<?= $form->end('コメント'); ?>