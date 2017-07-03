<?php

/***** チャット *****/
class Chat{

	static $command_ajax = array("view","submit");

	static $logMax = 50;

	//----- チャット表示 -----//
	static function view(){
		$my = Chara::$self;

		print self::load();
	}

	//----- チャット送信 -----//
	static function submit(){
		$my = Chara::$self;

		if(!empty($_POST["comment"])){
			//コメント
			self::comment($my->id,$my->name,$_POST["comment"]);

			//データ更新
			$money_tmp = $my->money;
			$money = rand(10,100);
			$my->money += $money;
			array_push($my->update,"money");

			print "<div class='report'>{$money}円ゲットしました。<br>持ち金　：　{$money_tmp}円 + {$money}円 = {$my->money}円<br><br></div>";
		}

		print self::load();
	}

	//----- チャットにコメント -----//
	static function comment($id,$name,$comment){
		//整形
		$tmp = array(&$id,&$name,&$comment);
		m($tmp);

		//長さ検査
		if(strlen($comment) > 200) error("コメントが200バイトを超えています。（".strlen($comment))."文字）";

		//連続投稿検査
		$result = mq("SELECT * FROM `chat` ORDER BY `time` desc LIMIT 0,1");
		$row = massoc($result);
		if($row['id'] == $id and $row['comment'] == $comment and $id) error("連続投稿やめちくりー");

		//挿入
		mq("INSERT INTO `chat` (`id`,`name`,`comment`,`time`) VALUES ('{$id}','{$name}','{$comment}',NOW())");

		//最後の発言を消す（最後の発言時間を取って、それより前の発言を消す）
		$max = self::$logMax;
		$query = mq("SELECT `time` FROM `chat` ORDER BY `time` desc LIMIT {$max},1");
		$row = massoc($query);

		mq("DELETE FROM `chat` WHERE `time` < '{$row['time']}' OR `time` = '{$row['time']}'");
	}

	//----- チャットデータロード -----//
	static function load(){
		$max = self::$logMax;
		$result = mq("SELECT * FROM `chat` ORDER BY `time` desc LIMIT {$max}");
		while($row = massoc($result)){
			$tmp = array(&$row['name'],&$row['comment']);
			h($tmp);
			if(empty($row["name"])) $row["name"] = "報告";
			$chat .= "<p><label title='{$row['name']}'>".mb_substr($row['name'],0,5,"UTF-8").(mb_strlen($row['name'],"UTF-8") > 5 ? "..." : "")."</label> &gt; {$row['comment']}（{$row["time"]}）</p><br>";
		}

		return "<div class='justify' id='chat'>{$chat}</div>";
	}

}

?>