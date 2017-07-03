<?php
$styles[] = 'css/givemac2';
$scripts[] = 'js/boku2d-base';
$scripts[] = 'js/boku2d-dom';
$scripts[] = 'js/givemac2';
$title = 'ソーシャルゲームはどのように社会へ貢献していくか - #givemac2';
?>

<div id="slides">
	<div class="slide">
		<div class="body">
			<header>
				<h2>ソーシャルゲームはどのように社会へ貢献していくか</h2>
				<h3>#givemac2</h3>
			</header>
			
			<p><a href="http://twitter.com/tyage">@tyage</a></p>
			<br />
			<p><a href="http://tyage.sakura.ne.jp/blog/?p=313">原文</a></p>
			<p><a href="http://tyage.sakura.ne.jp/blog/?p=315">ブログ</a></p>
			<br />
			<p>画像：<a href="http://www.t3.rim.or.jp/~naoto/naoto.html">スーパー正男</a></p>
			<p>supported by <a href="http://jquery.com/">jQuery</a></p>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<img class='object masao' style='bottom:0px;left:16px;' data-model='masao'>
			<div class="object" style='top:100px;left:250px;' data-model='floating'>
				<h3>キー操作</h3>
				<p>ジャンプ：↑</p>
				<p>右移動：→</p>
				<p>左移動：←</p>
				<p>ページ移動：土管の上で↓キー　または　土管をクリック</p>
			</div>
			<div class="object next" style='bottom:0;right:0;' data-model='navi' data-direction='next'></div>
		</div>
	</div>
	<div class="slide">
		<div class="body">
			<header>
				<h2>ソーシャルゲームの定義</h2>
			</header>
			
			<p><a href="http://ja.wikipedia.org/wiki/%E3%82%BD%E3%83%BC%E3%82%B7%E3%83%A3%E3%83%AB%E3%82%B2%E3%83%BC%E3%83%A0">Wikipediaの定義</a></p>
			<blockquote>
				ソーシャルゲーム（Social Game）は、ソーシャル・ネットワーキング・サービス（SNS）上で提供され、他のユーザーとコミュニケーションをとりながらプレイするオンラインゲームである。
			</blockquote>
			<br />
			<p class='alert'>※注：ここからはソーシャルゲームを、「他のユーザーとコミュニケーションをとることができるオンラインゲーム」と脳内変換して読んでください。</p>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<img class='object masao' style='bottom:32px;left:16px;' data-model='masao'>
			<div class='object cloud' style='top:230px;left:200px;' data-model='cloud'></div>
			<div class='object cloud' style='top:190px;left:330px;' data-model='cloud'></div>
			<div class='object cloud' style='top:150px;left:460px;' data-model='cloud'></div>
			<div class='object cloud' style='top:110px;left:590px;' data-model='cloud'></div>
			<div class="object prev" style='bottom:0;left:0;' data-model='navi'></div>
			<div class="object next" style='bottom:0;right:0;' data-model='navi'></div>
		</div>
	</div>
	<div class="slide">
		<div class="body">
			<header>
				<h2>ソーシャルとゲーム</h2>
			</header>
			
			<p>ソーシャルとゲーム、それぞれがどのような役割を担うことができるかについて考えてみる。</p>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<img class='object masao' style='bottom:32px;left:16px;' data-model='masao'>
			<div class="object block" style='top:210px;left:270px;' data-model='fixed'></div>
			<div class="object" style='top:210px;left:420px;width:160px;height:32px;' data-model='fixed'>
				<img src="/image/masao/item/block1.gif" style='position:absolute;left:0px;' alt="" />
				<img src="/image/masao/item/itembox.gif" style='position:absolute;left:32px;' alt="" />
				<img src="/image/masao/item/block1.gif" style='position:absolute;left:64px;' alt="" />
				<img src="/image/masao/item/itembox.gif" style='position:absolute;left:96px;' alt="" />
				<img src="/image/masao/item/block1.gif" style='position:absolute;left:128px;' alt="" />
			</div>
			<div class="object itembox" style='top:120px;left:484px;' data-model='fixed'></div>
			<img class="object enemy" style='bottom:0;left:600px;' data-model='kame'>
			<div class="object prev" style='bottom:0;left:0;' data-model='navi'></div>
			<div class="object next" style='bottom:0;right:0;' data-model='navi'></div>
		</div>
	</div>
	<div class="slide">
		<div class="body">
			<header>
				<h2>ゲームについて</h2>
			</header>
			
			<ul>
				<li>今日の日本において、ゲームをしない子供は少ない</li>
				<li>ゲームは子供の興味を引くのに適しており、大抵は敷居が低いものである</li>
				<li>それによる影響は良くも悪くも大きかったりする</li>
				<li>ゲームは、上手く使えば子供に対する教育を担うことが可能である</li>
			</ul>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<img class='object masao' src='/image/masao/stop.gif' style='bottom:32px;left:16px;' data-model='masao'>
			<img class="object enemy" style='bottom:0;left:100px;' data-model='kame'>
			<img class="object enemy" style='bottom:0;left:200px;' data-model='kame'>
			<img class="object enemy" style='bottom:0;left:300px;' data-model='kame'>
			<img class="object enemy" style='bottom:0;left:400px;' data-model='kame'>
			<img class="object enemy" style='bottom:0;left:500px;' data-model='kame'>
			<img class="object enemy" style='bottom:0;left:600px;' data-model='kame'>
			<img class="object enemy" style='bottom:0;left:700px;' data-model='kame'>
			<div class="object prev" style='bottom:0;left:0;' data-model='navi'></div>
			<div class="object next" style='bottom:0;right:0;' data-model='navi'></div>
		</div>
	</div>
	<div class="slide">
		<div class="body">
			<header>
				<h2>ソーシャルであることについて</h2>
			</header>
			
			<ul>
				<li>ソーシャルであることで人と関わることができる</li>
				<li>互いに何かを教えあう、同じものに興味を持つ仲間を作ることができる</li>
				<li>他、人との関わり方を新たに学ぶかもしれない</li>
				<li>ゲームで遊んでいるうち、思いもしないことを学んでいる可能性がある</li>
			</ul>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<img class='object masao' src='/image/masao/stop.gif' style='bottom:32px;left:16px;' data-model='masao'>
			<img class="object enemy" style='bottom:0;left:100px;' data-model='mariri'>
			<img class="object enemy" style='bottom:0;left:200px;' data-model='mariri'>
			<img class="object enemy" style='bottom:0;left:300px;' data-model='mariri'>
			<img class="object enemy" style='bottom:0;left:400px;' data-model='mariri'>
			<img class="object enemy" style='bottom:0;left:500px;' data-model='mariri'>
			<img class="object enemy" style='bottom:0;left:600px;' data-model='mariri'>
			<img class="object enemy" style='bottom:0;left:700px;' data-model='mariri'>
			<div class="object prev" style='bottom:0;left:0;' data-model='navi'></div>
			<div class="object next" style='bottom:0;right:0;' data-model='navi'></div>
		</div>
	</div>
	<div class="slide">
		<div class="body">
			<header>
				<h2>自分の事例</h2>
			</header>
			
			<p>ゲームを通じてプログラミングに興味を持つようになった</p>
			<br />
			<ul>
				<li><a href="http://brassiere.jp/02cgi/09.html">TOWN</a>というオープンソースなコミュニティベースのゲームに出会う</li>
				<li>HTML/JavaScript/CSSを他のユーザーが使っているのを見て真似する（ゲーム内にXSS脆弱性があった）</li>
				<li>XSSの危険性について認識する</li>
				<li>他のユーザーから教えてもらいながら、Webサイトの作成したり自分でそのゲームを設置する</li>
				<li>自分で本を買うようになり、本格的にプログラミングをはじめる</li>
			</ul>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<img class='object masao' src='/image/masao/stop.gif' style='bottom:32px;left:16px;' data-model='masao'>
			<img class="object enemy" style='bottom:30px;left:100px;' data-model='poppi'>
			<img class="object enemy" style='bottom:60px;left:200px;' data-model='poppi'>
			<img class="object enemy" style='bottom:90px;left:300px;' data-model='poppi'>
			<img class="object enemy" style='bottom:120px;left:400px;' data-model='poppi'>
			<img class="object enemy" style='bottom:150px;left:500px;' data-model='poppi'>
			<img class="object enemy" style='bottom:180px;left:600px;' data-model='poppi'>
			<img class="object enemy" style='bottom:210px;left:700px;' data-model='poppi'>
			<div class="object prev" style='bottom:0;left:0;' data-model='navi'></div>
			<div class="object next" style='bottom:0;right:0;' data-model='navi'></div>
		</div>
	</div>
	<div class="slide">
		<div class="body">
			<header>
				<h2>まとめ</h2>
			</header>
			
			<ul>
				<li>これからソーシャルゲームで遊ぶ子供は増えていくと思われる</li>
				<li>それに伴って、ソーシャルゲームの社会的影響も今後増していくはず</li>
				<li>自分のようにソーシャルゲームによって良い方向に影響されるだけでなく、悪い方向に影響される場合もある</li>
				<li>ソーシャルゲームの影響が社会問題ではなく、社会貢献になることが望ましい</li>
				<li>だたしそれはユーザーの動きに依存するため、ゲーム側での統制は少々難しいと考えられる</li>
				<li>ソーシャルゲームだけに関わらず、SNSなどにも関わる話ではあるが、いかにしてユーザー同士のよりよい交流を保っていくのかが今後問題になるのではないだろうか</li>
				<li>そしてそれがソーシャルゲームが社会へ教育という点で貢献していく上で、重要になってくるのだと思っている</li>
			</ul>
		</div>
		
		<div class='world'>
			<div class='object wall top' data-model='fixed'></div>
			<div class='object wall bottom' data-model='ground'></div>
			<div class='object wall left' data-model='fixed'></div>
			<div class='object wall right' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:270px;' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:302px;' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:334px;' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:366px;' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:398px;' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:430px;' data-model='fixed'></div>
			<div class="object block" style='bottom:0px;left:462px;' data-model='fixed'></div>
			<div class="object block" style='bottom:32px;left:302px;' data-model='fixed'></div>
			<div class="object block" style='bottom:32px;left:334px;' data-model='fixed'></div>
			<div class="object block" style='bottom:32px;left:366px;' data-model='fixed'></div>
			<div class="object block" style='bottom:32px;left:398px;' data-model='fixed'></div>
			<div class="object block" style='bottom:32px;left:430px;' data-model='fixed'></div>
			<div class="object block" style='bottom:32px;left:462px;' data-model='fixed'></div>
			<div class="object block" style='bottom:64px;left:334px;' data-model='fixed'></div>
			<div class="object block" style='bottom:64px;left:366px;' data-model='fixed'></div>
			<div class="object block" style='bottom:64px;left:398px;' data-model='fixed'></div>
			<div class="object block" style='bottom:64px;left:430px;' data-model='fixed'></div>
			<div class="object block" style='bottom:64px;left:462px;' data-model='fixed'></div>
			<div class="object block" style='bottom:96px;left:366px;' data-model='fixed'></div>
			<div class="object block" style='bottom:96px;left:398px;' data-model='fixed'></div>
			<div class="object block" style='bottom:96px;left:430px;' data-model='fixed'></div>
			<div class="object block" style='bottom:96px;left:462px;' data-model='fixed'></div>
			<div class="object block" style='bottom:128px;left:398px;' data-model='fixed'></div>
			<div class="object block" style='bottom:128px;left:430px;' data-model='fixed'></div>
			<div class="object block" style='bottom:128px;left:462px;' data-model='fixed'></div>
			<div class="object block" style='bottom:160px;left:430px;' data-model='fixed'></div>
			<div class="object block" style='bottom:160px;left:462px;' data-model='fixed'></div>
			<div class="object block" style='bottom:192px;left:462px;' data-model='fixed'></div>
			<div class="object tower" style='bottom:0px;left:600px;' data-model='tower'></div>
			<img class='object masao' src='/image/masao/stop.gif' style='bottom:32px;left:16px;' data-model='masao'>
			<div class="object prev" style='bottom:0;left:0;' data-model='navi'></div>
		</div>
	</div>
</div>