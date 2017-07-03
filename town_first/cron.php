<?php

require_once("ini.php");
require_once("view.php");
require_once("library.php");
require_once("chara.php");

setup();

$query = mq("SELECT `id` FROM `member`");
while($row = massoc($query)){
	$user = new User();
	$user->id = $row["id"];
	$user->load(array("bank","time"));
	Chara::$self = &$user;
	if($user->bank > 0) Bank::interest();
	$user->save();
	
	$now = time();
	$last = getTime($user->time["reload"]);
	if($now - $last["timestamp"] > Ini::$life*24*60*60) $user->delete();
}

//デパート更新
Shop::reload();

//広告クリック初期化
mq("TRUNCATE TABLE `ad`");
?>