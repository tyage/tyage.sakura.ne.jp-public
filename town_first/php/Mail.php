<?php

class Mail{
	
	static $command_ajax = array("check","reload");
	
	static $logMax = 50;
	
	//----- メール -----//
	static function reload(){
		$my = Chara::$self;
		
		if($_POST["send_name"]){
			$my->entry();
			
			self::send();
		}
		
		print self::load();
		
		$my->time["check"]["mail"] = time();
		array_push($my->update,"time");
	}
	
	//----- メールチェック -----//
	static function check(){
		$my = Chara::$self;
		
		$mail = array();
		$fp = fo("member/{$my->id}/mail.csv","r");
		while($row = assocCsv($fp,"mail")){
			if($my->time["check"]["mail"] > $row["time"] or $row["type"] == "送信") continue;
			else $mail[$row["type"]]++;
		}
		fc($fp);
		
		foreach($mail as $key => $value){
			print $value."件の{$key}があります！<br>";
		}
		
	}
	
	static function send(){
		$my = Chara::$self;
		
		if(empty($_POST["title"])) $_POST["title"] = "無題";
		if(empty($_POST["message"])) error("メッセージがありません。");
		
		//送信先決定
		if($_POST["send_list"]){
			$send_name = is_array($_POST["send_list"]) ? $_POST["send_list"] : array($_POST["send_list"]);
			foreach($send_name as $value){
				if($value == $my->name) continue;
				$names[] = $value;
			}
			$send_name = $names;
		}elseif($_POST["send_name"]){
			if($_POST["send_name"] == $my->name) error("自分に送信しないでください！");
			$send_name = $_POST["send_name"];
		}else{
			error("送信先を選んでください。");
		}
		
		if(!$send_id = Chara::get_id($send_name)) error("送信先が見つかりません！");
		
		//送信
		if(is_array($send_id)){
			foreach($send_id as $id){
				self::write($id,array("type" => "受信","name" => $my->name,"title" => $_POST["title"],"message" => $_POST["message"]));
			}
			self::write($my->id,array("type" => "送信","name" => implode(",",$send_name),"title" => $_POST["title"],"message" => $_POST["message"]));
		}else{
			self::write($send_id,array("type" => "受信","name" => $my->name,"title" => $_POST["title"],"message" => $_POST["message"]));
			self::write($my->id,array("type" => "送信","name" => $send_name,"title" => $_POST["title"],"message" => $_POST["message"]));
		}
		
		h($_POST);
		print <<<EOF
<h3>メールを送信しました。</h3>
<h4>{$_POST["title"]}</h4>
<pre>{$_POST["message"]}</pre><br>
<br>
EOF;
	}
	
	static function delete(){
		$my = Chara::$self;
		
		if(!$_POST["no"]) error("消すメッセージを指定してください。");
		
		if(is_array($_POST["no"])) $no = $_POST["no"];
		else $no[] = $_POST["no"];
		
		$fp = fo("member/{$my->id}/mail.csv","r+");
		while($row = assocCsv($fp,"mail")){
			if(in_array($row["no"],$no)) continue;
			else $newData[] = $row;
		}
		foreach($newData as $row){
			putCsv($fp,"mail",$row);
		}
		fc($fp);
		
		View::header_def();
		
		print "メールを消しました。";
	}
	
	static function load(){
		$my = Chara::$self;
		
		$fp = fo("member/{$my->id}/mail.csv","r");
		while($row = assocCsv($fp,"mail")){
			$row["time"] = mkdate($row["time"]);
			h($row);
			$message = "<tr class='mes_detail' title='{$row["no"]}'><td>{$row["name"]}</td><td>{$row["title"]}</td><td>{$row["time"]}</td></tr>\n<tr class='mes'><td colspan='3'><pre>{$row["message"]}</pre></td></tr>\n";
			if($row["type"] == "受信"){
				$receive .= $message;
			}elseif($row["type"] == "送信"){
				$send .= $message;
			}elseif($row["type"] == "報告"){
				$report .= $message;
			}else{
				$receive .= $message;
			}
		}
		fc($fp);
		
		$mail = <<<EOF
<div class="tab" title="chara" id="mailbox">
	
	<ul class="top">
		<li title="receive" class="select">受信BOX</li>
		<li title="send">送信BOX</li>
		<li title="report">報告BOX</li>
	</ul>
	<br clear="left">
	
	<div title="receive" class="first">
		<table class="mail">
		<caption>受信BOX</caption>
		<thead><tr class="mes"><th>送信者</th><th>タイトル</th><th>日付</th></tr></thead>
		<tbody>{$receive}</tbody>
		</table>
	</div>
	
	<div title="send">
		<table class="mail">
		<caption>送信BOX</caption>
		<thead><tr><th>送信先</th><th>タイトル</th><th>日付</th></tr></thead>
		<tbody>{$send}</tbody>
		</table>
	</div>
	
	<div title="report">
		<table class="mail">
		<caption>報告BOX</caption>
		<thead><tr><th>送信者</th><th>タイトル</th><th>日付</th></tr></thead>
		<tbody>{$report}</tbody>
		</table>
	</div>
	
</div>
EOF;
		return $mail;
		
	}
	
	static function write($send_id,$data){
		$ini = Ini::load("mail_no");
		$ini["mail_no"]++;
		Ini::save($ini);
		
		$data["no"] = $ini["mail_no"];
		$data["time"] = mktime();
		$rows[] = $data;
		
		$file = "member/{$send_id}/mail.csv";
		$fp = fo($file,"r");
		while($row = assocCsv($fp,"mail")){
			$rows[] = $row;
		}
		fc($fp);
		
		array_splice($rows,self::$logMax + 1);
		
		$fp = fo($file,"w");
		foreach($rows as $row){
			putCsv($fp,"mail",$row);
		}
		fc($fp);
		
	}
	
}

?>