<h2>関西生徒会連盟 公式ウェブサイト</h2>

<section>
	<p>関西生徒会連盟公式ウェブサイトへようこそ。本ウェブサイトはインターネット上での本連盟の窓口として、最新の情報を入手したり、本連盟とコンタクトを取ったりすることができます。</p>
	<p>本ページではメインページとして、本ウェブサイトに関する総括的な情報を取り扱っています。</p>
</section>

<section>
	<h3>What's new?</h3>
	<dl id='news'>
		<? foreach ($news as $new): ?>
			<? $datetime = explode(' ',$new['News']['created']); ?>
			<dt><?= $datetime[0] ?></dt>
			<dd><?= $new['News']['body']; ?></dd>
		<? endforeach; ?>
	</dl>
</section>