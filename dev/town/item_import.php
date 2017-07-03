<?php
$db_host = "";
$db_user = "tyage";
$db_pass = "";
$db_name = "tyage";

$mysql = mysql_connect($db_host,$db_user,$db_pass) or error("データベースコネクトエラー<br>".mysql_error());
mysql_select_db($db_name) or error("データベースセレクトエラー<br>".mysql_error());
mysql_set_charset("utf8");

$keyTable = array(
	'syubetu' => 'category',
	'hinmoku' => 'name',
	'nedan' => 'price',
	'taikyuu' => 'life',
	'kankaku' => 'interval',
	'comment' => 'description',
	'cal' => 'weight',
	'kouka' => 'special',
	'zaiko' => 'stock',
	'sintai_syouhi' => 'energy',
	'zunou_syouhi' => 'spirit',
	'kokugo' => 'language',
	'suugaku' => 'math',
	'rika' => 'science',
	'syakai' => 'society',
	'power' => 'power',
	'speed' => 'speed',
	'kenkou' => 'soft',
	'looks' => 'beauty'
);

$file = "item.csv";
$data = file_get_contents($file);
$item = explode("\n",$data);
foreach($item as $i => $row){
   $list = explode("<>",$row);
   list($syubetu,$hinmoku,$kokugo,$suugaku,$rika,$syakai,$eigo,$ongaku,$bijutu,$kouka,$looks,$tairyoku,$kenkou,$speed,$power,$wanryoku,$kyakuryoku,$nedan,$love,$unique,$etti,$taikyuu,$taikyuu_tani,$kankaku,$zaiko,$cal,$siyou_date,$sintai_syouhi,$zunou_syouhi,$comment,$kounyuubi,$tanka,$tokubai) = $list;

   $power = ($wanryoku + $kyakuryoku) / 2;
   $cal /= 1000;
   $kankaku *= 60;
   $sintai_syouhi = -$sintai_syouhi;
   $zunou_syouhi = -$zunou_syouhi;
   
   $sql .= "(";
   foreach ($keyTable as $from => $to) {
		$sql .= "'".$$from."',";
   }
	$sql = substr($sql,0,-1);
	$sql .= "),";
}
$sql = substr($sql,0,-1);

foreach ($keyTable as $from => $to) {
	$insert .= "`".$to."`,";
}
$insert = substr($insert,0,-1);
mysql_query("INSERT INTO `town_dev_items` ({$insert}) VALUES {$sql}") or print("クエリ失敗<br>SQL:<br>{$sql}<br>".mysql_error());
