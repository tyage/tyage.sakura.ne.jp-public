<nav>
	<?= $html->link('投稿する', '/posts/add/') ?>
</nav>

<section id='categories'>
	<h2>カテゴリ</h2>
	
	<nav>
		<?= $html->link('全て', '/') ?> / 
		<? foreach ($categories as $category): ?>
			<?= $html->link($category['Category']['name'], 
				'/posts/index/'.$category['Category']['id']) ?> / 
		<? endforeach ?>
	</nav>
</section>

<section id="search">
	<?= $form->create() ?>
	<?= $form->input('Post.word', array('label' => false, 'div' => false, 'size' => 40)) ?>
	<?= $form->submit('検索', array('label' => false, 'div' => false)) ?>
	<?= $form->end() ?>
</section>

<aside>
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-1507795450221855";
	/* エフェクターレビュー */
	google_ad_slot = "4125325540";
	google_ad_width = 728;
	google_ad_height = 90;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
</aside>

<? foreach ($posts as $post): ?>
	<div class="post">
		<header>
			<nav class="action">
				<?= $html->link('編集', 
					'/posts/edit/'.$post['Post']['id']) ?>
				<?= $html->link('削除', 
					'/posts/delete/'.$post['Post']['id']) ?>
			</nav>
			<h3><?= h($post['Post']['name']) ?></h3>
			<p class="username">
				投稿者：<?= h($post['Post']['username']) ?>
			</p>
		</header>
		<div class="content">
			<dl>
				<dt>購入金額</dt>
					<dd><?= h($post['Post']['cost']) ?></dd>
				<dt>好きな音楽のジャンルや影響を受けたギタリストなどなど</dt>
					<dd><pre><?= h($post['Post']['cause']) ?></pre></dd>
				<dt>演奏する楽器・エフェクターの接続など</dt>
					<dd><pre><?= h($post['Post']['instrument']) ?></pre></dd>
				<dt>エフェクターの種類</dt>
					<dd><?= $html->link($post['Category']['name'], 
						'/posts/index/'.$post['Category']['id']
					) ?></dd>
				<dt>総合評価</dt>
					<dd><?= $this->element('star', array(
						'star' => $post['Post']['all']
						)) ?></dd>
				<dt>コストパフォーマンス</dt>
					<dd><?= $this->element('star', array(
						'star' => $post['Post']['all']
						)) ?></dd>
				<dt>音質</dt>
					<dd><?= $this->element('star', array(
						'star' => $post['Post']['quality']
						)) ?></dd>
				<dt>使いやすさ</dt>
					<dd><?= $this->element('star', array(
						'star' => $post['Post']['operability']
						)) ?></dd>
				<dt>おすすめ度</dt>
					<dd><?= $this->element('star', array(
						'star' => $post['Post']['recommend']
						)) ?></dd>
				<dt>エフェクターレビュー</dt>
					<dd><pre><?= h($post['Post']['review']) ?></pre></dd>
			</dl>
		</div>
		<footer>
			<time><?= h($post['Post']['created']) ?></time>
		</footer>
	</div>
<? endforeach ?>

<?= $paginator->numbers(true) ?>