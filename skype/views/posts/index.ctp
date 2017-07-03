<? $i = 0 ?>
<? foreach ($posts as $post): ?>
	<? if ($i++ === 5): ?>
		<div class="ad">
			<script type="text/javascript">var kauli_yad_count = typeof(kauli_yad_count) == 'undefined' ? 1 : kauli_yad_count + 1;(function(d){ d.write('<span id="kauli_yad_' + kauli_yad_count + '" style="width:728px; height:90px; display:inline-block"><!--14579--><' + '/span>'); var s = d.createElement('script'); var h = d.getElementsByTagName('head')[0]; s.defer = 'defer'; s.async = 'async'; s.src = 'http://js.kau.li/yad.js'; h.insertBefore(s, h.firstChild);})(document);</script>
		</div>
	<? endif ?>
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
		
		<div class='comments'>
			<? foreach ($post['Comment'] as $comment): ?>
				<div class="post comment">
					<header>
						<p class='username'><?= h($comment['username']) ?></p>
						<div class="detail">
							<span class="id">[<?= $comment['id'] ?>]</span>
							<time class='time'><?= $comment['updated'] ?></time>
						</div>
					</header>
					<pre class="content"><?= h($comment['body']) ?></pre>
					<footer></footer>
				</div>
			<? endforeach ?>
		</div>
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