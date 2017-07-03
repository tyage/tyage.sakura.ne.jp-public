<?php

class Bank{
	static $command = array("top","show");
	static $command_ajax = array("money");
	
	//銀行
	static $logMax = 50;
	static $interestRate = 0.01;
	
	//----- トップ -----//
	static function top(){
		$my = Chara::$self;
		
		//--- 表示 ---//
		View::header_def("bank.js");
		
		print <<<EOF
<script type="text/javascript"><!--
\$(function(){
	\$bank.set();
});
// --></script>

持ち金：{$my->money}円<br>
<br>
<form action="./?mode=Bank&amp;command=money" method="POST" id="bank_form" class="ajax reset">
<fieldset>
	<legend>普通預金</legend>
	
	残額：{$my->bank}円<br>
	<br>
	<div class="justify">
		<label>操作</label><select name="work"><option value="振込み">振込み</option><option value="引き出し">引き出し</option></select><br>
		<label>金額</label><div><input type="text" name="money" size="10">円 / <input type="checkbox" name="all" value="on">全額</div><br>
		<label></label><input type="submit" value="決定"><br>
	</div>
	<br>
	<a href="./?mode=Bank&amp;command=show">入出金明細を見る</a><br>
	<br>
</fieldset>
</form>
<br>
<a href="./?mode=Map&amp;command=top">マップへ戻る</a>
EOF;
	}
	
	//----- 入出金 -----//
	static function money(){
		$my = Chara::$self;
		
		//--- 検査、データ更新 ---//
		i($_POST["money"]);
		
		switch($_POST["work"]){
			case "振込み":
				$money = $_POST["all"] ? $my->money : $_POST["money"];
				if($money <= 0 or $money > $my->money) error("数値がおかしい、または持ち金が足りません。");
				break;
				
			case "引き出し":
				$money = $_POST["all"] ? $my->bank : $_POST["money"];
				if($money <= 0 or $money > $my->bank) error("数値がおかしい、または銀行にそんなにお金がありません。");
				$money = -$money;
				break;
				
      default:
      	error("なにがしたいのか分かりません。");
				break;
		}
				
		$my->money -= $money;
		$my->bank += $money;
		
		array_push($my->update,"bank","money");
		
		//--- 通帳更新 ---//
		self::write($my->id,array("work" => $_POST["work"],"money" => $money));
		
		print abs($money)."円{$_POST["work"]}しました。";
	}
	
	//----- 通帳表示 -----//
	static function show(){
		$my = Chara::$self;
		
		$bank_tmp = $my->bank;
		$fp = fo("member/{$my->id}/bank.csv","r");
		while($row = assocCsv($fp,"bank")){
			if($row["money"] < 0){
				$row["money"] = abs($row["money"]);
				$tr .= "<tr><td>{$row["work"]}</td><td></td><td>".abs($row["money"])."</td><td>{$bank_tmp}</td><td>{$row["time"]}</td></tr>\n";
				$bank_tmp += $row["money"];
			}elseif($row["money"] > 0){
				$tr .= "<tr><td>{$row["work"]}</td><td>".abs($row["money"])."</td><td></td><td>{$bank_tmp}</td><td>{$row["time"]}</td></tr>\n";
				$bank_tmp -= $row["money"];
			}
		}
		
		View::header_def();
		
		print <<<EOF
<table class="list">
<thead><tr><th>内容</th><th>入金</th><th>出金</th><th>残高</th><th>日付</th></tr></thead>
<tbody>{$tr}</tbody>
</table>
<br>
<a href="./?mode=Bank&amp;command=top">銀行のトップに戻る</a><br>
<br>
<a href="./?mode=Map&amp;command=top">マップへ戻る</a>
EOF;
	}
	
	//----- 通帳更新 -----//
	static function write($to,$data){
		if(empty($data["time"])) $data["time"] = mkdate();
		
		$rows[] = $data;
		
		$file = "member/{$to}/bank.csv";
		$fp = fo($file,"r");
		while($row = assocCsv($fp,"bank")){
			$rows[] = $row;
		}
		fc($fp);
		
		array_splice($rows,self::$logMax + 1);
		
		$fp = fo($file,"w");
		foreach($rows as $row){
			putCsv($fp,"bank",$row);
		}
		fc($fp);
	}
	
	//----- 利子 -----//
	static function interest(){
		$my = Chara::$self;
		
		$interest = intval($my->bank * self::$interestRate);
		$my->bank += $interest;
		$my->update[] = "bank";
		self::write($my->id,array("work" => "利子","money" => $interest));
	}
}

?>