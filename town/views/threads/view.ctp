<nav id='threads'>
	<h3><?= $html->link($thread['Forum']['title'], '/forums/view/'.$thread['Forum']['id']) ?>のスレッド</h3>
	<? foreach ($threads as $t): ?>
		<?= $html->link($t['Thread']['title'], '/threads/view/'.$t['Thread']['id']) ?>
	<? endforeach; ?>
</nav>

<h2><?= h($thread['Thread']['title']); ?></h2>

<div id='responses'>
	<? foreach ($responses as $response): ?>
		<div class='response'>
			<div class='info'>
				<p class='user'>
					<?= $html->image('chara'.DS.$response['User']['image']); ?>
					<?= $html->link(
						$response['User']['username'],
						'/users/view/'.$response['User']['id'],
						array('target' => '_blank')
					); ?>
				</p>
				<time class='created'><?= $response['Response']['created']; ?></time>
			</div>
			<div class='body'>
				<pre><?= h($response['Response']['body']); ?></pre>
			</div>
		</div>
	<? endforeach; ?>
</div>

<?= $paginator->numbers($paginatorOption); ?>

<?= $form->create('Response',array('action' => 'add')); ?>
<?= $form->hidden('Thread.id',array('value' => $thread['Thread']['id'])); ?>
<?= $form->input('Response.body',array('label' => '内容')); ?>
<?= $form->end('追加'); ?>

<?= $html->css('response', null, array('inline' => false)); ?>