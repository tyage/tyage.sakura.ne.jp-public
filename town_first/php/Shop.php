<?php

class Shop{
	// コマンド用
	static $command = array("top","in","buy");
	
	static $types = array("食料品売り場" => array("料理","食品","飲料"),"娯楽品売り場" => array("DVD","玩具","書籍","ペット"),"美容と健康売り場" => array("スポーツ用品","薬","美容","装身具"),"生活用品売り場" => array("日用品","電化製品"),"ごちゃまぜ売り場" => array("お花","乗り物","名産品"));
	
	//----- 店トップ -----//
	static function top(){
		$my = Chara::$self;
		
		$my->from_ok = array("Map" => array("top"),"Main" => array("in"),"Shop" => array("in","buy"));
		$my->check_from();
		
		$i = 0;
		foreach(self::$types as $key => $value){
			$i++;
			$link .= "<a href='./?mode=Shop&amp;command=in&amp;type={$key}'>{$i}階：{$key}店へ</a><br>\n";
		}
		
		View::header_def();
		
		print <<<EOF
$link
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//----- 店の中 -----//
	static function in(){
		$my = Chara::$self;
		
		$my->from_ok = array("Shop" => array("top","buy"));
		$my->check_from();
		
		$types = self::$types[$_GET["type"]];
		if(empty($types)) error("店の種類を選んでください！");
		m($types);
		
		$query = mq("SELECT * FROM `shop` WHERE `type` IN (".makeValues($types).")");
		while($row = massoc($query)){
			$items[$row["type"]][] = $row;
			$itemNames[] = $row["name"];
		}
		
		m($itemNames);
		$itemData = get_item($itemNames);
		
		foreach($types as $type){
			if(empty($items[$type])) continue;
			
			$table .= "<table class='list shopItem'><thead>".self::FormatHeader($type)."</thead><tbody>";
			foreach($items[$type] as $item){
				$data = $itemData[$item["name"]];
				$item["name"] = "<input type='checkbox' name='{$data["name"]}' value='1'".( ($data["price"] > $my->money) ? " disabled='disabled'" : "" ).">{$data["name"]}";
				$table .= self::FormatBody($item,$data);
			}
			$table .= "</tbody></table><input type='submit' value='購入'>";
		}
		
		View::header_def("shop.js");
		
		print <<<EOF
<script type="text/javascript"><!--
$(function(){
	\$shop.set();
});
// --></script>
<form action="./?mode=Shop&amp;command=buy&amp;type={$_GET["type"]}" method="POST">
<table id="shop_item" class="list">
<tbody>{$table}</tbody>
</table>
</form>
<br>

<a href="./?mode=Shop&amp;command=top">デパート入口</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//----- デパート購入 -----//
	static function buy(){
		$my = Chara::$self;
		$my->item->load();
		
		$okItems = $errorItems = array();
		
		i($_POST);
		foreach($_POST as $key => $value){
			if($value > 0){
				m($key);
				$names[] = $key;
			}
		}
		if(empty($names)) error("商品を選んでください！");
		$query = mq("SELECT * FROM `shop` WHERE `name` IN (".makeValues($names).")");
		$flag = FALSE;
		while($row = massoc($query)){
			if($row["stock"] <= 0){
				$errorItems[$row["name"]] = 0;
				continue;
			}
			$flag = TRUE;
			
			$stock = $row["stock"] - $_POST[$row["name"]];
			if($stock < 0){
				$_POST[$row["name"]] = $errorItems[$row["name"]] = $row["stock"];
				$stock = 0;
			}else{
				$okItems[$row["name"]] = $_POST[$row["name"]];
			}
			mq("UPDATE `shop` SET `stock` = '{$stock}' WHERE `name` = '{$row["name"]}'");
			
			$itemNames[] = $row["name"];
			for($i=0;$i<$_POST[$row["name"]];$i++){
				$my->item->add(array("type" => "","name" => $row["name"],"first" => time(),"last" => 0,"count" => $row["count"],"place" => "デパート"));
			}
		}
		if(!$flag) error("どの商品もありませんでした！");
		
		mq("DELETE FROM `shop` WHERE `stock` = 0");
		
		$my->item->save();
		array_merge($my->item->data,get_item($itemNames));
		
		foreach($okItems as $key => $value){
			$okLog .= "<li>{$key}を{$value}個</li>";
		}
		if($okLog) $okLog = "<h3>以下の商品を購入しました。</h3>\n<br><ul>{$okLog}</ul>";
		
		foreach($errorItems as $key => $value){
				$errorLog .= "<li>{$key}が{$value}個</li>";
		}
		if($errorLog) $errorLog = "<span class='error'>以下の商品の在庫が足りませんでした！</span><br><ul>{$errorLog}</ul>しか買えませんでした。<br>";
		
		View::header_def();
		
		print <<<EOF
{$okLog}
{$errorLog}
<br>
<a href="./?mode=Shop&amp;command=in&amp;type={$_GET["type"]}">{$_GET["type"]}へ</a><br>
<a href="./?mode=Shop&amp;command=top">デパート入口</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	//内部用
	
	//----- デパート更新 -----//
	static function reload(){
		//新しいアイテムデータ取得
		$newItems = array();
		$query = mq("SELECT `rand`,`stock`,`type`,`name`,`count` FROM `item`");
		while($row = massoc($query)){
			//追加するか
			if($row["rand"] < rand(0,100)) continue;
			
			//ストック数決定（stockの半分以上）
			$stock = $row["stock"] / 2;
			i($stock);
			$stock = rand(1,$stock) + $stock;
			
			//整形してSQL文作成
			$tmp = array(&$row["type"],&$row["name"]);
			m($tmp);
			$tmp = array(&$stock,&$row["count"]);
			i($tmp);
			$newItems[] = "('{$row["type"]}','{$row["type_sub"]}','{$row["name"]}',{$stock},{$row["count"]})";
		}
		
		mq("TRUNCATE TABLE `shop`");
		mq("INSERT INTO `shop` (`type`,`type_sub`,`name`,`stock`,`count`) VALUES ".implode(",",$newItems));
	}
	
	static function FormatHeader($type){
		$header = "<tr><th rowspan='2'>▼{$type}</th><th>体力</th><th>精神力</th>";
		foreach(Ini::$ability_ja as $value){
			$header .= "<th>{$value}</th>";
		}
		$header .= "</tr><tr><th>在庫</th><th>金額</th><th>耐久</th><th>間隔</th><th colspan='13'>説明</th></tr>\n";
		return $header;
	}
	static function FormatBody($item,$itemData){
		$body = "<tr><td rowspan='2'>{$item["name"]}</td><td>{$itemData["energy"]}</th><td>{$itemData["spirit"]}</td>";
		foreach(Ini::$ability as $value){
			if($itemData[$value] == "0") $itemData[$value] = "";
			$body .= "<td>{$itemData[$value]}</td>";
		}
		$body .= "</tr><tr><td>{$item["stock"]}個</td><td>{$itemData["price"]}円</td><td>{$itemData["count"]}回</td><td>{$itemData["span"]}分</td><td colspan='13'>{$itemData["explain"]}</td></tr>\n";
		return $body;
	}
}

?>