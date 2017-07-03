<?php

class Buyer{
	static $command = array("top","in","buy");
	
	static function top(){
		$my = Chara::$self;
		
		$i = 0;
		foreach(Shop::$types as $key => $value){
			$i++;
			$links .= "<a href='./?mode=Buyer&amp;command=in&amp;type={$key}'>{$i}階：{$key}店へ</a><br>\n";
		}
		
		View::header_def();
		
		print <<<EOF
$links
<br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	static function in(){
		$my = Chara::$self;
		
		if(!$_GET["type"]) error("店の種類を選んでください！");
		$types = Shop::$types[$_GET["type"]];
		
		$fp = fo("log/buyer/{$my->town}.csv","r");
		while($row = assocCsv($fp,"shop")){
			if(!in_array($row["type"],$types)) continue;
			$items[$row["type"]][] = $row;
			$itemNames[] = $row["name"];
		}
		fc($fp);
		
		m($itemNames);
		$itemData = get_item($itemNames);
		
		foreach($types as $type){
			if(empty($items[$type])) continue;
			
			$table .= "<table class='list shopItem'><thead>".Shop::FormatHeader($type)."</thead><tbody>";
			foreach($items[$type] as $item){
				$data = $itemData[$item["name"]];
				$item["name"] = "<input type='checkbox' name='{$data["name"]}' value='1'".( ($data["price"] > $my->money) ? " disabled='disabled'" : "" ).">{$data["name"]}";
				$table .= Shop::FormatBody($item,$data);
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
<form action="./?mode=Buyer&amp;command=buy&amp;type={$_GET["type"]}" method="POST">
<table id="shop_item" class="list">
<tbody>{$table}</tbody>
</table>
</form>
<br>

<a href="./?mode=Buyer&amp;command=top">問屋入口</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	static function buy(){
		$my = Chara::$self;
		$my->item->load();
		
		House::load($my->id);
		if(!House::$data["shop"]["have"]) error("お店がありません！");
		
		$okItems = $errorItems = array();
		$file = "log/buyer/{$my->town}.csv";
		i($_POST);
		
		$flag = FALSE;
		$fp = fo($file,"r");
		while($row = assocCsv($fp,"shop")){
			if($_POST[$row["name"]] <= 0){
				if($row["stock"] > 0) $newRows[] = $row;
				continue;
			}
			if($row["stock"] <= 0){
				$errorItems[$row["name"]] = 0;
				continue;
			}
			$flag = TRUE;
			
			$stock = $row["stock"] - $_POST[$row["name"]];
			if($stock < 0){
				$_POST[$row["name"]] = $errorItems[$row["name"]] = $row["stock"];
			}else{
				$okItems[$row["name"]] = $_POST[$row["name"]];
				$row["stock"] = $stock;
				if($stock > 0) $newRows[] = $row;
			}
			
			$shopItems[] = array("type" => $row["type"],"name" => $row["name"],"stock" => $_POST[$row["name"]],"count" => $row["count"]);
		}
		if(!$flag) error("どの商品もありませんでした！");
		fc($fp);
		
		$fp = fo($file,"w");
		foreach($newRows as $row) putCsv($fp,"shop",$row);
		fc($fp);
		
		$fp = fo("member/{$my->id}/shop.csv","r");
		while($row = assocCsv($fp,"shop")){
			$flag = FALSE;
			foreach($shopItems as $no => $item){
				if($item["name"] == $row["name"]){
					$flag = TRUE;
					$shopItems[$no]["stock"] += $row["stock"];
					break;
				}
			}
			if(!$flag) $shopItems[] = $row;
		}
		fc($fp);
		
		$fp = fo("member/{$my->id}/shop.csv","w");
		foreach($shopItems as $row) putCsv($fp,"shop",$row);
		fc($fp);
		
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
<a href="./?mode=Buyer&amp;command=in&amp;type={$_GET["type"]}">{$_GET["type"]}へ</a><br>
<a href="./?mode=Buyer&amp;command=top">問屋入口</a><br>
<a href="./?mode=Map&amp;command=top">街へ戻る</a><br>
EOF;
	}
	
	static function reload(){
		foreach(Ini::$maps as $id => $map){
			$fp = fo("log/buyer/{$id}.csv","w");
			
			$queryItem = mq("SELECT `rand`,`stock`,`type`,`name`,`count` FROM `item`");
			while($item = massoc($queryItem)){
				if($item["rand"] < rand(0,100)) continue;
				
				$stock = $item["stock"] / 2;
				i($stock);
				$stock = rand(1,$stock) + $stock;
				
				putCsv($fp,"shop",array("type" => $item["type"],"name" => $item["name"],"stock" => $stock,"count" => $item["count"]));
			}
			
			fc($fp);
		}
	}
}

?>