<?php

function __autoload($className){
    require_once(Ini::$dir."php/{$className}.php");
}

//----- エラー -----//
function error($message){
	$my = Chara::$self;
	
	if(!headers_sent() and Ini::$errorHeader) View::header();
	
	print <<<EOF
<span class="error_mes">
エラー！<br>
{$message}
</span>
EOF;
	
	if(!Ini::$errorHeader) exit();
	
	//--- ログイン時 ---//
	if($my->login){
		foreach($_GET as $key => $value){
			if($key == "mode" or $key == "command") continue;
			$get .= "{$key}=".urlencode($value)."&amp;";
		}
		print <<<EOF
<br>
<a href="./?mode={$my->mode}&amp;command={$my->command}&amp;{$get}">前の画面へ</a><br>
<a href="./?mode=Map&amp;command=top">マップへ戻る</a>
EOF;
	}
	
	View::footer();
	
	exit();
}

//----- サニタイズ + 改行コード統一 -----//
function sanitize(&$data){
	if(is_array($data)) return array_map('sanitize',&$data);
	
	$data = preg_replace("/\r?\n/","\r",$data);
	$data = preg_replace("/\r/","\n",$data);
	$data = str_replace("\0","",$data);
}

//----- セットアップ -----//
function setup(){
	//セッション開始
	session_start();
	
	//データベース接続
	if($_SERVER["SERVER_NAME"] == "localhost"){
		Ini::$mysql = mysql_connect(Ini::$db_host,Ini::$db_user,Ini::$db_pass) or error("データベースコネクトエラー<br>".mysql_error());
		mysql_select_db(Ini::$db_name) or error("データベースセレクトエラー<br>".mysql_error());
		Ini::$dir = "./";
	}else{
		Ini::$mysql = mysql_connect(Ini::$db_host_sa,Ini::$db_user_sa,Ini::$db_pass_sa) or error("データベースコネクトエラー<br>".mysql_error());
		mysql_select_db(Ini::$db_name_sa) or error("データベースセレクトエラー<br>".mysql_error());
	}
	mq("SET NAMES utf8");
	mysql_set_charset("utf8");
	
	//サニタイズ
	sanitize($_GET);
	sanitize($_POST);
}

//----- MySQL クエリ -----//
function mq($sql){
	$query = mysql_query($sql) or error("クエリ失敗<br>SQL:<br>{$sql}<br>".mysql_error());
	return $query;
}

//----- MySQL 連想配列で取得 -----//
function massoc(&$query){
	$row = mysql_fetch_assoc($query);
	return $row;
}

//----- HTMLサニタイズ -----//
function h(&$data){
	if(is_array($data)){
		array_map("h",&$data);
	}else{
		if(get_magic_quotes_gpc()) $data = stripslashes($data);
		$data = htmlspecialchars($data,ENT_QUOTES);
	}
}

//----- MySQLサニタイズ（複数返す） -----//
function m2($data){
	if(get_magic_quotes_gpc()){
		$data = stripslashes($data);
	}
	return mysql_real_escape_string($data);
}

//----- MySQLサニタイズ -----//
function m(&$data){
	if(is_array($data)){
		array_map("m",&$data);
	}else{
		if(get_magic_quotes_gpc()) $data = stripslashes($data);
		$data = mysql_real_escape_string($data);
	}
}

//----- 整数化 -----//
function i(&$data){
	if(is_array($data)){
		array_map("i",&$data);
	}else{
		$data = intval($data);
	}
}

//----- IP取得 -----//
function get_ip(){
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	}elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}else{
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if(!$ip){error("IPが取得できません。");}
	
	return $ip;
}

//----- モードからクラス -----//
function callMethod($class,$method){
	if( method_exists($class,"construct") ) call_user_func("{$class}::construct","");
	method_exists($class,$method) ? call_user_func("{$class}::{$method}","") : error("モードまたはコマンドがおかしいです。");
	if( method_exists($class,"deconstruct") ) call_user_func("{$class}::deconstruct","");
}

//----- アイテム名配列からデータ取得 -----//
function get_item($name = array()){
	$item = array();
	
	m($name);
	
	//--- アイテムのデータ取得 ---//
	$query = mq("SELECT * FROM `item` WHERE `name` IN (".makeValues($name).")");
	while($row = massoc($query)){
		$unserialize = unserialize($row["special"]);
		$row["special"] = ( is_array($unserialize) ? $unserialize : array() );
		$item[$row["name"]] = $row;
	}
	
	return $item;
}

//----- ファイルオープン -----//
function fo($file,$mode){
	$file = Ini::$dir.$file;
	
	if( !file_exists($file) ) make_file($file);
	
	$fp = @fopen($file,$mode) or error("ファイル：{$file}が開けません。");
	flock($fp,LOCK_EX);
	
	return $fp;
}

//----- ファイルクローズ -----//
function fc(&$fp){
	flock($fp,LOCK_UN);
	fclose($fp);
}

//----- file_get_contents -----//
function fgc($filename){
	$filename = Ini::$dir.$filename;
	if( !file_exists($filename) ) make_file($filename);
	return file_get_contents($filename);
}

//----- file_put_contents -----//
function fpc($filename,$data){
	$filename = Ini::$dir.$filename;
	if( !file_exists($filename) ) make_file($filename);
	return file_put_contents($filename,$data);
}

//----- ファイル作成 -----//
function make_file($file){
	$files = explode("/",$file);
	$length = count($files);
	
	for($i = 0;$i < $length;$i++){
		$pass .= $files[$i];
		
		if( !file_exists($pass) and !empty($files[$i]) ){
			if($i == $length - 1) touch($pass);
			else{
				mkdir($pass) or error("Make dir error : ".$pass);
				chmod($pass,0777);
			}
		}
		
		$pass .= "/";
	}
}

//----- ファイル・ディレクトリ消去 -----//
function del_dir($dir){
	if($handle = opendir("$dir")){
		while(false !== ($item = readdir($handle))){
			if($item != "." && $item != "..") {
				if(is_dir("$dir/$item")){
					del_dir("$dir/$item");
				}else{
					unlink("$dir/$item");
				}
			}
		}
		closedir($handle);
		rmdir($dir);
	}
}

//----- SQLの値列挙文を作成 -----//
function makeValues($array){
	return "'".(is_array($array) ? implode("','",$array) : $array)."'";
}

//----- SQLのカラム列挙文を作成 -----//
function makeColumns($array){
	return "`".(is_array($array) ? implode("`,`",$array) : $array)."`";
}

//----- GETデータをURIエンコードしたものをゲット -----//
function get_get(){
	$my = Chara::$self;
	
	if(!$_GET) return NULL;
	
	foreach($_GET as $key => $value){
		$get .= "{$key}=".urlencode($value)."&amp;";
	}
	return $get;
}

//----- 時間をフォーマット（Y-m-d H:i:s形式に） -----//
function mkdate($timestamp = false){
	if(!$timestamp) $timestamp = time();
	
	return date("Y-m-d H:i:s",$timestamp);
}

//----- フォーマットされた時間（Y-m-d H:i:s形式）から値を得る -----//
function getTime($format = false){
	if(!$format) $format = date("Y-m-d H:i:s");
	
	$datehour = explode(' ',$format);
	$date = explode("-",$datehour[0]);
	$hour = explode(":",$datehour[1]);
	$timestamp = mktime ($hour[0],$hour[1],$hour[2],$date[1],$date[2],$date[0]);
	
	return array("hour" => $hour[0],"minute" => $hour[1],"second" => $hour[2],"year" => $date[0],"month" => $date[1],"day" => $date[2],"timestamp" => $timestamp);
}

//----- 配列の任意の位置に要素を挿入 -----//
function array_insert(&$array,$insert,$pos){
	if(!is_array($array)) return false;
	
	$last = array_splice($array,$pos);
	array_push($array,$insert);
	$array = array_merge($array,$last);
	return true;
}

function assocCsv(&$fp,$header){
	if(!is_array( $row = fgetcsv($fp) )) return false;
	foreach($row as $no => $value){
		$newRow[Ini::$fheader[$header][$no]] = $value;
	}
	return $newRow;
}
function putCsv(&$fp,$header,$data){
	foreach(Ini::$fheader[$header] as $key){
		$newData[] = $data[$key];
	}
	fputcsv($fp,$newData);
}

function getCharaImages(){
	$files = scandir(Ini::$dir2."img/chara/");
	foreach($files as $file){
		if($file != "." && $file != "..") $charaImages[] = $file;
	}
	return $charaImages;
}

function isIdAvailable($id){
	m($id);
	$query = mq("SELECT `id` FROM `member` WHERE `id` = '{$id}'");
	$row = massoc($query);
	return empty($row);
}
function isNameAvailable($name){
	m($name);
	$query = mq("SELECT `name` FROM `member` WHERE `name` = '{$name}'");
	$row = massoc($query);
	return empty($row);
}

//----- 性別を日本語に変換 -----//
function formatSex($sex){
	return $sex == "m" ? "男" : "女";
}

//----- 配列をHTMLの属性値に変換 -----//
function formatAttr($attrs){
	foreach($attrs as $attr => $value){
		$format .= " {$attr}='{$value}'";
	}
	return $format;
}

?>