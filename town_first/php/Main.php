<?php

class Main{
	static $command = array("in","out");
	
	static function in(){
		$my = Chara::$self;
		
		//--- 読み込み ---//
		$_GET["mode"] = $my->mode;
		$_GET["command"] = $my->command;
		
		// 入ったときの無限ループを防ぐ
		$_GET["mode"] = "Map";
		$_GET["command"] = "top";
		if($my->mode == "Main" and $my->command = "out"){
			$_GET["mode"] = "Map";
			$_GET["command"] = "top";
		}
		
		//--- コマンド確認 ---//		
		$class_vars = get_class_vars($_GET["mode"]);
		if(!in_array($_GET["command"],$class_vars["command"])) error("コマンドがおかしいです。");
		
		//--- 実行 ---//
		callMethod($_GET["mode"],$_GET["command"]);
	}
	
	static function out(){
		$my = Chara::$self;
		
		// 出たら入れなくなるのを防ぐ
		if($my->mode == "Main" and $my->command = "out"){
			$my->mode = "Map";
			$my->command = "top";
		}
		
		session_unset();
		
		$my->logout();
		//Chat::comment("","","『{$my->name}』さんが落ちました。");
		
		View::top();
	}
	
}

?>