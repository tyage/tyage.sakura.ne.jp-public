<?php

class School{
	static $command = array("top","in");
	
	static function top(){
		$my = Chara::$self;
		
		$restTime = $my->time["school"] - time();
		$schoolCounter = "<p id='schoolCounter'>残り<span id='schoolCount'></span>秒でできます。</p><br>";
		
		$query = mq("SELECT * FROM `school`");
		while($row = massoc($query)){
			if($row['price'] > $my->money) $disabled2 = " disabled='disabled'";
			foreach(Ini::$ability as $value){
				if($row[$value] == "0") $row[$value] = "";
				$abiBody .= "<td>{$row[$value]}</td>";
			}
			$table .= <<<EOF
<tr><td rowspan="2"><input type="radio" name="class" value="{$row["id"]}"{$disabled2}>{$row["name"]}</td><td>{$row["energy"]}</td><td>{$row["spirit"]}</td>{$abiBody}</tr><tr><td>{$row["price"]}円</td><td>{$row["time"]}分</td><td colspan="13">{$row["explain"]}</td></tr>
EOF;
		}
		
		foreach(Ini::$ability_ja as $value){
			$abiHeader .= "<th>{$value}</th>";
		}
		
		//表示
		View::header_def();
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	\$("#schoolCount").countDown({
		from : {$restTime},
		to : 0,
		level : 1/1000,
		end : function(){
			$("#schoolCounter").remove();
			$("#school-submit").attr("disabled",false);
		}
	});
});
// --></script>

<h1 class="title">学校</h1>

<div class="explain">
	ここでは能力をあげることができます。<br>
	{$schoolCounter}
</div>

<form action="./?mode=School&amp;command=in" method="POST">
<table>
<tr><th rowspan="2">名前</th><th>体力</th><th>精神力</th>{$abiHeader}</tr><tr><th>金額</th><th>時間</th><th colspan="13">説明</th></tr>
{$table}
</table>
<input type="submit" value="授業を受ける" id="school-submit" disabled="disabled">
</form>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	
	static function in(){
		$my = Chara::$self;
		
		$my->from_ok = array("School" => array("top"));
		$my->check_from();
		
		if(empty($_POST["class"])) error("選択肢を選んでください。");
		
		$restTime = $my->time["school"] - time();
		if($restTime > 0) error("後{$restTime}秒待ってください。");
		
		$tmp = array(&$_POST["class"]);
		m($tmp);
		
		$query = mq("SELECT * FROM `school` WHERE `id` = '{$_POST["class"]}'");
		$class = massoc($query);
		if(empty($class)) error("その授業は存在しません。");
		
		if($class["price"] > $my->money) error("お金が足りないです。");
		if($class["energy"] > $my->energy) error("体力が足りないです。");
		if($class["spirit"] > $my->spirit) error("精神力が足りないです。");
		
		//アップデート
		$moneyTmp = $my->money;
		$my->money -= $class["price"];
		$my->energy -= $class["energy"];
		$my->spirit -= $class["spirit"];
		$my->time["school"] = time() + $class["time"]*60;
		array_push($my->update,"money","energy","spirit","time");
		
		//能力
		$my->upAbility($class);
		array_merge($my->update,Ini::$ability);
		
		$maxAbility = 0;
		foreach(Ini::$ability as $value){
			if($class[$value] > 0) $up[$value] = "（+{$class[$value]}）";
			if($my->$value > $maxAbility) $maxAbility = $my->$value;
		}
		foreach(Ini::$ability_type as $type => $array){
			foreach($array as $value){
				$abilityLength = ($my->$value <= 0 ? 0 : intval(500 * $my->$value / $maxAbility));
				$abilityBars .= "<div class='bar' style='width:{$abilityLength}px'>".Ini::$ability_ja[$value]."：{$my->$value}{$up[$value]}</div>";
			}
			$abilityBars .= "<br>";
		}
		
		//表示
		View::header_def();
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	\$("#moneyCounter").countDown({
		from : {$moneyTmp},
		to : {$my->money},
		level : 10,
		timer:10
	});
});
// --></script>

能力をあげました。<br>
<br>
持ち金：<span id="moneyCounter"></span>円<br>
<nobr>
{$abilityBars}
</nobr>
<br>
<a href="./?mode=School&amp;command=top">学校トップ</a><br>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
}

?>