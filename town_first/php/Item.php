<?php

/***** アイテム *****/
class Item{
	static $command_ajax = array("reload");
	static $item_serial = array("special");
	
	function __construct($id){
		$this->id = $id;
		$this->isItemLoaded = FALSE;
	}
	
	//----- アイテムロード -----//
	function load(){
		if($this->isItemLoaded) return;
		
		$this->isItemLoaded = TRUE;
		$this->data = array();
		$this->items = array();
		
		$i = 0;
		$fp = fo("member/{$this->id}/item.csv","r");
		while($item = assocCsv($fp,"item")){
			foreach(self::$item_serial as $key){
				$unserialize = unserialize($item[$key]);
				$item[$key] = ( is_array($unserialize) ? $unserialize : array() );
			}
			$this->items[$item["no"]] = $item;
			$itemNames[] = $this->items[$item["no"]]["name"];
			$i++;
		}
		fc($fp);
		
		if($i > 0) $this->data = get_item($itemNames);
		
		foreach($this->items as $no => $item){
			if($this->isItemExpired($no)) $this->item->delete($no);
		}
	}
	
	//----- アイテムセーブ -----//
	function save(){
		$fp = fo("member/{$this->id}/item.csv","w");
		foreach($this->items as $no => $item){
			if($this->isItemExpired($no)) continue;
			putCsv($fp,"item",$item);
		}
		fc($fp);
	}
	
	function add($item){
		$ini = Ini::load("ItemNo");
		$ini["ItemNo"]++;
		Ini::$update = array("ItemNo");
		Ini::save($ini);
		
		$item["no"] = $ini["ItemNo"];
		$this->items[$ini["ItemNo"]] = $item;
	}
	
	function delete($no){
		unset($this->items[$no]);
	}
	
	private function _use($no){
		$my = Chara::$self;
		
		$itemData = $this->data[$this->items[$no]["name"]];
		
		$my->energy -= $itemData["energy"];
		$my->spirit -= $itemData["spirit"];
		if($my->energy < 0 or $my->spirit < 0){
			print "体力または精神力が足りません。";
			return;
		}
		array_push($my->update,"energy","spirit");
		
		// 次にアイテム一覧を表示するときに表示されないようにする
		if(--$this->items[$no]["count"] <= 0) $this->delete($no);
		
		$this->items[$no]["last"] = time();
		
		$ability_max = 0;
		foreach(Ini::$ability as $value){
			if($itemData[$value] > 0) $ability_up[$value] = "（<span style='color:red'>+{$itemData[$value]}</span>）";
			elseif($itemData[$value] < 0) $ability_up[$value] = "（<span style='color:blue'>{$itemData[$value]}</span>）";
			
			$my->$value += $itemData[$value];
			if($my->$value > $ability_max) $ability_max = $my->$value;
			$my->update[] = $value;
		}
		
		foreach(Ini::$ability_type as $type => $array){
			foreach($array as $value){
				$ability_length = ($my->$value <= 0 or $ability_max <= 0) ? 0 : intval(480 * $my->$value / $ability_max);
				$ability_bar .= "<div class='bar' style='width:{$ability_length}px'>".Ini::$ability_ja[$value]."：{$my->$value}{$ability_up[$value]}</div>";
			}
			$ability_bar .= "<br>";
		}
		
		print $itemData["name"]."を使用しました。<br><br>".$ability_bar;
	}
	
	private function _sell($no){
		$my = Chara::$self;
		
		$item = $this->items[$no];
		$itemData = $this->data[$item["name"]];
		
		$this->delete($no);
		$this->items[$no]["last"] = time();
		
		$money = ceil($item["count"]/$itemData["count"]*$itemData["price"]);
		$my->money += $money;
		$my->update[] = "money";
		
		print $itemData["name"]."を売却して、{$money}円を取得しました。";
	}
	
	function isItemExpired($no){
		$item = $this->items[$no];
		$itemData = $this->data[$item["name"]];
		return $item["count"] <= 0 or ($itemData["limit"] and $item["first"] + $itemData["limit"]*60*60*24 < time());
	}
	function getTimeToUse($no){
		$item = $this->items[$no];
		$itemData = $this->data[$item["name"]];
		return $item["last"] + $itemData["span"] * 60 - time();
	}
  
	//----- アイテム表示 -----//
	static function reload(){
		$my = Chara::$self;
		$my->item->load();
		
		if(!empty($_POST["work"]) and $my->item->getTimeToUse($no) > 0){
			print "まだアイテムは使えません。";
		}else{
			switch($_POST["work"]){
				case "use":
					$my->item->_use($_POST["item"]);
					break;
				case "sell":
					$my->item->_sell($_POST["item"]);
					break;
			}
		}
		
		if(!empty($_POST["work"])) $my->item->save();
		
		self::view();
	}
	
	//----- 表示 -----//
	static function view(){
		$my = Chara::$self;
		
		$table .= "<thead>".self::FormatHeader()."</thead><tbody>";
		
		foreach($my->item->items as $no => $item){
			$itemData = $my->item->data[$item["name"]];
			if(empty($itemData)) continue;
			
			$rest = $my->item->getTimeToUse($no);
			
			if($rest > 0){
				$disabled = " disabled='disabled'";
				$counter = "（使えるまで残り<span id='Item{$no}'></span>秒）";
				$counterJs .= "$('#Item{$no}').countDown({from:{$rest},to:0,level:1/1000,end:function(){\$(this).parent().find(':input').attr('disabled',false);}});";
			}else{
				$disabled = "";
				$counter = "";
			}
			$item["name"] = "<input type='radio' name='item' value='{$no}'{$disabled}>{$item["name"]}{$counter}";
			$table .= self::FormatBody($item,$itemData);
		}
		$table .= "</tbody>";
		
		print <<<EOF
<table class="item list">
{$table}
</table>
<input type="radio" name="work" value="use" checked="checked">使用
<input type="radio" name="work" value="sell">売却
<input type="submit" value="実行">
<script type="text/javascript"><!-- 
{$counterJs}
 --></script>
EOF;
	}
	
	static function FormatHeader($type="名前"){
		$header = "<tr><th rowspan='2'>▼{$type}</th><th>体力</th><th>精神力</th>";
		foreach(Ini::$ability_ja as $value){
			$header .= "<th>{$value}</th>";
		}
		$header .= "</tr><tr><th>金額</th><th>耐久</th><th>間隔</th><th colspan='13'>説明</th></tr>\n";
		return $header;
	}
	static function FormatBody($row,$itemData){
		$body = "<tr><td rowspan='2'>{$row["name"]}</td><td>{$itemData["energy"]}</th><td>{$itemData["spirit"]}</td>";
		foreach(Ini::$ability as $value){
			if($itemData[$value] == "0") $itemData[$value] = "";
			$body .= "<td>{$itemData[$value]}</td>";
		}
		$body .= "</tr><tr><td>".ceil($row["count"]/$itemData["count"]*$itemData["price"])."円</td><td>{$row["count"]}回</td><td>{$itemData["span"]}分</td><td colspan='13'>{$itemData["explain"]}</td></tr>\n";
		return $body;
	}
}

?>