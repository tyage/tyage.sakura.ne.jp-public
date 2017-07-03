<?php

/***** 設定 *****/
class Config{
	
	static $command_ajax = array("go");
	
	//----- 設定 -----//
	static function go(){
		$my = Chara::$self;
		
		switch($_GET["type"]){
			case "chara":
			case "set_css":
			case "make_css":
			case "change_css":
			case "window":
				self::$_GET["type"]();
				break;
			default:
				error("設定内容がおかしいです。");
				break;
		}
	}
	
	static function top(){
		$my = Chara::$self;
		
		//--- キャラ画像 ---//
		$files = scandir(Ini::$dir2."img/chara/");
		foreach($files as $value){
			if($value == "." or $value == "..") continue;
			$chara_img .= "<option value='{$value}'".($value == $my->img ? " selected='selected'" : "").">{$value}</option>";
		}
		
		//--- キャラスピード ---//
		$my->item->load();
		foreach($my->item->items as $no => $item){
			if($my->item->data[$item["name"]]["special"]["speed"] <= 0) continue;
			$selected = ($no == $my->move ? " selected='selected'" : "");
			$moveItem .= "<option value='{$no}'{$selected}>{$item["name"]}</option>";
		}
		$selected = empty($selected) ? " selected='selected'" : "";
		$moveItem .= "<option value='0'{$selected}>徒歩</option>";
		
		//--- CSS ---//
		$file = Ini::$dir."member/{$my->id}/css/original/";
		if( !file_exists($file) ) make_file($file);
		foreach(scandir($file) as $file){
			if($file == "." or $file == "..") continue;
			$file = basename($file,".css");
			if($file == $my->css) $ori_selected = " selected='selected'";
			$ori_option .= "<option value='{$file}'{$ori_selected}>{$file}</option>";
		}
		
		if(!$ori_selected){
    	$theme = file_exists(Ini::$dir."log/css/{$my->css}.css") ? $my->css : 0;
			$css_selected = "theme";
		}else{
			$css_selected = "original";
		}
		foreach(Ini::$set_css as $type => $type_ja){
			$css_option .= "<option value='{$type}'".($css_selected == $type ? " selected='selected'" : "").">{$type_ja}</option>";
		}
		
		//--- ウィンドウ---//
		foreach(Ini::$window as $en => $ja){
			$selected_win = array();
			$selected_win[$my->window[$en]] = " selected='selected'";
			
			$list_win .= "<label>{$ja}</label><select name='{$en}'><option value='show'{$selected_win["show"]}>表示</option><option value='hide'{$selected_win["hide"]}>非表示</option></select><br>";
		}
		
		print <<<EOF
<form action="./?mode=Config&command=go&type=chara" method="POST" class="ajax" id="config_chara">
<fieldset>
	<legend>キャラ設定</legend>
	
	<div class="justify">
		<label>キャラ画像</label><select name="img">{$chara_img}</select><img id="img_view"><br>
		<label>移動手段</label><select name="move">{$moveItem}</select><br>
		<label></label><input type="submit" value="変更">
	</div>
</fieldset>
</form>
<br>
<form action="./?mode=Config&command=go&type=set_css" method="POST" class="ajax" id="config_css">
<fieldset>
	<legend>CSS設定</legend>
	
	<div class="justify">
		<label>現在のCSS</label><select name='css'>{$css_option}</select><div class='original'><select name='original'>{$ori_option}</select></div><div class='theme'><input type='text' name='theme' value='{$theme["no"]}' size='4'><a href='?mode=Theme&amp;command=top&amp;type={$key}' target="_blank">テーマ一覧</a></div><br>
		<label></label><input type="submit" value="変更">
	</div>
</fieldset>
</form>
<br>
<form action="./?mode=Config&command=go&type=make_css" method="POST" class="ajax reset">
<fieldset>
	<legend>CSS新規作成</legend>
	
	<div class="justify">
	  <label>内容</label><textarea cols="40" rows="10" name="css"></textarea><br>
		<label></label><input type="submit" value="作成"><br>
	</div>
</fieldset>
</form>
<br>
<form action="./?mode=Config&command=go&type=change_css" method="POST" class="ajax reset">
<fieldset>
	<legend>CSS変更</legend>
	
	<div class="justify">
	  <label>内容</label><textarea cols="40" rows="10" name="css"></textarea><br>
		<label></label><input type="submit" value="作成"><br>
	</div>
</fieldset>
</form>
<br>
<form action="./?mode=Config&command=go&type=window" method="POST" class="ajax">
<fieldset>
	<legend>毎回表示するウィンドウ</legend>
	
	<div class="justify">
		{$list_win}
		<label></label><input type="submit" value="変更">
	</div>
</fieldset>
</form>
EOF;
	}
	
	static function chara(){
		$my = Chara::$self;
		$my->item->load();
		
		m($_POST);
    $my->img = $_POST["img"];
    $my->move = $_POST["move"];
		array_push($my->update,"img","move");
		
		print "キャラ設定を変更しました。";
	}
	
	static function set_css(){
		$my = Chara::$self;
		
		$my->css = $_POST["css"] == "original" ? $_POST["original"] : $_POST["theme"];
		array_push($my->update,"css");
		
		print "CSSを変更しました。";
	}
	static function make_css(){
		$my = Chara::$self;
		
		$ini = Ini::load("CssNo");
		$ini["CssNo"]++;
		Ini::$update = array("CssNo");
		Ini::save($ini);
		
		fpc("member/{$my->id}/css/original/{$ini["CssNo"]}.css",$_POST["css"]);
		
		print "CSSを作成しました。";
	}
	static function chenge_css(){
	
	}
	
	static function window(){
		$my = Chara::$self;
		
		$my->window = array();
		foreach(Ini::$window as $en => $ja){
			$my->window[$en] = $_POST[$en];
		}
		array_push($my->update,"window");
		
		print "ウィンドウを変更しました。";
	}
	
}

?>