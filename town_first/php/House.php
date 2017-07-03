<?php

class House{
	static $command = array("top","apart_top","bbs_top","bbs_in","blog_top","shop_top","shop_buy","store_top","store_change","free_top");
	static $command_ajax = array("set_top","set","apart_simple","bbs_res","bbs_create","blog_post","blog_comment");
	
	static $data = array();
	static $urlName = null;
	
	static $apartX = 5;
	static $apartY = 3;
	static $apartDisX = 100;
	static $apartDisY = 100;
	
	static function construct(){
		if( in_array($_GET["command"],self::$command_ajax) or $_GET["command"] == "apart_top" ) return;
		self::$urlName = urlencode($_GET["name"]);
		
		$id = Chara::get_id($_GET["name"]);
		m($id);
		self::load($id);
	}
	
	static function load($id,$error = true){
		$query = mq("SELECT * FROM `house` WHERE `id` = '{$id}' AND `type` IN ('house','Apart')");
		$data = massoc($query);
		if(empty($data) and $error) error("家がありません！");
		
		foreach(Ini::$house_content as $name => $price){
			$data[$name] = unserialize($data[$name]);
		}
		self::$data = $data;
	}
	static function load_apart($town){
		$rows = array();
		$query = mq("SELECT * FROM `house` WHERE `town` = '{$town}' AND `type` = 'apart'");
		while($row = massoc($query)){
			foreach(Ini::$house_content as $name => $price){
				$row[$name] = unserialize($row[$name]);
			}
			$rows[] = $row;
		}
		
    return $rows;
	}
	static function bbs_load($id,$no){
		$query = mq("SELECT * FROM `bbs` WHERE `no` = '{$no}' AND `type` = '家' AND `type_sub` = '{$id}'");
		$bbs = massoc($query);
		if(!$bbs) error("そのスレは見つかりませんでした。");
		
		return $bbs;
	}
	
	static function set_top(){
		$my = Chara::$self;
		
		m($my->id);
		self::load($my->id,false);
		$HouseData = self::$data;
		if($HouseData["id"] != $my->id) return;
		
		foreach(Ini::$house_content as $key => $value){
			h($HouseData[$key]);
			
			if($HouseData[$key]["have"]){
				$checked = ($HouseData[$key]["view"] ? " checked='checked'" : "");
				$viewChecks .= "<label><input type='checkbox' name='view[]' value='{$key}'{$checked}>".Ini::$house_content_ja[$key]."</label><br>";
			}else $check .= "<input type='checkbox' name=\"new[]\" value='{$key}' />".Ini::$house_content_ja[$key]."<br>";
			
			$file = Ini::$dir."member/{$my->id}/css/house/{$key}.css";
			if($HouseData[$key]["have"] and file_exists($file)) $css[$key] = file_get_contents($file);
		}
		
		$i = 1;
		$img .= "<table id='houseImages'><tbody><tr>";
		foreach(Ini::$house_img as $key => $value){
			$img .= "<td><label><input type='radio' name='img' value='{$key}'>{$value}万円<br><img src='./img/house/{$key}'></label></td>";
			if($i % 5 == 0) $img .= "</tr><tr>";
			$i++;
		}
		$img .= "</tr></tbody></table>";
		
		if($HouseData["bbs"]["have"]){
			$content .= <<<EOF
<form action="./?mode=House&amp;command=set&amp;type=bbs" method="POST" class="ajax">
<fieldset>
	<legend>掲示板</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30" value="{$HouseData["bbs"]["title"]}"><br>
		<label>表示件数</label><input type="text" name="count" size="10" value="{$HouseData["bbs"]["count"]}"><br>
		<br>
		<label>CSS</label><textarea name="css" cols="40" rows="10">{$css["bbs"]}</textarea><br>
		<label></label><input type="submit" value="更新する"><br>
	</div>
</fieldset>
</form>
<br>
EOF;
		}
		
		if($HouseData["blog"]["have"]){
			$content .= <<<EOF
<form action="./?mode=House&amp;command=set&amp;type=blog" method="POST" class="ajax">
<fieldset>
	<legend>ブログ</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30" value="{$HouseData["blog_title"]}"><br>
		<label>表示件数</label><input type="text" name="count" size="10" value="{$HouseData["blog"]["count"]}"><br>
		<br>
		<label>CSS</label><textarea name="css" cols="40" rows="10">{$css["blog"]}</textarea><br>
		<label></label><input type="submit" value="更新する"><br>
	</div>
</fieldset>
</form>
<br>
<form action="./?mode=House&amp;command=blog_post" method="POST" class="ajax reset">
<fieldset>
	<legend>ブログ新規投稿</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30"><br>
		<label>内容</label><textarea name="message" cols="40" rows="10"></textarea><br>
		<label></label><input type="submit" value="投稿する"><br>
	</div>
</fieldset>
</form>
<br>
EOF;
		}
		
		if($HouseData["shop"]["have"]){
			$content .= <<<EOF
<form action="./?mode=House&amp;command=set&amp;type=shop" method="POST" class="ajax">
<fieldset>
	<legend>お店</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30" value="{$HouseData["shop"]["title"]}"><br>
		<br>
		<label>CSS</label><textarea name="css" cols="40" rows="10">{$css["shop"]}</textarea><br>
		<label></label><input type="submit" value="更新する"><br>
	</div>
</fieldset>
</form>
<br>
EOF;
		}
		
		if($HouseData["store"]["have"]){
			$content .= <<<EOF
<form action="./?mode=House&amp;command=set&amp;type=store" method="POST" class="ajax">
<fieldset>
	<legend>倉庫</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30" value="{$HouseData["store"]["title"]}"><br>
		<br>
		<label>CSS</label><textarea name="css" cols="40" rows="10">{$css["store"]}</textarea><br>
		<label></label><input type="submit" value="更新する"><br>
	</div>
</fieldset>
</form>
<br>
EOF;
		}
		
		if($HouseData["free"]["have"]){
			$file = Ini::$dir."member/{$my->id}/free.dat";
			if(file_exists($file)) $free = file_get_contents($file);
			
			$content .= <<<EOF
<form action="./?mode=House&amp;command=set&amp;type=free" method="POST" class="ajax">
<fieldset>
	<legend>フリーページ</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30" value="{$HouseData["free"]["title"]}"><br>
		<br>
		<label>内容</label><textarea name="css" cols="40" rows="10">{$free}</textarea><br>
		<label></label><input type="submit" value="更新する"><br>
	</div>
</fieldset>
</form>
<br>
EOF;
		}
		
		$town = Ini::$maps[$my->town];
		
		$js = urlencode(serialize("house_set.js"));
		
		print <<<EOF
<form action="./?mode=House&amp;command=set&amp;type=detail" method="POST" class="ajax">
<fieldset>
	<legend>全体設定</legend>
	
	<p>あなたの家は{$town["name"]}の{$HouseData["x"]}-{$HouseData["y"]}にあります。</p>
	
	<div class="justify">
		<label>表示設備</label><div>{$viewChecks}</div><br>
		<label>説明</label><input type="text" name="explain" value="{$HouseData["explain"]}" size="40"><br>
		<label>画像</label><div><input type="checkbox" name="changeImage" value="on" id="changeHouseImage">画像を変更する<br>{$img}</div><br>
		<label>追加設備</label><div>{$check}</div><br>
		<label></label><input type="submit" value="更新する"><br>
	</div>
</fieldset>
</form>
<br>
{$content}

<script type='text/javascript' src='./?mode=Style&amp;command=js&amp;js={$js}'></script>
EOF;
		
	}
	//----- 家の情報設定 -----//
	static function set(){
		$my = Chara::$self;
		
		m($my->id);
		self::load($my->id);
		$HouseData = self::$data;
		
		$where = " WHERE `id` = '{$my->id}' AND `type` = 'house'";
		
		foreach(Ini::$house_content as $name => $price){
			if( !is_array($HouseData[$name]) ) $HouseData[$name] = array();
		}
		
		switch($_GET["type"]){
			case "bbs":
				i($_POST["count"]);
				
				$HouseData["bbs"]["title"] = $_POST["title"];
				$HouseData["bbs"]["count"] = $_POST["count"];
				
				$val = serialize($HouseData["bbs"]);
				m($val);
				
				mq("UPDATE `house` SET `bbs` = '{$val}'".$where);
				
				fpc("member/{$my->id}/css/house/bbs.css",$_POST["css"]);
				
				break;
			case "blog":
				i($_POST["count"]);
				
				$HouseData["blog"]["title"] = $_POST["title"];
				$HouseData["blog"]["count"] = $_POST["count"];
				
				$val = serialize($HouseData["blog"]);
				m($val);
				
				mq("UPDATE `house` SET `blog` = '{$val}'".$where);
				
				fpc("member/{$my->id}/css/house/blog.css",$_POST["css"]);
				
				break;
			case "shop":
				$HouseData["shop"]["title"] = $_POST["title"];
				
				$val = serialize($HouseData["shop"]);
				m($val);
				
				mq("UPDATE `house` SET `shop` = '{$val}'".$where);
				
				fpc("member/{$my->id}/css/house/shop.css",$_POST["css"]);
				
				break;
			case "store":
				$HouseData["store"]["title"] = $_POST["title"];
				
				$val = serialize($HouseData["store"]);
				m($val);
				
				mq("UPDATE `house` SET `store` = '{$val}'".$where);
				
				fpc("member/{$my->id}/css/house/store.css",$_POST["css"]);
				
				break;
			case "free":
				$HouseData["free"]["title"] = $_POST["title"];
				
				$val = serialize($HouseData["free"]);
				m($val);
				
				mq("UPDATE `house` SET `free` = '{$val}'".$where);
				
				fpc("member/{$my->id}/css/house/free.css",$_POST["css"]);
				
				break;
			case "detail":
				if(!is_array($_POST["view"])) $_POST["view"] = array();
				if(!is_array($_POST["new"])) $_POST["new"] = array();
				
				if($_POST["changeImage"]){
					$all_price += Ini::$house_img[$_POST["img"]] * 10000;
					m($_POST["img"]);
					$set .= "`img` = '{$_POST["img"]}',";
				}
				
				foreach(Ini::$house_content as $name => $price){
					$HouseData[$name]["view"] = ( in_array($name,$_POST["view"]) ? 1 : 0 );
					
					if(in_array($name,$_POST["new"]) and $HouseData[$name]["have"] == 0){
						$HouseData[$name]["have"] = 1;
						$HouseData[$name]["view"] = 1;
						
						$all_price += $price*10000;
					}
					
					if($HouseData[$name]["have"]){
						$val = serialize($HouseData[$name]);
						m($val);
						$set .= "`{$name}` = '{$val}',";
					}
				}
				$set .= "`explain` = '{$_POST["explain"]}',";
				$set = substr($set,0,-1);
				mq("UPDATE `house` SET ".$set.$where);
				
				$my->money -= $all_price;
				$my->update[] = "money";
				
				$message = $all_price > 0 ? $all_price."円を使いました。" : "";
				break;
			default:
		}
		
		print "家の設定を更新しました。".$message;
	}
	
	static function top(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		View::header_def();
		
		foreach(Ini::$house_content as $key => $value){
			if($HouseData[$key]["have"] and $HouseData[$key]["view"]){
				$ja = Ini::$house_content_ja[$key];
				print "<a href='./?mode=House&amp;command={$key}_top&amp;name={$UrlName}'>{$ja}</a><br>";
			}
		}
		
		print <<<EOF
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	static function apart_top(){
		$my = Chara::$self;
		
		View::header_def();
		
		self::apart_view($my->town);
	}
	static function apart_simple(){
		self::apart_view($_GET["town"]);
	}
	static function apart_view($town){
		$my = Chara::$self;
		
		$rooms = self::load_apart($town);
		
		$axis = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p");
		foreach($rooms as $room){
			$houses[$room["y"]][$room["x"]] = $room;
		}
		for($y=0;$y<self::$apartY;$y++){
			for($x=0;$x<self::$apartX;$x++){
				$house = $houses[$axis[$y]][$x];
				$itemHouse .= "new AL.item({".
					"x:".(($x+1)*self::$apartDisX-32).",".
					"y:".(($y+1)*self::$apartDisY-32).",".
					"img:{stop:'".(empty($house) ? "./img/sell.gif" : "./img/door.gif")."'}".
					(empty($house) ? ",elem:{className:'sell',vx:{$x},vy:'{$axis[$y]}'}" : ",href:'./?mode=House&command=top&name=".urlencode($house["name"])."',linkDetail:'{$house["explain"]}'").
				"});\n";
			}
			if($y < self::$apartY - 1) $itemFloor .= "new AL.item({".
				"y:".(($y+1)*self::$apartDisX).
			"});\n";
		}
		$height = $y*self::$apartDisY;
		$width = $x*self::$apartDisX;
		
		print <<<EOF
<!-- なんかnodeを入れないとjQueryでAjaxできないっぽい -->
<div id="ALroom"></div>

<script type="text/javascript" src="http://tyage.sakura.ne.jp/js/action.js"><!--  --></script>
<script type="text/javascript"><!-- 
AL.ini.window.height = {$height};
AL.ini.window.width = {$width} + 200;

AL.set();

AL.def.type = "block";
AL.def.isPushable = false;
AL.def.isBlockable = [];
new AL.item({
	type : "block",
	x : AL.ini.window.width - 32,
	y : AL.ini.window.height - 32,
	img : {
		stop : "./img/door.gif"
	},
	href : "./?mode=Map&command=top",
	linkDetail : "街に戻る"
});
{$itemHouse}

AL.def.isBlockable = ["top","right","bottom","left"];
AL.def.elem = {
	style : {
		backgroundColor : "#666666"
	}
};
AL.def.x = 0;
AL.def.height = 3;
AL.def.width = {$width};
{$itemFloor}

new AL.item({
	type : "elevator",
	x : AL.ini.window.width - 200,
	y : AL.ini.window.height - 50,
	width : 100,
	height : 16,
	baseSpeed : {
		y : 3
	},
	max : {
		y : AL.ini.window.height - 50
	},
	min : {
		y : 50
	}
});

new AL.item({
	type : "control",
	isPushable : true,
	x : AL.ini.window.width - 32,
	y : AL.ini.window.height - 32,
	width : 32,
	height : 32,
	baseSpeed : {
		x : 1,
		jy : 40,
		gy : 3,
		rx : 1/2,
		ry : 1/2
	},
	img : {
		stop : "http://tyage.sakura.ne.jp/img/masao/stop.gif",
		left : "http://tyage.sakura.ne.jp/img/masao/left.gif",
		left2 : "http://tyage.sakura.ne.jp/img/masao/left2.gif",
		right : "http://tyage.sakura.ne.jp/img/masao/right.gif",
		right2 : "http://tyage.sakura.ne.jp/img/masao/right2.gif",
		uright : "http://tyage.sakura.ne.jp/img/masao/uright.gif",
		uleft : "http://tyage.sakura.ne.jp/img/masao/uleft.gif",
		dright : "http://tyage.sakura.ne.jp/img/masao/dright.gif",
		dleft : "http://tyage.sakura.ne.jp/img/masao/dleft.gif",
		bright : "http://tyage.sakura.ne.jp/img/masao/bright.gif",
		bleft : "http://tyage.sakura.ne.jp/img/masao/bleft.gif"
	},
	elem : {
		style : {
			backgroundColor : "transparent"
		}
	}
});

AL.start();
 --></script>
EOF;
	}
	
	//----- 掲示板トップ -----//
	static function bbs_top(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["bbs"]["have"] or !$HouseData["bbs"]["view"]) error("入れません！");
		
		$query = mq("SELECT * FROM `bbs` WHERE `type` = '家' AND `type_sub` = '{$HouseData["id"]}' LIMIT 50");
		while($row = massoc($query)){
			h($row);
			$table .= "<tr><td><a href='./?mode=House&amp;command=bbs_in&amp;name={$UrlName}&amp;no={$row["no"]}'>{$row["title"]}</a></td><td>{$row["author"]}</td><td>{$row["last"]}（{$row["last_name"]}）</td><td>{$row["res"]}</td></tr>\n";
		}
		
		h($HouseData["bbs"]["title"]);
		
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/bbs.css")."</style>");
		
		print <<<EOF
<h1>{$HouseData["bbs"]["title"]}</h1>
<br>
<table class="list">
<thead><tr><th>タイトル</th><th>作成者</th><th>最終更新</th><th>返信件数</th></tr></thead>
<tbody>{$table}</tbody>
</table>
<br>
<form action="./?mode=House&amp;command=bbs_create&amp;name={$UrlName}" method="POST" class="ajax reset">
<fieldset>
	<legend>投稿する</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="30"><br>
		<label>内容</label><textarea name="message" cols="50" rows="10"></textarea><br>
		<label></label><input type="submit" value="新規作成"><br>
	</div>
</fieldset>
</form>
<br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	//----- 掲示板スレの中 -----//
	static function bbs_in(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["bbs"]["have"] or !$HouseData["bbs"]["view"]) error("入れません！");
		
		i($_GET["no"]);
		$bbs = self::bbs_load($HouseData["id"],$_GET["no"]);
		
		$fp = fo("member/{$HouseData["id"]}/bbs/{$_GET["no"]}.csv","r");
		for($i=0;$i<Bbs::$pageMax;$i++){
			if(!$body = assocCsv($fp,"bbs")) break;
			h($body);
			$body["time"] = mkdate($body["time"]);
			$messages .= <<<EOF
<dl>
	<dt>
		<span class="no">No.{$body["no"]}</span>
		<span class="name">{$body["name"]}</span>
		<span class="info">{$body["time"]}</span>
	</dt>
	<dd>
		<pre>{$body["message"]}</pre>
	</dd>
</dl>
EOF;
		}
		fc($fp);
		
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/bbs.css")."</style>");
		
		print <<<EOF
<br>
<div id="bbs">
{$messages}
</div>
<br>
EOF;
		
		if($bbs["res"] < Bbs::$logMax){
			print <<<EOF
<form action="./?mode=House&amp;command=bbs_res&amp;name={$UrlName}&amp;no={$_GET["no"]}" method="POST" class="ajax reset">
<fieldset>
	<legend>投稿する</legend>
	
	<div class="justify">
		<label>メッセージ</label><textarea cols="30" rows="5" name="message"></textarea><br>
		<label></label><input type="submit" value="投稿"><br>
	</div>
</fieldset>
</form>
EOF;
		}
		
		print <<<EOF
<br>
<a href="./?mode=House&amp;command=bbs_top&amp;name={$UrlName}">掲示板一覧に戻る</a><br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
		
	}
	//----- 掲示板レス -----//
	static function bbs_res(){
		$my = Chara::$self;
		
		$id = Chara::get_id($_GET["name"]);
		m($id);
		self::load($id);
		$HouseData = self::$data;
		
		if(!$HouseData["bbs"]["have"] or !$HouseData["bbs"]["view"]) error("入れません！");
		
		i($_GET["no"]);
		$bbs = self::bbs_load($HouseData["id"],$_GET["no"]);
		
		if(!$_POST["message"]) error("メッセージがありません。");
		
		i($bbs["res"]);
		$bbs["res"]++;
		if($bbs["res"] > Bbs::$logMax) error("投稿件数が最大です！");
		
		m($my->name);
		mq("UPDATE `bbs` SET `res` = '{$bbs["res"]}',`last` = NOW(),`last_name` = '{$my->name}' WHERE `no` = '{$_GET["no"]}' AND `type` = '家' AND `type_sub` = '{$HouseData["id"]}'");
		
		$fp = fo("member/{$HouseData["id"]}/bbs/{$_GET["no"]}.csv","a");
		putCsv($fp,"bbs",array("no" => $bbs["res"],"name" => $my->name,"id" => $my->id,"message" => $_POST["message"],"time" => time()));
		fc($fp);
		
		$money = rand(1000,5000);
		$my->money += $money;
		$my->update[] = "money";
		
		print "{$money}円をゲットしました。";
	}
	//----- 掲示板作成 -----//
	static function bbs_create(){
		$my = Chara::$self;
		
		$id = Chara::get_id($_GET["name"]);
		m($id);
		self::load($id);
		$HouseData = self::$data;
		
		if(!$HouseData["bbs"]["have"] or !$HouseData["bbs"]["view"]) error("入れません！");
		
		if( empty($_POST["title"]) ) error("タイトルがありません。");
		if( empty($_POST["message"]) ) error("メッセージがありません。");
		
		$ini = Ini::load("bbs_no");
		$ini["bbs_no"]++;
		Ini::save($ini);
		
		$fp = fo("member/{$HouseData["id"]}/bbs/{$ini["bbs_no"]}.csv","w");
		putCsv($fp,"bbs",array("no" => 0,"title" => $_POST["title"],"name" => $my->name,"id" => $my->id,"message" => $_POST["message"],"time" => time()));
		fc($fp);
		
		$tmp = array(&$_POST["title"],&$my->name);
		m($tmp);
		mq("INSERT INTO `bbs` (`no`,`type`,`type_sub`,`title`,`first`,`author`,`res`,`last`,`last_name`) VALUES ('{$ini["bbs_no"]}','家','{$HouseData["id"]}','{$_POST["title"]}',NOW(),'{$my->name}',0,NOW(),'{$my->name}')");
		
		$money = rand(5000,10000);
		$my->money += $money;
		$my->update[] = "money";
		
		print "{$money}円をゲットしました。";
	}
	
	//----- お店トップ -----//
	static function shop_top(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["shop"]["have"] or !$HouseData["shop"]["view"]) error("入れません！");
		
		$allItems = array();
		$fp = fo("member/{$HouseData["id"]}/shop.csv","r");
		while($row = assocCsv($fp,"shop")){
			$allItems[$row["type"]][] = $row;
			$itemNames[] = $row["name"];
		}
		fc($fp);
		
		m($itemNames);
		$itemData = get_item($itemNames);
		
		foreach($allItems as $type => $items){
			$table .= "<table class='list shopItem'><thead>".Shop::FormatHeader($type)."</thead><tbody>";
			foreach($items as $item){
				$data = $itemData[$item["name"]];
				$item["name"] = "<input type='checkbox' name='{$data["name"]}' value='1'".( ($data["price"] > $my->money) ? " disabled='disabled'" : "" ).">{$data["name"]}";
				$table .= Shop::FormatBody($item,$data);
			}
			$table .= "</tbody></table>";
		}
		
		h($HouseData["shop"]["title"]);
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/shop.css")."</style>");
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	\$shop.set();
});
// --></script>
<h1>{$HouseData["shop"]["title"]}</h1>
<br>
<form action="./?mode=House&amp;command=shop_buy&amp;name={$UrlName}" method="POST">
<table id="shop_item" class="list">
<tbody>
{$table}
</tbody>
</table>
<input type="submit" value="購入">
</form>
<br>

<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>

EOF;
	}
	//----- フリーページトップ -----//
	static function shop_buy(){
		$my = Chara::$self;
		$my->item->load();
		
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["shop"]["have"] or !$HouseData["shop"]["view"]) error("入れません！");
		
		$newRows = $okItems = $errorItems = array();
		$file = "member/{$HouseData["id"]}/shop.csv";
		i($_POST);
		
		$flag = FALSE;
		$fp = fo($file,"r");
		while($row = assocCsv($fp,"shop")){
			if($_POST[$row["name"]] <= 0){
				if($row["stock"] > 0) $newRows[] = $row;
				continue;
			}
			if($row["stock"] <= 0){
				$errorItems[$row["name"]] = 0;
				continue;
			}
			$flag = TRUE;
			
			$stock = $row["stock"] - $_POST[$row["name"]];
			if($stock < 0){
				$_POST[$row["name"]] = $errorItems[$row["name"]] = $row["stock"];
			}else{
				$okItems[$row["name"]] = $_POST[$row["name"]];
				$row["stock"] = $stock;
				if($stock > 0) $newRows[] = $row;
			}
			
			$item_name[] = $row["name"];
			for($i=0;$i<$_POST[$row["name"]];$i++){
				$my->item->add(array("type" => "","name" => $row["name"],"first" => time(),"last" => 0,"count" => $row["count"],"place" => $HouseData["name"]."の家"));
			}
		}
		if(!$flag) error("どの商品もありませんでした！");
		fc($fp);
		
		$fp = fo($file,"w");
		foreach($newRows as $row) putCsv($fp,"shop",$row);
		fc($fp);
		
		$my->item->save();
		$my->item->data += get_item($item_name);
		
		foreach($okItems as $key => $value){
			$okLog .= "<li>{$key}を{$value}個</li>";
		}
		if($okLog) $okLog = "<h3>以下の商品を購入しました。</h3>\n<br><ul>{$okLog}</ul>";
		
		foreach($errorItems as $key => $value){
				$errorLog .= "<li>{$key}が{$value}個</li>";
		}
		if($errorLog) $errorLog = "<span class='error'>以下の商品の在庫が足りませんでした！</span><br><ul>{$errorLog}</ul>しか買えませんでした。<br>";
		
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/shop.css")."</style>");
		
		print <<<EOF
{$okLog}
{$errorLog}
<br>
<a href="./?mode=House&amp;command=shop_top&amp;name={$UrlName}">店のトップに戻る</a><br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//----- ブログトップ -----//
	static function blog_top(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["blog"]["have"] or !$HouseData["blog"]["view"]) error("入れません！");
		
		$fp = fo("member/{$HouseData["id"]}/blog.csv","r");
		for($i=$HouseData["blog"]["count"];$i>0;$i++){
			if(!$row = assocCsv($fp,"blog")) break;
			$message = fgc("member/{$HouseData["id"]}/blog/{$row["no"]}.dat");
			h($message);
			h($row);
			
			$bodyC = "";
			$countC = 0;
			$fpC = fo("member/{$HouseData["id"]}/blogC/{$row["no"]}.csv","r");
			while($rowC = assocCsv($fpC,"blogC")){
				h($rowC);
				$bodyC .= "<div>{$rowC["name"]}（{$rowC["time"]}）<hr><pre>{$rowC["comment"]}</pre></div>";
				$countC++;
			}
			
			$body .= <<<EOF
<h3>{$row["title"]}<span class="info">{$row["time"]}</span></h3>

<pre>{$message}</pre>

<p class='comment'>コメント（{$countC}件）</p>
<div class='comment'>
{$bodyC}
<form action="./?mode=House&amp;command=blog_comment&amp;name={$UrlName}&amp;BlogNo={$row["no"]}" method="POST" class="ajax reset">
	<textarea name="comment" cols="50" rows="5"></textarea><br>
	<input type="submit" value="コメントする"><br>
</form>
</div>
EOF;
		}
		
		h($HouseData["blog"]["title"]);
		
		View::header_def(array("blog.js"),"<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/blog.css")."</style>");
		
		print <<<EOF
<script type="text/javascript"><!-- 
\$(function(){
	\$blog.set();
});
 --></script>
<h1>{$HouseData["blog"]["title"]}</h1>
<br>
<div id="blog">
{$body}
</div>
<br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	//----- ブログ書き込み -----//
	static function blog_post(){
		$my = Chara::$self;
		m($my->id);
		self::load($my->id);
		$HouseData = self::$data;
		
		if(!$HouseData["blog"]["have"] or !$HouseData["blog"]["view"]) error("入れません！");
		if(empty($_POST["title"]) or empty($_POST["message"])) error("記入漏れがあります！");
		
		$ini = Ini::load("BlogNo");
		$ini["BlogNo"]++;
		Ini::save($ini);
		
		$fp = fo("member/{$HouseData["id"]}/blog.csv","a");
		putCsv($fp,"blog",array("no" => $ini["BlogNo"],"title" => $_POST["title"],"time" => mkdate()));
		fc($fp);
		
		fpc("member/{$HouseData["id"]}/blog/{$ini["BlogNo"]}.dat",$_POST["message"]);
		
		print "投稿しましたー";
	}
	//----- ブログコメント -----//
	static function blog_comment(){
		$my = Chara::$self;
		m($my->id);
		self::load($my->id);
		$HouseData = self::$data;
		
		if(!$HouseData["blog"]["have"] or !$HouseData["blog"]["view"]) error("入れません！");
		if(empty($_POST["comment"])) error("記入漏れがあります！");
		
		$flag = false;
		$fp = fo("member/{$HouseData["id"]}/blog.csv","r");
		while($row = assocCsv($fp,"blog")){
			if($row["no"] == $_GET["BlogNo"]){
				$flag = true;
				break;
			}
		}
		fc($fp);
		if(!$flag) error("そんな記事ありませーん！");
		
		$ini = Ini::load("BlogCNo");
		$ini["BlogCNo"]++;
		Ini::save($ini);
		
		$fp = fo("member/{$HouseData["id"]}/blogC/{$_GET["BlogNo"]}.csv","a");
		putCsv($fp,"blogC",array("no" => $ini["BlogCNo"],"name" => $my->name,"comment" => $_POST["comment"],"time" => mkdate()));
		fc($fp);
		
		print "コメントしましたー";
	}
	
	//----- 倉庫トップ -----//
	static function store_top(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["store"]["have"] or !$HouseData["store"]["view"]) error("入れません！");
		if($HouseData["id"] != $my->id) error("家主しか入れません！");
		
		$my->item->load();
		$items .= "<form action='./?mode=House&amp;command=store_change&amp;name={$UrlName}' method='POST'><table class='list'><thead>".Item::FormatHeader("手持ち")."</thead><tbody>";
		foreach($my->item->items as $item){
			$itemData = $my->item->data[$item["name"]];
			$item["name"] = "<input type='checkbox' name='myItems[]' value='{$item["no"]}'>".$item["name"];
			$items .= Item::FormatBody($item,$itemData);
		}
		$items .= "</tbody></table><br><input type='submit' value='交換する'><br><br><table class='list'><thead>".Item::FormatHeader("倉庫")."</thead><tbody>";
		$fp = fo("member/{$my->id}/store.csv","r");
		while($item = assocCsv($fp,"item")){
			$itemData = get_item($item["name"]);
			$itemData = $itemData[$item["name"]];
			$item["name"] = "<input type='checkbox' name='storeItems[]' value='{$item["no"]}'>".$item["name"];
			$items .= Item::FormatBody($item,$itemData);
		}
		fc($fp);
		$items .= "</tbody></table>";
		
		h($HouseData["store"]["title"]);
		
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/store.css")."</style>");
		
		print <<<EOF
<h1>{$HouseData["store"]["title"]}</h1>
<br>
{$items}
<br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	//----- 倉庫交換 -----//
	static function store_change(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["store"]["have"] or !$HouseData["store"]["view"]) error("入れません！");
		if($HouseData["id"] != $my->id) error("家主しか入れません！");
		
		if(!$_POST["storeItems"]) $_POST["storeItems"] = array();
		if(!$_POST["myItems"]) $_POST["myItems"] = array();
		
		$my->item->load();
		$storeItems = array();
		$fp = fo("member/{$my->id}/store.csv","r");
		while($item = assocCsv($fp,"item")){
			$storeItems[$item["no"]] = $item;
		}
		fc($fp);
		
		$ini = Ini::load("StoreNo");
		foreach($_POST["myItems"] as $no){
			if(!$my->item->items[$no]) continue;
			$ini["StoreNo"]++;
			$my->item->items[$no]["no"] = $ini["StoreNo"];
			$storeItems[$ini["StoreNo"]] = $my->item->items[$no];
			$my->item->delete($no);
		}
		foreach($_POST["storeItems"] as $no){
			if(!$storeItems[$no]) continue;
			$my->item->add($storeItems[$no]);
			unset($storeItems[$no]);
		}
		Ini::$update = array("StoreNo");
		Ini::save($ini);
		
		$fp = fo("member/{$my->id}/store.csv","w");
		foreach($storeItems as $item){
			putCsv($fp,"item",$item);
		}
		fc($fp);
		$my->item->save();
		
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/store.css")."</style>");
		
		print <<<EOF
交換完了！
<br>
<a href="./?mode=House&amp;command=store_top&amp;name={$UrlName}">倉庫に戻る</a><br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//----- フリーページトップ -----//
	static function free_top(){
		$my = Chara::$self;
		$UrlName = self::$urlName;
		$HouseData = self::$data;
		
		if(!$HouseData["free"]["have"] or !$HouseData["free"]["view"]) error("入れません！");
		
		h($HouseData["free"]["title"]);
		$content = fgc("member/{$HouseData["id"]}/free.dat");
		h($content);
		
		View::header_def("","<style type='text/css'>".fgc("member/{$HouseData["id"]}/css/house/free.css")."</style>");
		
		print <<<EOF
<h1>{$HouseData["free"]["title"]}</h1>
<br>
<pre>
{$content}
</pre>
<br>
<a href="./?mode=House&amp;command=top&amp;name={$UrlName}">家の玄関に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
}

?>