<?php
$title = "SUSHI取りゲーム";
$styles[] = 'sushi';
$scripts[] = 'sushi';
?>

<table id="result">
	<thead>
		<tr>
			<th>食べた寿司数</th>
			<th>落した寿司数</th>
			<th>食べた総重量</th>
			<th>残り食べれる重量</th>
			<th>普段の総支払額</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="count">0個</td>
			<td class="dropped">0個</td>
			<td class="gram">0グラム</td>
			<td class="hungry"></td>
			<td class="money">0円</td>
		</tr>
	</tbody>
</table>

<div id='forms'>
	<form id="add">
		<p>寿司を<input type="text" size="3" value="0">貫<input type="submit" value="追加"></p>
	</form>
	<form id="speed">
		<p>スピードを<input type="text" size="3" value="5">に<input type="submit" value="変更"></p>
	</form>
</div>

<img id="shokunin" src="/image/sushi/shokunin.gif">
<div id="scroll"></div>
<div id="scroll_table"></div>

<div id="table">
	<img src="/image/sushi/table.png">
</div>

<section>
	<hgroup>
		<h2>SUSHI取りゲーム ～お寿司が恋しい貴方へ～</h2>
	</hgroup>
	
	<section>
		<hgroup>
			<h3>概要</h3>
		</hgroup>
		
		<p>ただの暇つぶしです。
	</section>
		
	<section>
		<hgroup>
			<h3>ストーリー</h3>
		</hgroup>
		
		<p>ある日、スシ○ーの株主総会でに回転寿司食べ放題券が配られました。</p>
		<p>半信半疑で行くと、本当に食べ放題だったようです。</p>
		<p>思う存分食べ尽くせ！</p>
	</section>
	
	<section>
		<hgroup>
			<h3>操作方法</h3>
		</hgroup>
		<p>クリック（またはドラッグ＆ドロップ）でお寿司を落とせます。</p>
		<p>ちゃんと机の上に落とさないと食べれませんよ。（食べ放題なのでで損はしない）</p>
		<p>また、食べれる量は一秒に10グラム増えていきます。（エンドレスです。）</p>
		<p>満腹のときはお寿司に手が出せません。</p>
		<p>また、上のフォームから個数を指定してお寿司を追加したり、スピードを変更したりできます。</p>
		<p>スピードは数が大きいほど遅くなるという罠。</p>
		<p>ウィンドウをリサイズしたりしないでね。</p>
	</section>
	
	<section>
		<hgroup>
			<h3>ネタ一覧</h3>
		</hgroup>
		
		<table id="neta">
			<thead>
				<tr>
					<th>名前</th><th>値段</th><th>重量</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</section>
	
	<section>
		<hgroup>
			<h3>動作環境</h3>
		</hgroup>
		
		<p>IE7.0以上、Google Chrome1.0以上、FireFox2.0以上、Opera9.0以上、Safari3.0以上だったら動くんじゃない？</p>
		<p>JavaScriptはONにしといてくださいよ。</p>
	</section>
	
	<section>
		<hgroup>
			<h3>制作期間</h3>
		</hgroup>
		
		<p>2009/06/01～2009/06/02+α</p>
	</section>
</section>
