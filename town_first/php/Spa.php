<?php

class Spa{
	static $command = array("top","in","out");
	
	static $spas = array("nomal" => "普通");
	static $cost = array("nomal" => 100);
	static $energySpeed = array("nomal" => 1);
	static $spiritSpeed = array("nomal" => 1);
	
	//----- 温泉トップ -----//
	static function top(){
		$my = Chara::$self;
		
		//来た場所チェック
		$my->from_ok = array("Map" => array("top"),"Main" => array("in"),"Spa" => array("out"));
		$my->check_from();
		
		//温泉テーブル
		foreach(self::$spas as $key => $value){
			$cost = self::$cost[$key];
			$energySpeed = self::$energySpeed[$key];
			$spiritSpeed = self::$spiritSpeed[$key];
			$energyMaxSpeed = intval(($my->max_energy - $my->energy) / $energySpeed);
			$spiritMaxSpeed = intval(($my->max_spirit - $my->spirit) / $spiritSpeed);
			
			$spas .= "<tr><td>".($cost > $my->money ? "{$value}風呂（資金不足で入れません。）" : "<a href='./?mode=Spa&amp;command=in&amp;type={$key}'>{$value}風呂に入る</a>")."</td><td>{$cost}円</td><td>{$energySpeed}/秒</td><td>{$spiritSpeed}/秒</td><td>{$energyMaxSpeed}秒</td><td>{$spiritMaxSpeed}秒</td><td></td></tr>\n";
		}
		
		//表示
		View::header_def();
		
		print <<<EOF
<table class="list">
<thead><tr><th>温泉</th><th>費用</th><th>体力回復度</th><th>精神力回復度</th><th>体力完全回復まで</th><th>精神力完全回復まで</th><th>効能</th></tr></thead>
<tbody>{$spas}</tbody>
</table>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
	
	//----- 温泉入浴中 -----//
	static function in(){
		$my = Chara::$self;
		
		//来た場所チェック
		$my->from_ok = array("Spa" => array("top"));
		$my->check_from();
		
		if(empty(self::$spas[$_GET["type"]])) error("その温泉はありません。");
		
		//表示
		View::header_def();
		
		$energySpeed = self::$energySpeed[$_GET["type"]];
		$spiritSpeed = self::$spiritSpeed[$_GET["type"]];
		$energyMaxSpeed = intval(($my->max_energy - $my->energy) / $energySpeed);
		$spiritMaxSpeed = intval(($my->max_spirit - $my->spirit) / $spiritSpeed);
		
		print <<<EOF
<script><!-- 
$(function(){
	$("#energy").countDown({
		from : {$my->energy},
		to : {$my->max_energy},
		level : {$energySpeed}/1000,
		down : false
	});
	$("#spirit").countDown({
		from : {$my->spirit},
		to : {$my->max_spirit},
		level : {$spiritSpeed}/1000,
		down : false
	});
	$("#energyTime").countDown({
		from : {$energyMaxSpeed},
		to : 0,
		level : 1/1000
	});
	$("#spiritTime").countDown({
		from : {$spiritMaxSpeed},
		to : 0,
		level : 1/1000
	});
});
 --></script>
<p>体力：<span id="energy"></span>（残り：<span id="energyTime"></span>秒）</p><br>
<p>精神力：<span id="spirit"></span>（残り：<span id="spiritTime"></span>秒）</p><br>
<br>
<p>入浴中に更新したり、違う動作をすると正常に回復しません。</p><br>
<a href="./?mode=Spa&amp;command=out&amp;type={$_GET['type']}">温泉からあがる</a>
EOF;
	}
	
	//----- 温泉からあがる -----//
	static function out(){
		$my = Chara::$self;
		
		//来た場所チェック
		$my->from_ok = array("Spa" => array("in"));
		$my->check_from();
		
		//温泉の値段取得
		$cost = self::$cost[$_GET['type']];
		
		//ヘッダー
		View::header_def();
		
		//お金がないとき
		if($cost > $my->money){
			$my->energy = 0;
			$my->spirit = 0;
			
			print "お金がないので必死で逃げたら心も体もぼろぼろになりました。<br>";
		}else{
			$my->money -= $cost;
			
			$first = getTime($my->time["reload"]);
			$batheTime = time() - $first["timestamp"];
			
			if($batheTime > 24*60*60){
				$my->energy = 0;
				$my->spirit = 0;
				
				print "一日中入っていたので心も体もぼろぼろです。<br>";
			}else{
				$recoverEnergy = $batheTime * self::$energySpeed[$_GET['type']];
				$recoverSpirit = $batheTime * self::$spiritSpeed[$_GET['type']];
				if($my->energy + $recoverEnergy > $my->max_energy) $recoverEnergy = $my->max_energy - $my->energy;
				if($my->spirit + $recoverSpirit > $my->max_spirit) $recoverSpirit = $my->max_spirit - $my->spirit;
				$my->energy += $recoverEnergy;
				$my->spirit += $recoverSpirit;
				
				print <<<EOF
{$my->time["reload"]}から入りました。<br>
{$cost}円かかりました。<br>
体力{$recoverEnergy}、精神力{$recoverSpirit}回復しました。<br>
EOF;
			}
		}
		
		array_push($my->update,"money","energy","spirit");
		
		print <<<EOF
<a href="./?mode=Spa&amp;command=top">温泉トップ</a><br>
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a>
EOF;
	}
}

?>