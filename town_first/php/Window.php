<?php

/***** ウィンドウ *****/
class Window{
	
	static $command = array("chat","mail","item","config","house","status","newChat","newMail","newItem","newConfig","newHouse","newStatus");
	
	//----- チャットウィンドウ -----//
	static function chat(){
		if($_GET["type"] == "new") self::newChat();
		
		print <<<EOF
<form id="chat_form">
<input type="text" name="comment" size="50">
<input type="submit" value="発言">
</form>

<a href="#" id="chat_reload">チャット更新</a>
<label><input type="checkbox" id="chatAutoReload" checked="checked">チャットを自動で更新</label>

<div id="chat_content"></div>
EOF;
		if($_GET["type"] != "new") exit();
	}
	//----- チャットウィンドウ -----//
	static function newChat(){
		View::header(array("jquery/jquery.js","basic.js","chat.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	set();
	\$chat.set();
	\$chat.reload();
});
// --></script>
EOF;
	}
	
	//----- メールウィンドウ -----//
	static function mail(){
		if($_GET["type"] == "new") self::newMail();
		
		print <<<EOF
<form id="mail_form" class="justify">
	<label>送信先</label><input type="text" name="send_name" size="10"><br>
	<label>タイトル</label><input type="text" name="title" size="50"><br>
	<label>内容</label><textarea name="message" rows="7" cols="40"></textarea><br>
	<label></label><input type="submit" value="送信する"><br>
</form>
<br>
<a href="#" id="mail_reload">メールを更新</a><br>
<br>
<div id="mail_content"></div>

EOF;
		if($_GET["type"] != "new") exit();
	}
	//----- メールウィンドウ -----//
	static function newMail(){
		View::header(array("jquery/jquery.js","basic.js","mail.js","tab.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	set();
	\$mail.reload();
	\$mail.set();
});
// --></script>
EOF;
	}
	
	//----- アイテムウィンドウ -----//
	static function item(){
		if($_GET["type"] == "new") self::newItem();
		
		print <<<EOF
<form action="./?mode=Item&amp;command=work" method="POST" id="item_form">
	<div id="item_content"></div>
</form>
EOF;
		if($_GET["type"] != "new") exit();
	}
	//----- アイテムウィンドウ -----//
	static function newItem(){
		View::header(array("jquery/jquery.js","basic.js","item.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	set();
	\$item.reload();
	\$item.set();
});
// --></script>
EOF;
	}
	
	//----- 設定ウィンドウ -----//
	static function config(){
		$my = new Chara();
		
		if($_GET["type"] == "new") self::newConfig();
		
		Config::top();
		
		if($_GET["type"] != "new") exit();
	}
	//----- 設定ウィンドウ -----//
	static function newConfig(){
		View::header(array("jquery/jquery.js","basic.js","config.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	set();
	\$config.set();
});
// --></script>
EOF;
	}
	
	//----- 設定ウィンドウ -----//
	static function house(){
		$my = new Chara();
		
		if($_GET["type"] == "new") self::newHouse();
		
		House::set_top();
		
		if($_GET["type"] != "new") exit();
	}
	//----- 設定ウィンドウ -----//
	static function newHouse(){
		View::header(array("jquery/jquery.js","basic.js","house.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	set();
	\$house.set();
});
// --></script>
EOF;
	}
	
	//----- ステータス表示 -----//
	static function status(){
		$my = new Chara();
		
		$my->set_detail();
		
		//能力バー
		$ability_max = 0;
		foreach(Ini::$ability as $value){
			if($my->$value > $ability_max) $ability_max = $my->$value;
		}
		foreach(Ini::$ability as $value){
			$ability_length[$value] = ($my->$value <= 0 ? 0 : intval(480 * $my->$value / $ability_max));
		}
		
		//IP表示
		foreach($my->ip as $key => $value){
			$ip .= "{$key}（{$value}回）<br>";
		}
		
		//簡易アイテム一覧
		$my->item->load();
		foreach($my->item->items as $row){
			$item_view .= "●{$row["name"]}（{$row["count"]}回）<br>";
		}
		
		if($_GET["type"] == "new") self::newStatus();
		
		print <<<EOF
<div class="tab" title="chara" id="status">
	
	<ul class="top">
		<li title="info" class="select">詳細情報</li>
		<li title="item">アイテム</li>
		<li title="ability">能力値</li>
	</ul>
	<br clear="left">
	
	<div title="info" class="justify first">
		<label>ＩＤ</label>{$my->id}<br>
		<label>名前</label>{$my->name}（{$my->sexJa})<br>
		<label>ＩＰ</label><div style="width:300px;max-height:150px;overflow-y:scroll">{$ip}</div><br>
		<label>ブラウザ</label>{$my->browser}<br>
		<label>仕事</label>{$my->work["now"]}（Lv.{$my->work["level"][$my->work["now"]]}）<br>
		<label>能力</label>{$my->ability}<br>
		<label>称号</label>{$my->title}<br>
		<label>性格</label>{$my->mind}<br>
		<label>持ち金</label>{$my->money}円（総資産：{$my->money_all}円）<br>
		<label>コイン</label>{$my->coin}枚<br>
		<label>健康状態</label>{$my->health}<br>
		<label>体力</label>{$my->energy}/{$my->max_energy}<br>
		<label>精神力</label>{$my->spirit}/{$my->max_spirit}<br>
		<label>身長</label>{$my->height}<br>
		<label>体重</label>{$my->weight}<br>
		<label>体格指数</label>{$my->bmi}<br>
	</div>
	
	<div title="item">
		$item_view
	</div>
	
	<div title="ability">
		<nobr>
		<div class="bar" style="width:{$ability_length['language']}px">語学力：{$my->language}</div>
		<div class="bar" style="width:{$ability_length['math']}px">数学力：{$my->math}</div>
		<div class="bar" style="width:{$ability_length['science']}px">科学力：{$my->science}</div>
		<div class="bar" style="width:{$ability_length['society']}px">社会力：{$my->society}</div>
		<br>
		<div class="bar" style="width:{$ability_length['arm']}px">腕力：{$my->arm}</div>
		<div class="bar" style="width:{$ability_length['leg']}px">脚力：{$my->leg}</div>
		<div class="bar" style="width:{$ability_length['quick']}px">敏捷：{$my->quick}</div>
		<div class="bar" style="width:{$ability_length['soft']}px">柔軟：{$my->soft}</div>
		<br>
		<div class="bar" style="width:{$ability_length['beauty']}px">魅力：{$my->beauty}</div>
		<div class="bar" style="width:{$ability_length['attent']}px">集中力：{$my->attent}</div>
		<div class="bar" style="width:{$ability_length['skill']}px">器用さ：{$my->skill}</div>
		<div class="bar" style="width:{$ability_length['lucky']}px">運：{$my->lucky}</div>
		</nobr>
	</div>
	
	<div title="enq">
		all_enq
	</div>
	
	<div title="ad">
		all_ad
	</div>
	
	<div title="rept">
		rept
	</div>
	
	<ul class="bottom">
		<li title="enq">アンケート</li>
		<li title="ad">宣伝</li>
		<li title="rept">報告板</li>
	</ul>
	
</div>
EOF;
		
		if($_GET["type"] != "new") exit();
	}
	static function newStatus(){
		View::header(array("jquery/jquery.js","basic.js","tab.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	set();
	\$tab.set();
});
// --></script>
EOF;
	}
	
}

?>