<?php

class Game{
	static $command = array("top","coin_exhange","card","ufo","porker","blackjack","rulet","ad");
	static $command_ajax = array("coin_buy","coin_sell","ufoCatch","viewAd","clickAd");
	
	//広告クリック
	static $adCoinRate = 3;
	static $adClickMax = 4;
	
	//ゲーセン
	static $coinCharge = 0.9;
	
	//カードゲーム
	static $cardMax = 5;
	static $cardRate = 1;
	static $cardInterval = 1800;
	static $cardLogMax = 15;
	
	//UFOキャッチャー
	static $ufoViewGoods = 30;
	static $ufoGetCoinMax = 5;
	static $ufoGetCoinRate = 35;
	static $ufoGetItemRate = 6;
	static $ufoCost = 1;
	static $ufoInterval = 10;
	static $ufoLogMax = 15;
	
	static function top(){
		$my = Chara::$self;
		
		View::header_def();
		
		print <<<EOF
<p>コイン：{$my->coin}枚</p><br>
<br>
<a href="./?mode=Game&amp;command=coin_exhange">コイン交換所</a><br>
<a href="./?mode=Game&amp;command=ad">コインゲッター</a><br>
<a href="./?mode=Game&amp;command=card">カードゲーム</a><br>
<a href="./?mode=Game&amp;command=ufo">UFOキャッチャー</a><br>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	static function coin_exhange(){
		$my = Chara::$self;
		
		$ini = Ini::load("coinRate");
		$coinRate_sell = intval($ini["coinRate"] * self::$coinCharge);
		
		View::header_def("game.js");
		
		print <<<EOF
<p>コイン：{$my->coin}枚</p><br>
<br>
<form action="./?mode=Game&amp;command=coin_buy" method="POST" class="ajax coin_exchange">
<fieldset>
	<legend>コインを買う</legend>
	
	<p>現在、コイン一枚：{$ini["coinRate"]}円で購入できます。</p><br>
	<div class="justify">
		<label>枚数</label><div><input type="text" name="coin" size="8"> / <input type="checkbox" name="all" value="on">買えるだけ買う</div><br>
		<label></label><input type="submit" value="コインを買う"><br>
	</div>
</fieldset>
</form>

<form action="./?mode=Game&amp;command=coin_sell" method="POST" class="ajax coin_exchange">
<fieldset>
	<legend>コインを売る</legend>
	
	<p>現在、コイン一枚：{$coinRate_sell}円で売却できます。</p><br>
	<div class="justify">
		<label>枚数</label><div><input type="text" name="coin" size="8"> / <input type="checkbox" name="all" value="on">全部売る</div><br>
		<label></label><input type="submit" value="コインを売る"><br>
	</div>
</fieldset>
</form>

<a href="./?mode=Game&amp;command=top">ゲーセンに戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>

<script type="text/javascript"><!-- 
	\$game.set();
 --></script>
EOF;
	}
	static function coin_buy(){
		$my = Chara::$self;
		
		$ini = Ini::load("coinRate");
		
		if(!empty($_POST["all"])) $_POST["coin"] = $my->money / $ini["coinRate"];
		i($_POST["coin"]);
		
		$my->money -= $ini["coinRate"]*$_POST["coin"];
		$my->coin += $_POST["coin"];
		array_push($my->update,"coin","money");
		
		if($my->money < 0) error("お金が足りません！");
		
		print "コインを{$_POST["coin"]}枚買いました";
	}
	static function coin_sell(){
		$my = Chara::$self;
		
		$ini = Ini::load("coinRate");
		$coinRate_sell = intval($ini["coinRate"] * self::$coinCharge);
		
		if(!empty($_POST["all"])) $_POST["coin"] = $my->coin;
		i($_POST["coin"]);
		
		$my->money += $_POST["coin"] * $coinRate_sell;
		$my->coin -= $_POST["coin"];
		array_push($my->update,"coin","money");
		
		if($my->coin < 0) error("コインが足りません！");
		
		print "コインを{$_POST["coin"]}枚売りました";
	}
	
	static function card(){
		$my = Chara::$self;
		
		if($_GET["action"] == "next") self::card_action();
		
		$ini = Ini::load("card");
		foreach(unserialize($ini["card"]) as $card){
			$cards .= "<div class='card'>{$card}</div>";
		}
		
		$query = mq("SELECT * FROM `card` ORDER BY `time` DESC");
		while($row = massoc($query)){
			h($row["name"]);
			$past_game .= "<tr><td>{$row["name"]}</td><td>{$row["number"]}</td><td>{$row["coin"]}枚</td><td>{$row["time"]}</td></tr>";
		}
		
		View::header_def();
		
		$card_rate = self::$cardRate;
		$card_interval = self::$cardInterval;
		print <<<EOF
<pre class="explain">
コインを一枚払い、1～5の数字のかかれたカードを引きます。
同じ人が連続でカードを引くことはできず、また前に引いてから{$card_interval}秒経つまで引けません。
最後に引かれたカード（左端のカード）と同じ数字を引くとハズレです。
ハズレの場合は貯まっているカード×{$card_rate}コインを支払い、貯まったカードが消えます。
それ以外の場合は貯まっているカード×{$card_rate}コインをもらい、場にカードが貯まります。
</pre>

{$cards}<br clear="both">
<a href="./?mode=Game&amp;command=card&amp;action=next">カードを引く</a>

<table class="list">
	<caption>過去のゲーム</caption>
	<thead><tr><th>名前</th><th>引いたカード</th><th>獲得コイン枚数</th><th>時間</th></tr></thead>
	<tbody>{$past_game}</tbody>
</table>
<br>

<a href="./?mode=Game&amp;command=top">ゲーセンに戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	private static function card_action(){
		$my = Chara::$self;
		
		$tmp = array(&$my->id,&$my->name);
		m($tmp);
		$query = mq("SELECT `id` FROM `card` ORDER BY `time` DESC LIMIT 0,1");
		$row = massoc($query);
		if($row["id"] == $my->id) error("連続でカードは引けません。");
		
		$query = mq("SELECT * FROM `card` WHERE `id` = '{$my->id}' ORDER BY `time` DESC");
		if($row = massoc($query)){
			$time = getTime($row["time"]);
			if(time() - $time["timestamp"] < self::$cardInterval) error((self::$cardInterval/60)."分経つまで引けません。");
		}
		
		$ini = Ini::load("card");
		$ini["card"] = unserialize($ini["card"]);
		
		$number = rand(1,self::$cardMax);
		$no = count($ini["card"]);
		if($ini["card"][$no-1] == $number){
			$ini["card"] = array($number);
			$coin = -$no*self::$cardRate;
		}else{
			array_push($ini["card"],$number);
			$coin = $no*self::$cardRate;
		}
		$ini["card"] = serialize($ini["card"]);
		Ini::$update = array("card");
		Ini::save($ini);
		
		mq("INSERT INTO `card` (`id`,`name`,`coin`,`number`,`time`) VALUES ('{$my->id}','{$my->name}',{$coin},{$number},NOW())");
		
		$query = mq("SELECT `time` FROM `card` ORDER BY `time` desc LIMIT ".self::$cardLogMax.",1");
		$row = massoc($query);
		mq("DELETE FROM `card` WHERE `time` < '{$row['time']}' OR `time` = '{$row['time']}'");
		
		$my->coin += $coin;
		array_push($my->update,"coin");
	}
	
	static function ufo(){
		$my = Chara::$self;
		
		View::header_def("ufo.js");
		
		if(!empty($_POST["catch"])){
		 	$message = self::ufoCatch();
			print <<<EOF
<script type='text/javascript'><!-- 
	message.add({$message});
 --></script>
EOF;
		}
		
    for($i=0;$i<self::$ufoViewGoods;$i++){
    	$ufoGoods .= "<img src='./img/coin.gif' style='left:".rand(1,90)."%;top:".rand(80,90)."%;z-index:".rand(1,5).";'>";
    }
		
		$query = mq("SELECT * FROM `ufo` ORDER BY `time` DESC");
		while($row = massoc($query)){
			h($row["name"]);
			$pastGame .= "<tr><td>{$row["name"]}</td><td>{$row["item"]}</td><td>{$row["time"]}</td></tr>";
		}
		
		$ufoInterval = self::$ufoInterval;
		print <<<EOF
<pre class="explain">
カーソルキーで操作します。
コインを一枚払い、右キーを押してUFOを移動した後に、下キーを押して商品を取ります。
前の操作から{$ufoInterval}秒経つまで取れません。
商品は主にコインですが、稀に他のアイテムが取れることがあります。
何も取れないこともあります。
</pre>
<div id="ufoCatch">
	<img src="./img/ufo.gif" id="ufoCatcher" style="z-index:3;" />
	{$ufoGoods}
</div>
<br>
<table class="list">
	<caption>過去のゲーム</caption>
	<thead><tr><th>名前</th><th>獲得アイテム</th><th>時間</th></tr></thead>
	<tbody>{$pastGame}</tbody>
</table>
<br>
<a href="./?mode=Game&amp;command=top">ゲーセンに戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>

<script type="text/javascript"><!-- 
	\$ufo.set();
 --></script>
EOF;
	}
	static function ufoCatch(){
		$my = Chara::$self;
		
		//時間制限
		$query = mq("SELECT * FROM `ufo` WHERE `id` = '{$my->id}' ORDER BY `time` DESC");
		if($row = massoc($query)){
			$time = getTime($row["time"]);
			if(time() - $time["timestamp"] < self::$ufoInterval) error((self::$ufoInterval)."秒後まできません。");
		}
		
		//コイン支払い
		$my->coin -= self::$ufoCost;
		$my->update[] = "coin";
		
		//商品ゲット
		$getItem = array();
		srand();
		
		if(rand(1,100) <= self::$ufoGetCoinRate){
			$coin = rand(1,self::$ufoGetCoinMax);
			$my->coin += $coin;
			
			$getItem[] = "コイン{$coin}枚";
		}
		if(rand(1,100) <= self::$ufoGetItemRate){
			$query = mq("SELECT * FROM `item` ORDER BY RAND() LIMIT 1");
			$item = massoc($query);
			
			$my->item->load();
			$my->item->add($item);
			$my->item->save();
			
			$getItem[] = $item["name"];
		}
		
		$getItem = implode("と",$getItem);
		
		//ログを追加
		m($my->id);
		m($my->name);
		mq("INSERT INTO `ufo` (`id`,`name`,`item`,`time`) VALUES ('{$my->id}','{$my->name}','{$getItem}',NOW())");
		
		//ログを消す
		$query = mq("SELECT `time` FROM `ufo` ORDER BY `time` desc LIMIT ".self::$ufoLogMax.",1");
		$row = massoc($query);
		mq("DELETE FROM `ufo` WHERE `time` < '{$row['time']}' OR `time` = '{$row['time']}'");
		
		//結果報告
		print "コインを".self::$ufoCost."枚払って、<br>".(empty($getItem) ? "何も取れませんでした。" : $getItem."をゲット！");
		
	}
	
	static function ad(){
		$my = Chara::$self;
		
		View::header_def("ad.js");
		
		$adCoinRate = self::$adCoinRate;
		$adClickMax = self::$adClickMax;
		print <<<EOF
<div id="ads"></div>
<pre>
上の広告をクリックすると、一クリックにつきコイン{$adCoinRate}枚ゲット！
一日{$adClickMax}回までクリックできます。
</pre>

<br>
<a href="./?mode=Game&amp;command=top">ゲーセンに戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>

<script type="text/javascript"><!-- 
	\$ad.set();
 --></script>
EOF;
	}
	static function clickAd(){
		$my = Chara::$self;
		
		m($my->id);
		m($my->name);
		$query = mq("SELECT COUNT(*) FROM `ad` WHERE `id` = '{$my->id}' LIMIT 5");
		$row = massoc($query);
		if($row["COUNT(*)"] + 1 > self::$adClickMax) error("一日".self::$adClickMax."回までしかクリックできません。");
		
		mq("INSERT INTO `ad` (`id`,`name`,`time`) VALUES ('{$my->id}','{$my->name}',NOW())");
		$my->coin += self::$adCoinRate;
		$my->update[] = "coin";
		
		print "コインを".self::$adCoinRate."枚ゲットしました！";
	}
	static function viewAd(){
		print file_get_contents("http://axad.shinobi.jp/f/".Ini::$adKey."/?_-_".Ini::$adKey."_-_http://tyage.sakura.ne.jp/");
	}
}

?>