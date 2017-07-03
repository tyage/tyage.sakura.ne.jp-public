<?php

/***** 設定 *****/
class Ini{
	
	//データベース（sakura）
	static $db_host_sa = "";
	static $db_user_sa = "tyage";
	static $db_pass_sa = "";
	static $db_name_sa = "tyage";
	
	//データベース（@pages）
	static $db_host_at = "localhost";
	static $db_user_at = "tyage";
	static $db_pass_at = "";
	static $db_name_at = "";
	
	//データベース（local）
	static $db_host = "localhost";
	static $db_user = "";
	static $db_pass = "";
	static $db_name = "town";
	
	static $mysql = null;

	//基本
	static $title = "KINKI in PHP（仮名） RC";
	static $ver = "1.0";
	static $dir = "";//見せちゃだめディレクトリ
	static $dir2 = "";//公開ディレクトリ（画像とか）
	static $header = array("jquery/jquery.js","jquery/hotkeys.js","basic.js","message.js","navi.js","menu.js","tab.js","window.js","chat.js","mail.js","entry.js","item.js","config.js","house.js");
	
	static $adKey = "";
	
	static $window = array("status" => "ステータス","item" => "アイテム","mail" => "メール","chat" => "チャット","house" => "家","config" => "設定");
	
	//エラー全部表示
	static $errorHeader = true;
	
	//--- ファイルヘッダ ---//
	static $fheader = array(
		"enq" => array("title","vote","creater"),
		"mail" => array("no","type","name","time","title","message"),
		"bank" => array("work","money","time"),
		"shop" => array("type","name","stock","count"),
		"item" => array("no","type","name","first","last","count","place","message"),
		"bbs" => array("no","title","name","id","message","time"),
		"blog" => array("no","title","time"),
		"blogC" => array("no","name","comment","time")
	);
	
	//住民
	static $life = 30;//(日)
	
	static $walkSpeed = 0.5;
	
	//能力
	static $ability = array("language","math","science","society","arm","leg","quick","soft","beauty","attent","skill","lucky");
	static $ability_ja = array(
		"language" => "言語",
		"math" => "数学",
		"science" => "科学",
		"society" => "社会",
		"arm" => "腕力",
		"leg" => "脚力",
		"quick" => "俊敏",
		"soft" => "柔軟",
		"beauty" => "魅力",
		"attent" => "集中",
		"skill" => "器用",
		"lucky" => "運"
	);
	static $ability_type = array(
		"brain" => array("language","math","science","society"),
		"physical" => array("arm","leg","quick","soft"),
		"other" => array("beauty","attent","skill","lucky")
	);
	
	//管理人
	static $admin_id = "tyage";
	static $admin_pass = "";
	
	//参加者表示
	static $entry_limit = 10;//（分）
	
	static $charaDefSpeed = 0.5;
	
	//マップ
	static $maps = array(
		"kyushu" => array("name" => "九州","x" => 1,"y" => 1,"price" => 10000),
		"shikoku" => array("name" => "四国","x" => 2,"y" => 1,"price" => 10000),
		"chugoku" => array("name" => "中国","x" => 2,"y" => 2,"price" => 10000),
		"chubu" => array("name" => "中部","x" => 3,"y" => 2,"price" => 10000),
		"kanto" => array("name" => "関東","x" => 4,"y" => 2,"price" => 10000),
		"hokuriku" => array("name" => "北陸","x" => 4,"y" => 3,"price" => 10000),
		"tohoku" => array("name" => "東北","x" => 4,"y" => 4,"price" => 10000),
		"westHokkai" => array("name" => "西北海","x" => 4,"y" => 5,"price" => 10000),
		"eastHokkai" => array("name" => "東北海","x" => 5,"y" => 5,"price" => 10000)
	);
	static $mapVector = array("x" => 5,"y" => 5);
	
	//設定
	static $set_css = array("theme" => "テーマ","original" => "自作");
	
	//家
	static $house_img = array("1.gif" => 10,"2.gif" => 10,"3.gif" => 10,"4.gif" => 10,"5.gif" => 10,"6.gif" => 10,"7.gif" => 10,"8.gif" => 10,"9.gif" => 10,"10.gif" => 10,"11.gif" => 10,"12.gif" => 10,"13.gif" => 10,"14.gif" => 10,"15.gif" => 10,"16.gif" => 10,"17.gif" => 10,"18.gif" => 10,"19.gif" => 10,"20.gif" => 10,"21.gif" => 10,"22.gif" => 10,"23.gif" => 10,"24.gif" => 10,"25.gif" => 10,"26.gif" => 10,"27.gif" => 10,"28.gif" => 10,"29.gif" => 10,"30.gif" => 10,"31.gif" => 10,"32.gif" => 10,"33.gif" => 10,"34.gif" => 10,"35.gif" => 10,"36.gif" => 10,"37.gif" => 10,"38.gif" => 10,"39.gif" => 10,"40.gif" => 10,"41.gif" => 10,"42.gif" => 10,"43.gif" => 10,"44.gif" => 10,"45.gif" => 10,"46.gif" => 10,"47.gif" => 10,"48.gif" => 10,"49.gif" => 10,"50.gif" => 10,"51.gif" => 10,"52.gif" => 10,"53.gif" => 10,"54.gif" => 10,"55.gif" => 10,"56.gif" => 10,"57.gif" => 10,"58.gif" => 10,"59.gif" => 10,"60.gif" => 10,"61.gif" => 10,"62.gif" => 10,"63.gif" => 10,"64.gif" => 10,"65.gif" => 10,"66.gif" => 10,"67.gif" => 10,"68.gif" => 10,"69.gif" => 10,"70.gif" => 10,"71.gif" => 10,"72.gif" => 10,"73.gif" => 10,"74.gif" => 10,"75.gif" => 10,"76.gif" => 10,"77.gif" => 10,"78.gif" => 10,"79.gif" => 10,"80.gif" => 10,"81.gif" => 10,"82.gif" => 10,"83.gif" => 10,"84.gif" => 10,"85.gif" => 10,"86.gif" => 10,"87.gif" => 10,"88.gif" => 10,"89.gif" => 10,"90.gif" => 10,"91.gif" => 10,"92.gif" => 10,"93.gif" => 10,"94.gif" => 10,"95.gif" => 10,"96.gif" => 10,"97.gif" => 10,"98.gif" => 10,"99.gif" => 10,"100.gif" => 10,"101.gif" => 10,"102.gif" => 10,"103.gif" => 10,"104.gif" => 10,"105.gif" => 10,"106.gif" => 10,"107.gif" => 10,"108.gif" => 10,"109.gif" => 10,"110.gif" => 10,"111.gif" => 10,"112.gif" => 10,"113.gif" => 10,"114.gif" => 10,"115.gif" => 10,"116.gif" => 10,"117.gif" => 10,"118.gif" => 10);
	static $house_content = array("bbs" => 100,"shop" => 100,"blog" => 100,"free" => 100,"store" => 100);
	static $house_content_ja = array("bbs" => "掲示板","shop" => "お店","blog" => "ブログ","free" => "フリーページ","store" => "倉庫");
	
	//--- その他 ---//
	static $update = array();
	
	//----- 設定取得 -----//
	static function load($select){
		$ini = array();
		$query = mq("SELECT * FROM `ini` WHERE `key` IN (".makeValues($select).")");
		while($row = massoc($query)){
			$ini[$row['key']] = $row['value'];
		}
		if (empty($row)) {
			if (!is_array($select)) $select = array($select);
			foreach ($select as $s) {
				mq("INSERT INTO `ini` (`key`, `value`) VALUES ('".$s."', '')");
			}
		}
		return $ini;
	}
	
	//----- 設定保存 -----//
	static function save($ini){
		foreach($ini as $key => $value){
			$sql = "UPDATE `ini` SET value = ".($value == "NOW()" ? $value : "'{$value}'")." WHERE `key` = '{$key}';";
		}
		mq($sql) or error("クエリ失敗<br>SQL:{$sql}");
	}
}

?>