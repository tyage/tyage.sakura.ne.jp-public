<?php

class Work{
	static $command = array("hello","go","company");
	static $command_ajax = array("change");
	
	static $interval = 10;//（分）
	static $point = 100;
	static $levelBonus = 1.1; //一レベル上がるごとに貰える給料や次レベルまでのポイントの倍率
	static $masterLevel = 20;
	static $pay = array("h" => 1,"d" => 3,"w" => 7,"m" => 10,"y" => 30);
	static $pay_ja = array("h" => "時給","d" => "日給","w" => "週給","m" => "月給","y" => "年給");
	
	static function hello(){
		$my = Chara::$self;
		
		$my->set_detail();
		
		//ヘッダーテーブル作成
		foreach(Ini::$ability_ja as $value){
			$td_head .= "<td>{$value}</td>";
		}
		foreach(Ini::$ability as $value){
			$td_head_my .= "<td>{$my->$value}</td>";
		}
		$table_head .= "<tr><td colspan='30'><input type='submit' value='この職に就く'></td></tr><tr><td>職業</td><td>体格指数</td><td>性別</td><td>初任給</td><td>ボーナス</td><td>支払い</td><td>体力</td><td>精神力</td>{$td_head}</tr><tr><td>あなたのパラ</td><td>{$my->bmi}</td><td>{$my->sexJa}</td><td>-</td><td>-</td><td>-</td><td>{$my->energy}</td><td>{$my->spirit}</td>{$td_head_my}</tr>";
		
		//ボディーテーブル作成
		$query = mq("SELECT * FROM `work`");
		$i = 0;
		while($row = massoc($query)){
			if($row["before"] and $my->work["level"][$row["before"]] < self::$masterLevel) continue;
			if($row["sex"] and $row["sex"] != $my->sex) continue;
			
			if($i++ % 10 == 0){
				$table .= $table_head;
			}
			
			$disabled = "";
			$td_body = "";
			$bmi_disable = "";
			
			if($row["name"] == $my->work["now"]) $disabled = " disabled='disabled'";
			
			if(($row["bmi_min"] and $row["bmi_min"] > $my->bmi) or ($row["bmi_max"] and $my->bmi > $row["bmi_max"])){
				$bmi_disable = " class='disable'";
				$disabled = " disabled='disabled'";
			}
			
			if($row["bmi_min"] == "0") $row["bmi_min"] = "";
			if($row["bmi_max"] == "0") $row["bmi_max"] = "";
			
			foreach(Ini::$ability as $value){
				if($row[$value] == "0") $row[$value] = "";
				
				if($row[$value] > $my->$value){
					$disabled = " disabled='disabled'";
					$td_body .= "<td class='disable'>{$row[$value]}</td>";
				}else{
					$td_body .= "<td>{$row[$value]}</td>";
				}
			}
			$pay = self::$pay_ja[$row["pay"]];
			$table .= "<tr><td><input type='radio' name='work' value='{$row["name"]}'{$disabled}>{$row["name"]}</td><td{$bmi_disable}>{$row["bmi_min"]}～{$row["bmi_max"]}</td><td>{$row["sex"]}</td><td>{$row["first"]}</td><td>{$row["bonus"]}倍</td><td>{$pay}</td><td>{$row["energy"]}</td><td>{$row["spirit"]}</td>{$td_body}</tr>";
		}
		
		//表示
		View::header_def();
		
		print <<<EOF
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
<br>
<form action="./?mode=Work&amp;command=change" method="POST" class="ajax">
<table class="list"><tbody>{$table}</tbody></table>
</form>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	static function change(){
		$my = Chara::$self;
		
		//入力簡易検査
		if(!$_POST["work"]) error("仕事を選べ");
		if($_POST["work"] == $my->work["now"]) error("今と同じ仕事は選べません。");
		
		//職業データ取得
		m($_POST["work"]);
		$query = mq("SELECT * FROM `work` WHERE `name` = '{$_POST["work"]}'");
		$row = massoc($query);
		if(!$row) error("そんな職業なかったっす。");
		
		//BMIなど取得
		$my->set_detail();
		
		//検査
		if(($row["bmi_min"] and $row["bmi_min"] > $my->bmi) or ($row["bmi_max"] and $my->bmi > $row["bmi_max"])) error("体格指数が合っていません。");
		if($row["sex"] and $row["sex"] != $my->sex) error("性別がおかしいです。");
		foreach(Ini::$ability as $value){
			if($row[$value] > $my->$value) error("経験地が足りません。");
		}
		if($row["before"] and $my->work["level"][$row["before"]] < self::$masterLevel) error("まだその職にはつけません。");
		
		$oldJob = $my->work["now"];
		$my->work["level"][$_POST["work"]] = 0;
		$my->work["point"][$_POST["work"]] = 0;
		$my->work["count"][$_POST["work"]] = 0;
		$my->work["now"] = $_POST["work"];
		$my->update[] = "work";
		
		print <<<EOF
新しい仕事に就きました。<br>
{$oldJob}　→　{$my->work["now"]}
EOF;
	}
	
	static function go(){
		$my = Chara::$self;
		
		if($my->health < 0) error("仕事ができるような体ではありません、");
		
		$restTime = $my->work["time"] + self::$interval*60 - time();
		if($restTime > 0) error("仕事は後{$restTime}秒できません。");
		
		//職業データ取得
		m($my->work["now"]);
		$query = mq("SELECT * FROM `work` WHERE `name` = '{$my->work["now"]}'");
		$data = massoc($query);
		
		//経験値取得
		$point = $my->health + rand($my->health / 2 * -1,$my->health / 2);
		$my->work["point"][$my->work["now"]] += $point;
		$nextLevel = intval(self::$point * pow(self::$levelBonus,$my->work["level"][$my->work["now"]]));
		
		if($my->work["point"][$my->work["now"]] >= $nextLevel){
			$my->work["level"][$my->work["now"]]++;
			$my->work["point"][$my->work["now"]] -= $nextLevel;
			$message .= "レベルＵＰ！（Lv.".($my->work["level"][$my->work["now"]]-1)."→Lv.{$my->work["level"][$my->work["now"]]}）<br>";
		}
		
		//仕事回数増加
		$my->work["count"][$my->work["now"]]++;
		$nextPay = self::$pay[$data["pay"]] - ($my->work["count"][$my->work["now"]] % self::$pay[$data["pay"]]);
		if($nextPay == self::$pay[$data["pay"]]){
			$money = intval($data["first"] * pow(self::$levelBonus,$my->work["level"][$my->work["now"]]));
			$my->money += $money;
			array_push($my->update,"money");
			$message .= $money."円ゲット！<br>";
		}
		
		$my->work["time"] = time();
		$my->update[] = "work";
		
		//表示
		View::header_def();
		
		print <<<EOF
経験値{$point}ポイントゲット！<br>
{$message}<br>
給料まで後{$nextPay}回です。<br>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	static function company(){
		$my = Chara::$self;
		
		//職業データ取得
		m($my->work["now"]);
		$query = mq("SELECT * FROM `work` WHERE `name` = '{$my->work["now"]}'");
		$work = massoc($query);
		
		if($work["place"] != "会社") error("会社員の方以外はお帰りください。");
		
		self::go();
	}
}

?>