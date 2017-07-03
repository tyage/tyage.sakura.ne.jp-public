<?php

class Bbs{
	static $command = array("top","in");
	static $command_ajax = array("res","create");
	
	static $types = array("雑談","報告","意見","その他");
	static $logMax = 100;
	static $pageMax = 30;
	
	static function top(){
		$my = Chara::$self;
		
		if($my->admin){$type_select .= "<option value='管理'>管理</option>";}
		$query = mq("SELECT * FROM `bbs` WHERE `type` = '普通' AND `type_sub` IN ('管理') LIMIT 10");
		while($row = massoc($query)){
			h($row);
			$table .= "<tr><td>{$row["type_sub"]}</td><td><a href='./?mode=Bbs&amp;command=in&amp;no={$row["no"]}'>{$row["title"]}</a></td><td>{$row["author"]}</td><td>{$row["last"]}（{$row["last_name"]}）</td><td>{$row["res"]}</td></tr>\n";
		}
		
		m(self::$types);
		foreach(self::$types as $value){
			$type_select .= "<option value='{$value}'>{$value}</option>";
		}
		$query = mq("SELECT * FROM `bbs` WHERE `type` = '普通' AND `type_sub` IN (".makeValues(self::$types).") LIMIT 50");
		while($row = massoc($query)){
			sanitize($row);
			h($row);
			$table .= "<tr><td>{$row["type_sub"]}</td><td><a href='./?mode=Bbs&amp;command=in&amp;no={$row["no"]}'>{$row["title"]}</a></td><td>{$row["author"]}</td><td>{$row["last"]}（{$row["last_name"]}）</td><td>{$row["res"]}</td></tr>\n";
		}
		
		View::header_def();
		
		print <<<EOF
<table class="list">
<thead><tr><th>種類</th><th>タイトル</th><th>作成者</th><th>最終更新</th><th>返信件数</th></tr></thead>
<tbody>{$table}</tbody>
</table>
<br>
<form action="./?mode=Bbs&amp;command=create" method="POST" class="ajax reset">
<fieldset>
	<legend>新規作成</legend>
	
	<div class="justify">
		<label>タイトルと種類</label><input type="text" name="title" size="30"><select name="type">{$type_select}</select><br>
		<label>内容</label><textarea name="message" cols="50" rows="10"></textarea><br>
		<label></label><input type="submit" value="新規作成">
	</div>
</fieldset>
</form>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	
	static function in(){
		$my = Chara::$self;
		
		$bbs = self::load($_GET["no"]);
		
		$fp = fo("log/bbs/{$_GET["no"]}.csv","r");
		for($i=0;$i<self::$pageMax;$i++){
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
		
		View::header_def();
		
		print <<<EOF
<br>
<div id="bbs">
{$messages}
</div>
<br>
<form action="./?mode=Bbs&amp;command=res&amp;no={$_GET["no"]}" method="POST" class="ajax reset">
<fieldset>
	<legend>投稿</legend>
	
	<div class="justify">
		<label>内容</label><textarea cols="30" rows="5" name="message"></textarea><br>
		<label></label><input type="submit" value="投稿">
	</div>
</fieldset>
</form>
<br>
<a href="./?mode=Bbs&amp;command=top">掲示板一覧に戻る</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	
	static function res(){
		$my = Chara::$self;
		
		i($_GET["no"]);
		$bbs = self::load($_GET["no"]);
		
		if(!$_POST["message"]) error("メッセージがありません。");
		
		$query = mq("SELECT * FROM `bbs` WHERE `type` = '普通' AND `no` = '{$_GET["no"]}'");
		$row = massoc($query);
		$row["res"]++;
		if($row["res"] > self::$logMax) error("投稿件数が最大です！");		
		
		$fp = fo("log/bbs/{$_GET["no"]}.csv","a");
		putCsv($fp,"bbs",array("no" => $row["res"],"name" => $my->name,"id" => $my->id,"message" => $_POST["message"],"time" => time()));
		fc($fp);
		
		$tmp = array(&$row["res"],&$my->name);
		m($tmp);
		mq("UPDATE `bbs` SET `res` = '{$row["res"]}',`last` = NOW(),`last_name` = '{$my->name}' WHERE `type` = '普通' AND `no` = '{$_GET["no"]}'");
		
		$money = rand(1000,5000);
		$my->money += $money;
		array_push($my->update,"money");
		
		print "投稿完了！<br>{$money}円をゲットしました。";
	}
	
	static function create(){
		$my = Chara::$self;
		
		if(!$_POST["title"]) error("タイトルがありません。");
		if(!$_POST["message"]) error("メッセージがありません。");
		if(!($_POST["type"] == "管理" and $my->admin) and !in_array($_POST["type"],self::$types)) error("タイプがおかしいです");
		
		$ini = Ini::load("bbs_no");
		$ini["bbs_no"]++;
		Ini::save($ini);
		
		$fp = fo("log/bbs/{$ini["bbs_no"]}.csv","w");
		putCsv($fp,"bbs",array("no" => 0,"title" => $_POST["title"],"name" => $my->name,"id" => $my->id,"message" => $_POST["message"],"time" => time()));
		fc($fp);
		
		$tmp = array(&$_POST["type"],&$_POST["title"],&$my->name);
		m($tmp);
		mq("INSERT INTO `bbs` (`no`,`type`,`type_sub`,`title`,`first`,`author`,`res`,`last`,`last_name`) VALUES ('{$ini["bbs_no"]}','普通','{$_POST["type"]}','{$_POST["title"]}',NOW(),'{$my->name}',0,NOW(),'{$my->name}')");
		
		print "新しくスレを立てました！";
	}
	
	private static function load($no){
		i($no);
		$query = mq("SELECT * FROM `bbs` WHERE `no` = '{$no}' AND `type` = '普通'");
		$bbs = massoc($query);
		if(!$bbs){error("そのスレは見つかりませんでした。");}
		
		return $bbs;
	}
	
}
?>