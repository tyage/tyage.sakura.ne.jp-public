<?php

class Builder{
	static $command = array("top");
	static $command_ajax = array("build");
	
	//----- 建設トップ -----//
	static function top(){
		$my = Chara::$self;
		
		//--- 移転か ---//
		m($my->id);
		$query = mq("SELECT * FROM `house` WHERE `id` = '{$my->id}'");
		$row = massoc($query);
		$type = empty($row) ? "新規建設" : "移転";
		
		//--- 街情報取得 ---//
		foreach(Ini::$maps as $id => $map){
			$maps .= "<option value='{$id}'".($my->town == $id ? " selected='selected'" : "").">{$map["name"]}</option>";
		}
		
		//--- 家画像 ---//
		$i = 1;
		$img .= "<table id='houseImg'><tbody><tr>";
		foreach(Ini::$house_img as $key => $value){
			$img .= "<td><label><input type='radio' name='img' value='{$key}'>{$value}万円<br><img src='./img/house/{$key}'></label></td>";
			if($i % 10 == 0) $img .= "</tr><tr>";
			$i++;
		}
		$img .= "</tr></tbody></table>";
		
		//--- 表示 ---//
		View::header_def(array("builder.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	\$builder.set();
});
// --></script>

<form action="./?mode=Builder&amp;command=build" method="POST" id="builder_form">

<input type="hidden" name="type">

<select name="town">
{$maps}
</select>

建設予定地：<span id="town_vector"></span>
<input type="hidden" name="x">
<input type="hidden" name="y">

<div id="map">
EOF;
		
		Map::view($my->town,FALSE);
		
		print <<<EOF
</div>
<br>
<br>
<div id="apart"></div>
{$img}
<br>
<input type="submit" value="{$type}">
</form>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//----- 建設実行 -----//
	static function build(){
		$my = Chara::$self;
		
		$tmp = array(&$_POST["x"],&$_POST["y"]);
		i($tmp);
		if(empty($_POST["type"]) or empty($_POST["town"]) or empty($_POST["x"]) or empty($_POST["y"])) error("足りない項目があります。");
		
		//--- 空いてるか検査 ---//
    switch($_POST["type"]){
    	case "Company":
    	case "House":
				$i = 0;
				$flag = false;
				$fp = fo("log/map/{$_POST["town"]}.csv","r");
				while($row = fgetcsv($fp)){
					if($i++ == $_POST["y"]){
						$flag = true;
						break;
					}
				}
				if($row[$_POST["x"]-1] != "地" or !$flag) error("そこは空地ではありません。");
				$query = mq("SELECT * FROM `house` WHERE `type` IN ('House') AND `town` = '{$_POST["town"]}' AND `x` = '{$_POST["x"]}' AND `y` = '{$_POST["y"]}'");
    		break;
    		
    	case "Apart":
				$query = mq("SELECT * FROM `house` WHERE `type` = 'Apart' AND `town` = '{$_POST["town"]}' AND `x` = '{$_POST["x"]}' AND `y` = '{$_POST["y"]}'");
				if($_POST["x"] > Ini::$apartX or $_POST["x"] < 0 or $_POST["y"] > Ini::$apartY or $_POST["y"] < 0) error("そこは空地ではありません。");
    		break;
    		
			default:
				error("何を建設いたしますか？");
    }
		$data = massoc($query);
		if(!empty($data)) error("そこはもう取られてしまいました。");
    
    //--- 費用取得 ---//
		switch($_POST["type"]){
			case "House":
    	case "Company":
				//--- 地価取得 ---//
				$town = Ini::$maps[$_POST["town"]];
				if(empty($town)) error("そんな街ありませんねん。");
				$money += $town["price"];
				
				//--- 家の支払い（初めにm($_POST)をすると家画像が見つからないので注意） ---//
				if(!Ini::$house_img[$_POST["img"]]) error("家の画像が見つかりません。");
				$money += Ini::$house_img[$_POST["img"]] * 10000;
				break;
				
			case "Apart":
				$money = 0;
				
				break;
			
		}
		
		//--- 家とアパートはどちらか一つしか持てない ---//
		m($_POST);
		m($my->id);
		switch($_POST["type"]){
			case "House":
			case "Apart":
				$whereType = "IN ('House','Apart')";
				break;
				
    	case "Company":
				$whereType = "= '{$_POST["type"]}'";
				break;
		}
		
		//--- ユニットに追加または更新 ---//
		m($my->name);
		
		$query = mq("SELECT * FROM `house` WHERE `id` = '{$my->id}' AND `type` {$whereType}");
		$data = massoc($query);
		if($data["id"] == $my->id)
			mq("UPDATE `house` SET `type` = '{$_POST["type"]}',`img` = '{$_POST["img"]}',`town` = '{$_POST["town"]}',`x` = '{$_POST["x"]}',`y` = '{$_POST["y"]}' WHERE `id` = '{$my->id}' AND `type` {$whereType}");
		else
			mq("INSERT INTO `house` (`id`,`type`,`name`,`img`,`explain`,`town`,`x`,`y`) VALUES ('{$my->id}','{$_POST["type"]}','{$my->name}','{$_POST["img"]}','{$my->name}の家','{$_POST["town"]}','{$_POST["x"]}','{$_POST["y"]}')");
		
		//--- お金処理 ---//
		if($money > $my->money) error("お金が足りません！");
		$my->money -= $money;
		$my->update[] = "money";
		
		print "建設完了！<br>{$money}円かかりました。";
	}
	
}