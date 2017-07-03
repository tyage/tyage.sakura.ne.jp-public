<h1><?= $blog['Blog']['title']; ?></h1>

<?= $html->link('投稿する', '/articles/add/'.$blog['Blog']['id']); ?>

<? foreach ($articles as $article): ?>
	<div class='article'>
		<h2><?= $html->link(
			$article['Article']['title'],
			'/articles/view/'.$article['Article']['id']
		); ?></h2>
		<div class='info'>
			<p><?= $article['Article']['created']; ?></p>
		</div>
		<pre class='body'><?= $article['Article']['body']; ?></pre>
	</div>
<? endforeach; ?>

<?= $paginator->numbers(true); ?>