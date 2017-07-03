<?php

//----- 外部ファイル読み込み -----//
require_once("ini.php");
require_once("view.php");
require_once("library.php");
require_once("chara.php");

//----- セットアップ -----//
setup();

//----- 呼び出し分岐 -----//
switch($_GET["mode"]){
case "Admin":
case "Ajax":
case "Help":
case "Style":
case "Register":
case "System":
case "Window":
	//--- コマンド確認 ---//
	$class_vars = get_class_vars($_GET["mode"]);
	if(!in_array($_GET["command"],$class_vars["command"])) error("コマンドがおかしいです。");
	
	//--- 実行 ---//
	callMethod($_GET["mode"],$_GET["command"]);
	
	break;
case "Bank":
case "Bbs":
case "Buyer":
case "Builder":
case "Chat":
case "Config":
case "Enq":
case "Gov":
case "Game":
case "House":
case "Item":
case "Mail":
case "Main":
case "Map":
case "School":
case "Shop":
case "Spa":
case "Work":
	//--- コマンド確認 ---//
	$class_vars = get_class_vars($_GET["mode"]);
	if( !is_array($class_vars["command"]) ) $class_vars["command"] = array();
	if( !is_array($class_vars["command_ajax"]) ) $class_vars["command_ajax"] = array();
	
	if( !in_array($_GET["command"],array_merge($class_vars["command"],$class_vars["command_ajax"])) ) error("コマンドがおかしいです。");
	if( in_array($_GET["command"],$class_vars["command_ajax"]) ) callMethod("Ajax","index");
	
	//--- クッキー ---//
	if($_POST["cookie"]){
		setcookie("id",$_POST["id"],time() + Ini::$life*24*60*60);
		setcookie("pass",$_POST["pass"],time() + Ini::$life*24*60*60);
	}
	
	//--- ロード ---//
	$my = new Chara();
	
	//--- 実行 ---//
	callMethod($_GET["mode"],$_GET["command"]);
	
	//--- いろいろと情報更新して保存 ---//
	$my->time["reload"] = mkdate();
	$my->mode = $_GET["mode"];
	$my->command = $_GET["command"];
	$my->browser = $_SERVER['HTTP_USER_AGENT'];
	$ip = get_ip();
	$my->ip[$ip] += 1;
	array_push($my->update,"time","mode","command","browser","ip");
	
	$my->save();
	
	break;
default:
	View::top();
	
	break;
}

mysql_close(Ini::$mysql);

if($_GET["mode"] == "Ajax" or $_GET["mode"] == "Style") exit();

//----- フッター表示 -----//
View::footer();

?>