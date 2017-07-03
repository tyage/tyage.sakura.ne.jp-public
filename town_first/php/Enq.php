<?php

/******

アンケート

+++ methode +++
	top    : トップビューワ
	in     : 投稿、閲覧ビューワ
	vote   : 投稿
	create : 新規作成
	set    : 設定
	delete : 消去

******/

class Enq{
	static $command = array("top","in","vote","create","set","delete");
	
	/*** トップ
	
	+++ GET +++
	no : ページNO
	
	***/
	static function top(){
		$my = Chara::$self;
		
		//--- 自分のアンケートの情報取得 ---//
		$data = self::load($my->name,false);
		if($data["name"] == $my->name){
			h($data);
			
			$fp = fo("member/{$my->id}/enq.csv","r");
			while($row = assocCsv($fp,"enq")){
				$tmp = array(&$row["title"],&$row["creater"]);
				h($tmp);
				$option .= "<option value='{$row["title"]}'>{$row["title"]}（{$row["creater"]}作）：".count(unserialize($row["vote"]))."票</option>";
			}
			fc($fp);
			
			if($data["comment"]) $check = " checked='checked'";
			
			$myenq = <<<EOF
<form action="./?mode=Enq&amp;command=set" method="POST">
<fieldset>
	<legend>設定</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="50" value="{$data["title"]}" /><br>
		<label>メッセージ</label><textarea cols="50" rows="5" name="message">{$data["message"]}</textarea><br>
		<label>選択可能数</label><input type="text" name="choice" size="3" value="{$data["choice"]}" maxlength="3" /><br>
		<label>追加可能数</label><input type="text" name="add" size="3" value="{$data["add"]}" maxlength="3" /><br>
		<label>コメント可能</label><input type="checkbox" name="comment" value="1"{$check} /><br>
	</div>
	
</fieldset>
<br>
<fieldset>
	<legend>選択肢</legend>
	
	<div class="justify">
		<label>削除する選択肢</label><select multiple="multiple" name="delete_choice[]">{$option}</select><span id="remove_cancel">選択解除</span><br>
		<label>追加する選択肢</label><div><a href="#" id="add_choice">追加する</a><br></div><br>
	</div>
</fieldset>
<br>
<input type="submit" value="設定" />
</form>
<br>
<a href="./?mode=Enq&amp;command=delete">アンケートを消す</a><br>
<br>
EOF;
		}else{
			//- まだ作っていない場合 -//
			$myenq = <<<EOF
<form action="./?mode=Enq&amp;command=create" method="POST">
<fieldset>
	<legend>新規作成</legend>
	
	<div class="justify">
		<label>タイトル</label><input type="text" name="title" size="50" /><br>
		<label>メッセージ</label><textarea cols="50" rows="5" name="message"></textarea><br>
		<label>選択可能数</label><input type="text" name="choice" size="3" maxlength="3" /><br>
		<label>追加可能数</label><input type="text" name="add" size="3" maxlength="3" /><br>
		<label>コメント可能</label><input type="checkbox" name="comment" value="1" checked="checked" /><br>
	</div>
	
</fieldset>

<input type="submit" value="作成" />
</form>
EOF;
		}
		
		//--- NOが指定されていなければ1ページ目とする ---//
		i($_GET["no"]);
		if($_GET["no"] <= 0) $_GET["no"] = 1;
		
		//--- データ数取得、ページナビ作成 ---//
		$ini = Ini::load("enq_no");
		if($ini["enq_no"] > 50*$_GET["no"]) $next = "<a href='./?mode=Enq&amp;command=top&amp;no=".($_GET["no"] + 1)."'>次に進む</a>";
		if($_GET["no"] > 1) $back = "<a href='./?mode=Enq&amp;command=top&amp;no=".($_GET["no"] - 1)."'>前に戻る</a>";
		
		//--- データ取得、列作成 ---//
		$query = mq("SELECT `name`,`title`,`first` FROM `enq` ORDER BY `first` DESC LIMIT ".( ($_GET["no"]-1)*50 > 0 ? ($_GET["no"]-1)*50 : 0 ).",".($_GET["no"]*50));
		while($row = massoc($query)){
			$tmp = array(&$row["name"],&$row["title"]);
			h($tmp);
			$tr .= "<tr><td><a href='./?mode=Enq&amp;command=in&amp;name=".urlencode($row["name"])."'>{$row["title"]}</a></td><td>{$row["name"]}</td><td>{$row["first"]}</td></tr>";
		}
		
		//--- 表示 ---//
		View::header_def("enq.js");
		
		print <<<EOF

<script type="text/javascript"><!--
$(function(){
	\$enq.set();
});
// --></script>

{$myenq}

<table class="list">
<thead><tr><th>タイトル</th><th>作成者</th><th>開始日</th></tr></thead>
<tbody>{$tr}</tbody>
</table>
<br>
<br>
{$back}　　　{$next}
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
		
	}
	
	
	/*** 投稿、閲覧画面に入室
	
	+++ GET +++
		name : アンケート作成者
	
	***/
	static function in(){
		$my = Chara::$self;
		$max = $create = 0;$tmp = array();
		
		$data = self::load($_GET["name"]);
		
		$fp = fo("member/{$data["id"]}/enq.csv","r");
		while($row = assocCsv($fp,"enq")){
			if($row["creater"] == $my->name) $create++;
			
			$row["vote"] = unserialize($row["vote"]);
			
			$count = count($row["vote"]);
			if($count > $max) $max = $count;
			
			$tmp[] = $row;
		}
		if($max <= 0) $max = 1;
		foreach($tmp as $row){
			$count = count($row["vote"]);
			$length = intval(500 * $count / $max);
			
			$checked = ( in_array($my->name,$row["vote"]) ? " checked='checked'" : "" );
			
			$tmp = array(&$row["title"],&$row["creater"]);
			h($row);
			
			$tr .= "<tr><td><label><input type='checkbox' name='choice[]' value='{$row["title"]}'{$checked}>{$row["title"]}</label></td><td><div class='bar' style='width:{$length}px'>{$count}</div></td><td>{$row["creater"]}</td></tr>";
		}
		fc($fp);
		
		//--- 表示 ---//
		//- 選択肢追加 -//
		$addable = $data["add"] - $create;
		if($addable > 0){
			$add = "<fieldset><legend>追加する選択肢（{$addable}個まで可能）</legend>";
			for($i = 0;$i < $addable;$i++) $add .= "<input type='text' name='add[]' size='50'><br>";
			$add .= "</fieldset>";
		}
		
		//- HTMLサニタイズ -//
		$tmp = array(&$data["name"],&$data["title"],&$data["message"]);
		h($tmp);
		
		//- エンコード済み名前 -//
		$name = urlencode($data["name"]);
		
		View::header_def();
		
		print <<<EOF
<form action="./?mode=Enq&amp;command=vote&amp;name={$name}" method="POST">
	{$data["name"]}さんのアンケート（{$data["choice"]}個選択可能）<br>
	<br>
	<h3>{$data["title"]}</h3>
	<pre>{$data["message"]}</pre><br>
	
	<table class="list">
	<thead><tr><th>選択肢</th><th>投票数</th><th>作成者</th></tr></thead>
	<tbody>{$tr}</tbody>
	<tfoot><tr><td colspan="3">
		{$add}
		<input type="submit" value="投票">
	</td></tr></tfoot>
	</table>
	
</form>
<br>
<br>
<a href="./?mode=Enq&amp;command=top">アンケートトップ</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
		
	}
	
	
	/*** 投稿
	
	+++ GET +++
		name : アンケート作成者
	
	+++ POST +++
		choice : 選択肢
	
	***/
	static function vote(){
		$my = Chara::$self;
		$choice = 0;$new_rows = array();$add = false;
		
		if(empty($_POST["choice"])) $_POST["choice"] = array();
		if(empty($_POST["add"])) $_POST["add"] = array();
		
		$data = self::load($_GET["name"]);
		
		foreach($_POST["add"] as $title){
			if(empty($title)) continue;
			$add = true;
			$new_rows[$title] = array("title" => $title,"vote" => array(),"creater" => $my->name);
			$create++;
		}
		
		$fp = fo("member/{$data["id"]}/enq.csv","r");
		while($row = assocCsv($fp,"enq")){
			if($row["creater"] == $my->name) $create++;
			
			$row["vote"] = unserialize($row["vote"]);
			
			$new_vote = array();
			foreach($row["vote"] as $name){
				if($my->name == $name) continue;
				$new_vote[] = $name;
			}
			$row["vote"] = $new_vote;
			
			if(in_array($row["title"],$_POST["choice"])){
				$row["vote"][] = $my->name;
				$choice++;
			}
			
			$new_rows[$row["title"]] = $row;
		}
		fc($fp);
		
		if($choice > $data["choice"]) error("選択しすぎです。");
		if($create > $data["add"] and $add) error("追加しすぎです。");
		
		$fp = fo("member/{$data["id"]}/enq.csv","w");
		foreach($new_rows as $row){
			$row["vote"] = serialize($row["vote"]);
			putCsv($fp,"enq",$row);
		}
		fc($fp);
		
		h($data["name"]);
		$name = urlencode($data["name"]);
		
		View::header_def();
		
		print <<<EOF
投稿完了！<br>
<br>
<a href="./?mode=Enq&amp;command=in&amp;name={$name}">このアンケートに戻る</a><br>
<a href="./?mode=Enq&amp;command=top">アンケートトップ</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	/*** 新規作成
	
	+++ POST +++
		title   : タイトル
		message : メッセージ（詳細）
		choice  : 選択可能数
		add     : 追加可能数
		comment : コメント可・不可
	
	***/
	static function create(){
		$my = Chara::$self;
		
		$tmp = array(&$_POST["choice"],&$_POST["add"],&$_POST["comment"]);
		i($tmp);
		
		//--- 検査 ---//
		if(!$_POST["title"] or !$_POST["message"] or !$_POST["choice"] < 0 or !$_POST["add"] < 0) error("設定がおかしいですね。");
		$data = self::load($my->name,false);
		if($data["name"] == $my->name) error("既にアンケートがあります。<br>それを消してからにしてください。");
		
		//--- DBに追加 ---//
		$tmp = array(&$my->id,&$my->name,&$_POST["title"],&$_POST["message"]);
		m($tmp);
		
		mq("INSERT INTO `enq` (`id`,`name`,`title`,`message`,`first`,`choice`,`add`,`comment`) VALUES ('{$my->id}','{$my->name}','{$_POST["title"]}','{$_POST["message"]}',NOW(),{$_POST["choice"]},{$_POST["add"]},".( $_POST["comment"] == 0 ? 0 : 1 ).")");
		
		//--- アンケート数更新 ---//
		$ini = Ini::load("enq_no");
		$ini["enq_no"]++;
		Ini::save($ini);
		
		//--- ファイル更新（または追加） ---//
		$fp = fo("member/{$my->id}/enq.csv","w");
		fc($fp);
		
		//--- 表示 ---//
		
		View::header_def();
		
		print <<<EOF
作成完了！<br>
<br>
<a href="./?mode=Enq&amp;command=top">アンケートトップ</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	/*** 設定
	
	+++ POST +++
		title         : タイトル
		message       : メッセージ（詳細）
		choice        : 選択可能数
		add           : 追加可能数
		comment       : コメント可・不可
		
		add_choice    : 追加選択肢
		delete_choice : 削除選択肢
		
	***/
	static function set(){
		$my = Chara::$self;
		
		$choice = array();
		
		$tmp = array(&$_POST["choice"],&$_POST["add"],&$_POST["comment"]);
		i($tmp);
		
		if(!$_POST["title"] or !$_POST["message"] or $_POST["choice"] < 0 or $_POST["add"] < 0) error("設定がおかしいですね。");
		
		if(!is_array($_POST["add_choice"])) $_POST["add_choice"] = array();
		if(!is_array($_POST["delete_choice"])) $_POST["delete_choice"] = array();
		
		$tmp = array(&$my->id,&$_POST["title"],&$_POST["message"]);
		m($tmp);
		
		mq("UPDATE `enq` SET `title` = '{$_POST["title"]}',`message` = '{$_POST["message"]}',`choice` = {$_POST["choice"]},`add` = {$_POST["add"]},`comment` = ".( $_POST["comment"] == 0 ? 0 : 1 )." WHERE `id` = '{$my->id}'");
		
		foreach($_POST["add_choice"] as $title){
			if(empty($title)) continue;
			
			$choice[$title] = array("title" => $title,"vote" => serialize(array()),"creater" => $my->name);
		}
		
		$fp = fo("member/{$my->id}/enq.csv","r");
		while($row = assocCsv($fp,"enq")){
			if(in_array($row["title"],$_POST["delete_choice"])) continue;
			$choice[$row["title"]] = $row;
		}
		fc($fp);
		
		$fp = fo("member/{$my->id}/enq.csv","w");
		foreach($choice as $row){
			putCsv($fp,"enq",$row);
		}
		fc($fp);
		
		View::header_def();
		
		print <<<EOF
設定完了！<br>
<br>
<a href="./?mode=Enq&amp;command=top">アンケートトップ</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	/*** 消去 ***/
	static function delete(){
		$my = Chara::$self;
		
		m($my->id);
		mq("DELETE FROM `enq` WHERE `id` = '{$my->id}'");
		
		$fp = fo("member/{$my->id}/enq.csv","w");
		fc($fp);
		
		View::header_def();
		
		print <<<EOF
消去完了！<br>
<br>
<a href="./?mode=Enq&amp;command=top">アンケートトップ</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//----- アンケート情報読み込み -----//
	private static function load($name,$check = true){
		$query = mq("SELECT * FROM `enq` WHERE `name` = '{$name}'");
		$data = massoc($query);
		if($data["name"] != $name and $check) error("そのアンケートは見つかりませんでした。");
		
		return $data;
	}
}

?>