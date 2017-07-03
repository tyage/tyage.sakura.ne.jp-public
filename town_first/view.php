<?php

class View{
	static $topMessage = "間違えて全員のデータを消してしまいました！<br>ごめんなさい！<br>もう一度登録お願いします。<br>今度こそきちんと登録できるようになりました！";
	
	//----- トップ -----//
	static function top(){
		self::header(array("jquery/jquery.js","basic.js","entry.js","map.js","message.js"));
		
		$login = $_COOKIE["id"] ? "<a href='./?mode=Main&amp;command=in&amp;login=cookie'>ID:{$_COOKIE["id"]}としてログイン</a><br>" : "<a href='./?mode=Main&amp;command=in&amp;login=test'>テストユーザーでログイン</a><br>";
		
		$message = self::$topMessage;
		print <<<EOF
<script type="text/javascript"><!--
$(document).ready(function(){
	set();
	\$entry.entry = "off";
	\$entry.set();
	\$map.setInfo();
	\$message.add("{$message}");
});
// --></script>

<div id="header">
	<div id="notify" style="margin:10px;"><a href="/town/">ちょっと新しいTOWNはこちら</a></div>
	<div id="entry">
EOF;
		
		View::entry();
		print <<<EOF
	</div>
</div>

<div id="content">

<div id="map" style="float:left">
EOF;
		
		$town = array_rand(Ini::$maps);
		//キャラ表示
		print "<div id='map_chara'>";
		View::entry_town($town);
		print "</div>";
		
		//マップ表示
		Map::view($town,FALSE);
		
		print <<<EOF
</div>
<div style="float:left;margin:5px;">

<h2>ログイン</h2>
{$login}
<br>
<form action="./?mode=Main&amp;command=in" method="POST">
<fieldset>
	<legend>ID、PASSを入力してログイン</legend>
	
	<div class="justify">
		<label>ID</label><input type="text" name="id" size="20"><br>
		<label>PASS</label><input type="text" name="pass" size="20"><br>
		<label>ID、PASSを保存</label><input type="checkbox" name="cookie" value="on" checked="checked"><br>
		<label></label><input type="submit" value="ログイン"><br>
	</div>
</fieldset>
</form>
<br>
<a href="./?mode=System&amp;command=cookie_delete">クッキー削除</a><br>
<br>

<h2>新規登録</h2>
<a href="./?mode=Register&amp;command=top">新規登録</a><br>
<br>

<h2>最新情報</h2>
<pre>
<a href="http://tyage.sakura.ne.jp/blog/?p=910">TOWN ver 1.6.0</a>
<a href="http://tyage.sakura.ne.jp/blog/?p=703">TOWN ver 1.5.0</a>
<a href="http://tyage.sakura.ne.jp/blog/?p=546">TOWN ver 1.4.1</a>
<a href="http://tyage.sakura.ne.jp/blog/?p=455">TOWN ver 1.4.0</a>
<a href="http://tyage.sakura.ne.jp/blog/?p=264">TOWN ver 1.3.1</a>
<a href="http://tyage.sakura.ne.jp/blog/?p=243">TOWN ver 1.3.0</a>
</pre>
<br>

<h2>ご注意！</h2>
<pre>
IE6以下には対応していません。
</pre>

</div>

EOF;
	}
	
	//----- ヘッダー -----//
	static function header($js = array(),$header = ""){
		$my = Chara::$self;
    
		if(file_exists(Ini::$dir."member/{$my->id}/css/original/{$my->css}.css")) $base_css = "original";
		elseif(file_exists(Ini::$dir."log/css/{$my->css}.css")) $base_css = "theme";
		else $base_css = "default";
		$css = array("id" => $my->css,"type" => $base_css);
    
		$link .= "<link rel='stylesheet' type='text/css' href='./?mode=Style&amp;command=css&amp;css=".urlencode(serialize($css))."'><script type='text/javascript' src='./?mode=Style&amp;command=js&amp;js=".urlencode(serialize($js))."'></script>";
		
		$title = Ini::$title;
		$ver = Ini::$ver;
		
		header("Content-Type:text/html; charset=UTF-8");
		
		print <<<EOM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang=ja>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
$link
<title>{$title}（Ver {$ver}）</title>
{$header}
</head>
<body>

<noscript>
	<meta http-equiv="Refresh" content="1;URL=http://tyage.sakura.ne.jp/error.html">
	JavaScriptは有効にしてください！
</noscript>

<!--[if lte IE 6]><script src="http://tyage.sakura.ne.jp/js/ie6/warning.js"></script><script>window.onload=function(){e("http://tyage.sakura.ne.jp/js/ie6/")}</script><![endif]-->

<div id="chara_data" class="data"></div>

<div id="message_box">
</div>

EOM;
	}
	
	//----- ヘッダー（標準） -----//
	static function header_def($js = array(),$header = ""){
		$my = Chara::$self;
		
		$get = get_get();
		
		//ウィンドウのリスト
		foreach(Ini::$window as $en => $ja){
			$window .= "<li title='{$en}'><img src='./img/window/{$en}.gif' name='{$ja}' title='{$ja}ウィンドウを開きます。' class='navi'></li>";
		}
		
		//表示するウィンドウ
		foreach($my->window as $name => $type){
			$window_show .= "'{$name}':'{$type}',";
		}
		$window_show = substr($window_show,0,-1);
		
		//ヘッダー
		$js = empty($js) ? Ini::$header : array_merge(Ini::$header,is_array($js) ? $js : array($js));
		self::header($js,$header);
		
		print <<<EOF
<script type="text/javascript"><!--
$(window).load(function(){
	\$top = "off";
	set();
	\$menu.set();
	\$navi.set();
	\$win.set({{$window_show}});
	\$tab.set();
	\$entry.set();
	\$mail.check();
	\$mail.autoCheck();
});
// --></script>

<ul id="topMenu">
	<li id="windowMenu">
		<ul>
			{$window}
		</ul>
	</li>
	<li id="commandMenu">
		<ul>
			<li><a href="./?{$get}"><img src="./img/reload.gif" name="更新" title="画面を更新します。失敗することもあるので注意" class="navi"></a></li>
			<li><a href="./?mode=Main&amp;command=out"><img src="./img/exit.gif" name="ログアウト" title="ログアウトします。お疲れ様でした。" class="navi"></a></li>
			<li><a href="./?mode=Help&amp;command=top" target="_blank"><img src="./img/book.gif" name="マニュアル" title="まだ出来ていません。" class="navi"></a></li>
		</ul>
	</li>
</ul>

<div id="header">
	<div id="notify" style="margin:10px;"><a href="/town/">ちょっと新しいTOWNはこちら</a></div>
	<div id="entry">
EOF;
		
		View::entry();
		print <<<EOF
	</div>
	
</div>

<div id="content">
EOF;
		
	}
	
	//----- フッター -----//
	static function footer(){
		print <<<EOM
</div>

<br style="clear:both;">
<div id="footer">
	<a href="./?mode=View&amp;command=top">トップへ</a><br>
	Copyright &copy; 2009 チャゲ All rights reserved.
</div>

</body>
</html>
EOM;
	}
	
	//----- メッセージ -----//
	static function message($message){
		print <<<EOF
<div>
{$message}
</div>
EOF;
	}
	
	//----- 参加者一覧 -----//
	static function entry(){
		$my = Chara::$self;
		
		//--- 参加者消去 ---//
		$limit = Ini::$entry_limit;
		$query = mq("SELECT `id`,`name` FROM `entry` WHERE `last` < SUBTIME(NOW(),'0:{$limit}:0')");
		while($row = massoc($query)){
			if($my->name == $row["id"]) continue;
			$delete_name[] = $row["name"];
		}
		//if(!empty($delete_name)) foreach($delete_name as $name) Chat::comment("","","『{$name}』さんが落ちました。");
		
		m($delete_name);
		mq("DELETE FROM `entry` WHERE `name` IN (".makeValues($delete_name).")");
		
		//--- 参加者表示 ---//
		$query = mq("SELECT * FROM `entry`");
		$i = 0;
		while($row = massoc($query)){
			h($row['name']);
			$i++;
			if($my->id == $row['id']){
				$entry .= "<span title='{$row['name']}'>{$row['name']}</span>★";
			}elseif($row['show']){
				$entry .= "<span title='{$row['name']}'>{$row['name']}</span>★";
			}
		}
		
		print "<a href='#' id='entry_reload' title='参加者情報を更新する'>現在の総参加者（{$i}人）</a>：{$entry}";
	}
	
	//----- 参加者一覧（キャラだけ） -----//
	static function entry_town($town){
		$my = Chara::$self;
		
		$query = mq("SELECT * FROM `entry`");
		while($row = massoc($query)){
			$id = "";
			h($row['name']);
			if($my->id == $row['id']){
				$id = " id='chara_my'";
			}elseif($town != $row['town'] or !$row['show']){
				continue;
			}
			
			print <<<EOF
<div style="top:{$row["y"]}px;left:{$row["x"]}px;" title="{$row["name"]}" class="chara"{$id}>
<span>{$row["name"]}</span><br><img src="./img/chara/{$row["img"]}" title="{$row["name"]}">
</div>
EOF;
		}
	}
	
	//----- 再度ログイン -----//
	static function relogin(){
		$my = Chara::$self;
		
		if($_GET["mode"] == "Ajax") return;
		
		$login = $_COOKIE["id"] ? "<a href='./?mode=Main&amp;command=in&amp;login=cookie'>保存されているID、PASSからログイン</a>（※推奨）<br>" : "<a href='./?mode=Main&amp;command=in&amp;login=test'>テストユーザーでログイン</a><br>";
		
		//POST、GETデータの取得
		if(!$_POST) $_POST = array();
		if(!$_GET) $_GET = array();
		
		foreach($_POST as $key => $value){
			if($key == "id" or $key == "pass") continue;
			h($key);
			h($value);
			$post .= "<input type='hidden' name='{$key}' value='{$value}'>";
		}
		$get = get_get();
		
		//表示
		self::header(array("jquery/jquery.js","basic.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
$(function(){
	set();
});
// --></script>
パスワードと名前が間違っている、または放置によるセッション切れが起こりました。<br>
再度ログインしてください。<br>
<br>
<br>
{$login}
<br>
<form action="./?{$get}" method="POST" class="justify">
	<label>ID</label><input type="text" name="id" value="{$_POST["id"]}" size="20"><br>
	<label>PASS</label><input type="text" name="pass" value="{$_POST["pass"]}" size="20"><br>
	<label>ID、PASSを保存</label><input type="checkbox" name="cookie" value="on" checked="checked"><br>
	<label></label><input type="submit" value="ログイン"><br>
	{$post}
</form>
<br>
<a href="./?mode=System&amp;command=cookie_delete">保存されたID、PASSを削除する</a>
EOF;
		
		self::footer();
		
		exit();
	}
	
}

?>