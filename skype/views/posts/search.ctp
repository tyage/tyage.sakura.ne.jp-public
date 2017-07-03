<?= $form->create() ?>
<?= $form->input('Post.word', array('type' => 'text', 'label' => 'キーワード')) ?>
<?= $form->end('検索') ?>

<? if (!empty($posts)): ?>
	<? foreach ($posts as $post): ?>
		<div class="post">
			<header>
				<h2 class="title"><?= h($post['Post']['title']) ?></h2>
				<p class='username'><?= h($post['Post']['username']) ?></p>
				<div class="detail">
					<span class="id">[<?= $post['Post']['id'] ?>]</span>
					<time class='time'><?= $post['Post']['updated'] ?></time>
				</div>
			</header>
			<pre class="content"><?= h($post['Post']['body']) ?></pre>
			<footer>
				<nav>
					<?= $html->link('返信', '/add/'.$post['Post']['id']) ?>
				</nav>
			</footer>
		</div>
	<? endforeach ?>
	
	<div class="page">
		<span class='prev'>
			<?= $paginator->prev('前の10件へ') ?>
		</span>
		| 
		<?= $paginator->numbers() ?>
		| 
		<span class='next'>
			<?= $paginator->next('次の10件へ') ?>
		</span>
	</div>
<? endif ?>
