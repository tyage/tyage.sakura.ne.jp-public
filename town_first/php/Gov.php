<?php

class Gov{
	static $command = array("top","rank");
	
	static $ranks = array("name" => "名前","no" => "登録日","sex" => "性別","money" => "総資産","coin" => "コイン","work" => "仕事","height" => "身長","weight" => "体重");
	static $logMax = 50;
	
	static function top(){
		$my = Chara::$self;
		
		//ランキング取得
		foreach(self::$ranks as $key => $value){
			$ranks .= "<option value='{$key}'>{$value}</option>\n";
		}
		foreach(Ini::$ability as $value){
			$ranks .= "<option value='{$value}'>".Ini::$ability_ja[$value]."</option>\n";
		}
		
		$query = mq("SELECT * FROM `news` ORDER BY `time` desc LIMIT ".self::$logMax);
		while($row = massoc($query)){
			h($row);
			$news .= "<tr><td>{$row["type"]}</td><td>{$row["time"]}</td><td>{$row["message"]}</td></tr>\n";
		}
		
		//表示
		View::header_def();
		
		print <<<EOF
<form action="./?mode=Gov&amp;command=rank" method="POST">
	<select name="type">{$ranks}</select>
	<input type="submit" value="ランキング">
</form>
<br>
<table class="list">
<thead><tr><th>種類</th><th>時刻</th><th>内容</th></tr></thead>
<tbody>{$news}</tbody>
</table>
<br>
<a href="./?mode=Map&amp;command=top">マップへ戻る</a>
EOF;
	}
	
	static function rank(){
		$my = Chara::$self;
		
		if(!empty($_POST["type"])) $_GET["type"] = $_POST["type"];
		$gov_rank = self::$ranks;
		if(!$gov_rank[$_GET["type"]] and !in_array($_GET["type"],Ini::$ability)) error("タイプがおかしいですねえ。");
		
		//ランクデータ取得
		if($_GET["order"] == "asc") $order = "ASC";
		else $order = "DESC";
		$sql .= implode("`,`",Ini::$ability);
		$query = mq("SELECT `name`,`no`,`time`,`sex`,`money`+`bank` AS `money`,`bank`,`coin`,`work`,`height`,`weight`,`{$sql}` FROM `member` ORDER BY `".($_GET["type"] == "money" ? "money`+`bank" : $_GET["type"])."` {$order} LIMIT 0,100");
		
		//ヘッダー作成
		$uri = "./?mode=Gov&amp;command=rank&amp;type=";
		
		foreach($gov_rank as $key => $value){
			if($_GET["type"] == $key and $_GET["order"] == "desc"){
				$order = "asc";
				$check = "▲";
			}elseif($_GET["type"] == $key){
				$order = "desc";
				$check = "▼";
			}else{
				$check = "";
			}
			$th .= "<th><a href='{$uri}{$key}&amp;order={$order}'>{$value}{$check}</a></th>";
		}
		
		foreach(Ini::$ability as $value){
			if($_GET["type"] == $value and $_GET["order"] == "desc"){
				$order = "asc";
				$check = "▲";
			}elseif($_GET["type"] == $value){
				$order = "desc";
				$check = "▼";
			}else{
				$order = "desc";
				$check = "";
			}
			$th .= "<th><a href='{$uri}{$value}&amp;order={$order}'>".Ini::$ability_ja[$value].$check."</a></th>";
		}
		
		//内容整形
		while($row = massoc($query)){
			$row["work"] = unserialize($row["work"]);
			$row["time"] = unserialize($row["time"]);
			$row["work"] = $row["work"]["now"].($row["work"]["now"] ? "（Lv.{$row["level"][$row["work"]]}）" : "無職");
			$row["sex"] = formatSex($row["sex"]);
			$row["money"] = $row["money"]."円";
			$row["no"] = $row["time"]["start"];
			
			$list .= "<tr>";
			foreach(self::$ranks+Ini::$ability_ja as $key => $value){
				$list .= "<td>{$row[$key]}</td>";
			}
			$list .= "</tr>\n";
		}
		
		//表示
		View::header_def();
		
		print <<<EOF
<br>
<table class="list">
<thead>{$th}</thead>
{$list}
</table>
<br>
<a href="./?mode=Gov&amp;command=top">役場のトップへ</a><br>
<br>
<a href="./?mode=Map&amp;command=top">マップへ戻る</a>
EOF;
	}
	
	static function write_news($type,$message){		
		mq("INSERT INTO `news` (`type`,`message`,`time`) VALUES ('{$type}','{$message}',NOW())");
		
		self::delete_news();
	}
	
	static function delete_news(){
		$query = mq("SELECT `time` FROM `news` ORDER BY `time` desc LIMIT ".self::$logMax.",1");
		$row = massoc($query);
		
		mq("DELETE FROM `news` WHERE `time` < '{$row['time']}' OR `time` = '{$row['time']}'");
	}
}
	
?>
