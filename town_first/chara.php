<?php

class Chara extends User{
	static $self = null;
	function __construct(){
		parent::__construct();
		
		$this->setup();
		$this->checkPass();
		$this->load();
		$this->entry();
		$this->login = true;
		
		self::$self = &$this;
	}
	
	function checkPass(){
		if(!parent::checkPass()){
			View::relogin();
			exit();
		}
	}
	
}

/***** キャラクターデータ *****/
class User{
	//- シリアル化されて保存されているデータ -//
	static $db_serial = array("ip","time","work","window");
	static $self = null;
	
	//----- 初期設定 -----//
	function __construct(){
		self::$self = &$this;
	}
	
	//----- セットアップ -----//
	function setup(){
		if($_POST["id"]) $_SESSION["id"] = $_POST["id"];
		elseif($_GET["login"] == "test") $_SESSION["id"] = "test";
		elseif(!$_SESSION["id"] and $_COOKIE["id"]) $_SESSION["id"] = $_COOKIE["id"];
		$this->id = $_SESSION["id"];
		
		if($_POST["pass"]) $_SESSION["pass"] = $_POST["pass"];
		elseif($_GET["login"] == "test") $_SESSION["pass"] = "test";
		elseif(!$_SESSION["pass"] and $_COOKIE["pass"]) $_SESSION["pass"] = $_COOKIE["pass"];
		$this->pass = $_SESSION["pass"];
	}
	
	//----- 名前からID取得 -----//
	static function get_id($name){
		m($name);
		
		if(is_array($name)){
			$name_id = array();
			
			$query = mq("SELECT `id`,`name` FROM `member` WHERE `name` IN (".makeValues($name).")");
			while($row = massoc($query)){
				$name_id[$row["name"]] = $row["id"];
			}
			return $name_id;
		}else{
			$query = mq("SELECT `id`,`name` FROM `member` WHERE `name` = '{$name}'");
			$row = massoc($query);
			return $row["id"];
		}
	}
	
	//----- 移動速度取得 -----//
	function getSpeed(){
		$this->item->load();
		return $this->item->items[$this->move] ? $this->item->data[$this->item->items[$this->move]["name"]]["special"]["speed"] : Ini::$walkSpeed;
	}
	
	//----- キャラデータロード -----//
	function load($selects = null){
		m($this->id);
		
		//--- ロード ---//
		$query = mq("SELECT ".(empty($selects) ? "*" : makeColumns($selects))." FROM `member` WHERE `id` = '{$this->id}'");
		$row = massoc($query);
		if(empty($row)) return;
		foreach($row as $key => $value){
			$this->$key = $value;
		}
		
		//--- 初期化 ---//
		$this->show = 1;
		$this->item = new Item($this->id);
		$this->admin = ($this->id === Ini::$admin_id and $this->pass === Ini::$admin_pass);
		$this->update = array();
		
		foreach(self::$db_serial as $key){
			$unserialize = unserialize($this->$key);
			$this->$key = ( is_array($unserialize) ? $unserialize : array() );
		}
	}
	
	//----- キャラデータセーブ -----//
	function save(){
		//--- データベース更新 ---//
		foreach(array_unique($this->update) as $key){
			if( in_array($key,self::$db_serial) ) $this->$key = serialize($this->$key);
			m($this->$key);
			$sql .= "`{$key}` = '{$this->$key}',";
		}
		
		if(empty($sql)) return;
		
		m($this->id);
		mq("UPDATE `member` SET ".substr($sql,0,-1)." WHERE `id` = '{$this->id}'");
	}
	
	//----- キャラ削除 -----//
	function delete(){
		m($this->id);
		
		//--- 列削除 ---//
		mq("DELETE FROM `member` WHERE `id` = '{$this->id}'");
		mq("DELETE FROM `enq` WHERE `id` = '{$this->id}'");
		mq("DELETE FROM `double` WHERE `id1` = '{$this->id}' OR `id2` = '{$this->id}'");
		mq("DELETE FROM `entry` WHERE `id` = '{$this->id}'");
		mq("DELETE FROM `bbs` WHERE `type_sub` = '{$this->id}'");
		mq("DELETE FROM `house` WHERE `id` = '{$this->id}'");
		
		//--- ファイル削除 ---//
		del_dir(Ini::$dir."member/{$this->id}");
	}
	
	//----- 参加者一覧に追加 -----//
	function entry(){
		$tmp = array(&$this->show,&$this->last,&$this->x,&$this->y);
		i($tmp);
		$tmp = array(&$this->id,&$this->name,&$this->town);
		m($tmp);
		
		//--- 参加者テーブルを更新または追加 ---//
		$query = mq("SELECT * FROM `entry` WHERE id = '{$this->id}'");
		if(!massoc($query)){
			mq("INSERT INTO `entry` (`id`,`name`,`show`,`last`,`town`,`x`,`y`,`img`) VALUES ('{$this->id}','{$this->name}',{$this->show},NOW(),'{$this->town}',{$this->x},{$this->y},'{$this->img}')");
			if($this->show){
				//Chat::comment("","","『{$this->name}』さんが入ってきました。");
			}
		}else{
			mq("UPDATE `entry` SET `last` = NOW(),`town` = '{$this->town}',`x` = {$this->x},`y` = {$this->y},`img` = '{$this->img}' WHERE `id` = '{$this->id}'");
		}
		
		mq("DELETE FROM `entry` WHERE `last` < SUBTIME(NOW(),'0:".Ini::$entry_limit.":0')");
	}
	
	//----- ログアウト -----//
	function logout(){
		mq("DELETE FROM `entry` WHERE `id` = '{$this->id}'");
	}
	
	//----- 能力アップ -----//
	function upAbility($upAbility){
		foreach(Ini::$ability as $value){
			$this->$value += $upAbility[$value];
		}
	}
	
	//----- パスチェック -----//
	function checkPass(){
		$tmp = array(&$this->id,&$this->pass);
		m($tmp);
		
		//--- チェック ---//
		$query = mq("SELECT `id` FROM `member` WHERE `id` = '{$this->id}' AND `pass` = '{$this->pass}'");
		$row = massoc($query);
		return $row["id"] == $this->id and !empty($row["id"]);
	}
	
	//----- 来た場所チェックcheckAccess? -----//
	function check_from(){
		//--- 単に更新してる場合は気にない ---//
		if($this->mode == $_GET["mode"] and $this->command == $_GET["command"]) return;
		
		//--- チェック ---//
		if(!$this->from_ok[$this->mode]) $this->from_ok[$this->mode] = array();
		if( !in_array($this->command,$this->from_ok[$this->mode]) ) error("来た場所がおかしいです。");
	}
	
	function set_detail(){
		$this->money_all = $this->money + $this->bank;
		
		if($this->height > 0) $this->bmi = intval($this->weight / ($this->height/100)^2);
		
		$this->sexJa = formatSex($this->sex);
	}
	
}

?>