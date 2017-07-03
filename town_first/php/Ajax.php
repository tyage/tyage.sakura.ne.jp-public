<?php

/***** Ajax *****/
class Ajax{
	static $command = array("entry","chara_data","unit_data","checkId","checkName");
	
	static function construct(){
		header("Content-Type:text/html; charset=UTF-8");
		Ini::$errorHeader = false;
	}
	
	static function deconstruct(){
		exit();
	}
	
	static function checkId(){
		if(!isIDAvailable($_POST["id"])) error("既にIDが使われています。");
	}
	static function checkName(){
		if(!isNameAvailable($_POST["name"])) error("既に名前が使われています。");
	}
	
	//----- ログイン中でのAjax用の下準備 -----//
	static function index(){
		$my = new Chara();
		
		callMethod($_GET["mode"],$_GET["command"]);
		
		$my->save();
	}
	
	//----- 参加者更新 -----//
	static function entry(){
		View::entry();
		
		if($_GET["entry"] != "off") $my = new Chara();
	}
	
	//----- キャラデータ取得 -----//
	static function chara_data(){
		$id = Chara::get_id($_POST["name"]);
		if(!$id) error("ユーザーが見つかりません。");
		
    $chara = new User();
  	$chara->id = $id;
  	$chara->load();
  	$chara->set_detail();
  	
  	$town = Ini::$maps[$chara->town];
		print <<<EOF
総資産：{$chara->money_all}円<br>
称号：{$chara->title}<br>
登録日：{$chara->time["start"]}<br>
最終更新：{$chara->time["reload"]}<br>
性別：{$chara->sexJa}<br>
仕事：{$chara->work["now"]}<br>
現在地：{$town["name"]}<br>
EOF;
	}
	
	//----- ユニットのデータ取得 -----//
	static function unit_data(){
		m($_POST["unit"]);
		if($_POST["type"] == "house"){
			$query = mq("SELECT * FROM `house` WHERE `name` = '{$_POST["unit"]}' LIMIT 0,1");
			$row = massoc($query);
			$row["img"] = "./img/house/".$row["img"];
		}else{
			$query = mq("SELECT * FROM `unit` WHERE `key` = '{$_POST["unit"]}' LIMIT 0,1");
			$row = massoc($query);
			$row["img"] = "./img/".$row["img"];
		}
		h($row);
		
		h($_POST["unit"]);
		print <<<EOF
<div title="{$_POST["unit"]}" class="info">
<h3><img src={$row["img"]}> {$row["name"]}</h3>
<p>{$row["explain"]}</p>
</div>
EOF;
	}
	
}

?>