<?php

/***** マップ処理 *****/
class Map{
	
	static $command = array("top","makeBigMapTable");
	static $command_ajax = array("reload","simple_view");
	
	//-----トップ -----//
	static function top(){
		$my = Chara::$self;
		$speed = $my->getSpeed();
		
		$mapImages = self::makeMapTable($my->town);
		
		$tows = self::get_towns($my->town);
		
		View::header_def(array("map.js"));
		
		print <<<EOF
<script type="text/javascript"><!--
$(function(){
	\$chara.speed = {$speed};
	\$map.data = {
		now : "{$my->town}",
		right : "{$tows["right"]}",
    left : "{$tows["left"]}",
    down : "{$tows["down"]}",
    up : "{$tows["up"]}"
  };
	\$map.reload_time = 30;
	\$map.set();
});
// --></script>

<div style="float:left;" id="map">

<div id="map_reload" class="load"></div>
EOF;
		//キャラ表示
		print "<div id='map_chara'>";
		View::entry_town($my->town,$my);
		print "</div>";
		
		//マップ表示
		self::view($my->town);
		
		print <<<EOF
</div>

<table class="blank" id="mapImages"><tbody>
{$mapImages}
</tbody></table>

<table class="blank" id="cursor">
<tbody>
	<tr><td></td><td><img src="./img/arrow/up.gif" class="up"></td><td></td></tr>
	<tr><td><img src="./img/arrow/left.gif" class="left"></td><td></td><td><img src="./img/arrow/right.gif" class="right"></td></tr>
	<tr><td></td><td><img src="./img/arrow/down.gif" class="down"></td><td></td></tr>
</tbody>
</table>
<p>カーソルキーで移動できます。</p><br>
<br>
<span id="chara_delete">キャラ全て死亡</span>

EOF;
		
	}
	
	//----- マップ更新 -----//
	static function reload(){
		$my = Chara::$self;
		
		//座標移動
		$my->x = $_POST["x"];
		$my->y = $_POST["y"];
		if($my->x > 32*16 + 16 - 32) $my->x = 32*16+16 - 32;
		if($my->y > 32*16 + 16 - 32) $my->y = 32*16+16 - 32;
		array_push($my->update,"x","y");
		
		if($_POST["town"]){
			//--- 周りの街を取得 ---//
			$tows = self::get_towns($my->town);
			
			//--- マップ移動 ---//
			if($tows[$_POST["town"]]){
				$my->town = $tows[$_POST["town"]];
				$my->update[] = "town";
				
				$tows = self::get_towns($my->town);
				
				print "<div id='map_data' class='data' now='{$my->town}' right='{$tows["right"]}' left='{$tows["left"]}' down='{$tows["down"]}' up='{$tows["up"]}'></div>";
				
				//マップ表示
				self::view($my->town);
			}
			
		}
		
		//キャラ表示
		$my->entry();
		print "<div id='map_chara'>";
		View::entry_town($my->town,$my);
		print "</div>";
	}
	
	//----- 単純マップ（トップや建設） -----//
	static function simple_view(){
		self::view($_GET["town"],FALSE);
	}
	
	//----- ユニット取得 -----//
	static function get_unit(){
		$query = mq("SELECT * FROM `unit`");
		while($row = massoc($query)){
			$unit[$row['key']] = $row;
		}
		return $unit;
	}
	
	//----- 家ユニット取得 -----//
	static function getHouses($town = false){
		$query = mq("SELECT * FROM `house` WHERE `type` IN ('House') ".(empty($town) ? "" : " AND `town` = '{$town}'"));
		while($house = massoc($query)){
			$houses[$house["x"]][$house["y"]] = $house;
		}
		return $houses;
	}
	
	//----- マップ表示 -----//
	static function view($townID,$linkable = TRUE){
		if(!$town = Ini::$maps[$townID]) error("街が見つかりません");
		print "<div id='town_data' class='data' name='{$town["name"]}' x='{$town["x"]}' y='{$town["y"]}' price='{$town["price"]}'></div>\n";
		
		//ユニット取得
		$unit = self::get_unit();
		$house = self::getHouses($townID);
		
		//マップテーブル作成（ヘッダー）
		$map .= "<div class='map' id='map_now'><img src='./img/map/{$townID}.jpg' id='mapBackImage'>";
		
		//マップテーブル作成（ボディ）
		$i = 0;
		$fp = fo("log/map/{$townID}.csv","r");
		while($row = fgetcsv($fp)){
			$y = $i++;
			
			foreach($row as $x => $value){
				$img = "";
				$imgAttr = "";
				$imgAttrs = array();
				$linkAttr = "";
				$linkAttrs = array();
				
				$x++;
				
				if(empty($house[$x][$y]) and empty($unit[$value]["key"])) continue;
				
				$map .= "<div style='top:".($i * 32 - 32)."px;left:".($x * 32 - 32)."px;'>";
				
				if($unit[$value]['tag']){
					$map .= "{$unit[$value]['tag']}</div>";
					continue;
				}
				
				$imgAttrs["title"] = $unit[$value]['explain'];
				$imgAttrs["name"] = $unit[$value]['name'];
        if(!empty($unit[$value]['img'])) $imgAttrs["src"] = "./img/".$unit[$value]['img'];
				if(!empty($unit[$value]['mode'])) $linkAttrs["href"] = "./?mode={$unit[$value]['mode']}&amp;command={$unit[$value]['command']}";
				
				if($value == "宿") $imgAttrs["class"] = "apart";
        if($value == "地"){
        	if(empty($house[$x][$y])){
        		$imgAttrs["vx"] = $x;
        		$imgAttrs["vy"] = $y;
        		$imgAttrs["class"] = "sell";
        	}else{
						$imgAttrs["title"] = $house[$x][$y]['explain'];
						$imgAttrs["name"] = $house[$x][$y]['name']."の家";
						$imgAttrs["class"] = "house";
						$imgAttrs["src"] = "./img/house/{$house[$x][$y]["img"]}";
						$linkAttrs["href"] = "./?mode=House&amp;command=top&amp;name=".urlencode($house[$x][$y]["name"]);
					}
				}
        
				if(!empty($imgAttrs)) $img = "<img".formatAttr($imgAttrs).">";
				if($linkable and !empty($linkAttrs)) $img = "<a".formatAttr($linkAttrs).">{$img}</a>";
				$map .= $img."</div>";
			}
		}
		
		$map .= "</div>";
		
		print $map;
	}
	
	//----- 周りの街を取得 -----//
	static function get_towns($town){
		$now = Ini::$maps[$town];
		
		foreach(Ini::$maps as $id => $map){
			if($map["x"] == $now["x"]-1 and $map["y"] == $now["y"]) $left = $id;
			elseif($map["x"] == $now["x"]+1 and $map["y"] == $now["y"]) $right = $id;
			elseif($map["x"] == $now["x"] and $map["y"] == $now["y"]+1) $up = $id;
			elseif($map["x"] == $now["x"] and $map["y"] == $now["y"]-1) $down = $id;
		}
		
		return array("left" => $left,"right" => $right,"up" => $up,"down" => $down);
	}
	
	static function makeMapTable($now){
		foreach(Ini::$maps as $id => $map){
			$map["id"] = $id;
			$maps[$map["y"]][$map["x"]] = $map;
		}
		for($y=Ini::$mapVector["y"];$y>=1;$y--){
			$mapImages .= "<tr>";
			for($x=1;$x<=Ini::$mapVector["x"];$x++){
				$map = $maps[$y][$x];
				$img = empty($map) ? "" : "<h4>{$map["name"]}</h4><img".($map["id"] == $now ? " src='./img/map/thumbs/{$map["id"]}.jpg' id='mapNow'" : " src='./img/map/thumbs/{$map["id"]}_d.jpg'")." title='{$map["id"]}'>";
				$mapImages .= "<td>{$img}</td>";
			}
			$mapImages .= "</tr>";
		}
		return $mapImages;
	}
	static function makeBigMapTable(){
		View::header_def();
			
		$mapYs = array(32.7,35.6,38.4,41.1,43.7);
		
		foreach(Ini::$maps as $id => $map){
			$map["id"] = $id;
			$maps[$map["y"]][$map["x"]] = $map;
		}
		for($y=Ini::$mapVector["y"];$y>=1;$y--){
			$mapImages .= "<tr>";
			for($x=1;$x<=Ini::$mapVector["x"];$x++){
				$map = $maps[$y][$x];
				$googleMapY = $mapYs[$y-1];
				$googleMapX = 127 + $x*3.48;
				$googleMap = "http://maps.google.com/staticmap?center={$googleMapY},{$googleMapX}&span=2,2&size=640x640&sensor=false&format=jpg&maptype=satellite&key=";
				if(!empty($map)){
					$img = file_get_contents($googleMap);
					
					$fp = fopen(Ini::$dir2."img/map/{$map["id"]}.jpg","w");
					fwrite($fp,$img);
					fclose($fp);
				}
				$mapImages .= "<td>".(empty($map) ? "" : "<img src='{$googleMap}' width='128' height='128'>")."</td>";
			}
			$mapImages .= "</tr>";
		}
		print "<table class='blank' cellpadding='0' cellspacing='0'>".$mapImages."</table>";
	}
}

?>