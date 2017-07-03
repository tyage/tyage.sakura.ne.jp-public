<?php

/***** 管理画面 *****/
class Admin{
	static $command = array("top","delete_member","reset");
	
	static function construct(){
		if($_POST['admin_pass']) $_SESSION['admin_pass'] = $_POST['admin_pass'];
		if($_SESSION['admin_pass'] != Ini::$admin_pass) error("パスワードが違います。");
	}
	
	//----- 管理人トップ -----//
	static function top(){
		$query = mq("SELECT `id` FROM `member`");
		while($row = massoc($query)){
			$chara = new User();
			$chara->id = $row["id"];
			$chara->load(array("id","name","pass","ip","mail"));
			
			$ips = "";
			foreach($chara->ip as $ip => $count){
				$ips .= $ip."({$count}回)<br>";
			}
			
			$list_option .= "<option value='{$chara->id}'>{$chara->name}(ID:{$chara->id})</option>\n";
			$list_member .= "<tr><td>{$chara->id}</td><td>{$chara->name}</td><td>{$chara->pass}</td><td>{$ips}</td><td>{$chara->mail}</td><td>{$chara->double}</td></tr>\n";
		}
		
		View::header();
		
		print <<<EOF
<form action="?mode=Admin&amp;command=delete_member" method="POST">
	<select name="id[]" multiple="yes" size="10">
	{$list_option}
	</select>
	<input type="submit" value="メンバー消去">
</form>

<table class="list">
<tr><th>ＩＤ</th><th>名前</th><th>パスワード</th><th>ＩＰ</th><th>メールアドレス</th><th>多重登録</th></tr>
{$list_member}
</table>
EOF;
	}
	
	//----- メンバー消去 -----//
	static function delete_member(){
		if(!is_array($_POST["id"])) $_POST["id"] = array($_POST["id"]);
		
		$chara = new User();
		foreach($_POST["id"] as $id){
			$chara->id = $id;
			$chara->delete();
		}
		
		self::top();
	}
	
	//--- リセット ---//
	static function reset(){
		$query = mq("SELECT `id` FROM `member`");
		while($row = massoc($query)){
			$member_file = "member/{$row["id"]}/";
			if(!file_exists(Ini::$dir.$member_file)){
				mkdir(Ini::$dir.$member_file) or error("Make dir error");
				chmod(Ini::$dir.$member_file,0777);
			}
			
			$file = $member_file."mail.csv";
			if(!file_exists(Ini::$dir.$file)){
				$fp = fo($file,"w");
				fc($fp);
			}
			
			$file = $member_file."item.csv";
			if(!file_exists(Ini::$dir.$file)){
				$fp = fo($file,"w");
				fc($fp);
			}
			
			$file = $member_file."bank_normal.csv";
			if(!file_exists(Ini::$dir.$file)){
				$fp = fo($file,"w");
				fc($fp);
			}
			
			$dir = Ini::$dir.$member_file."css/";
			if(!file_exists($dir)){
				mkdir($dir) or error("Make dir error:{$dir}");
				chmod($dir,0777);
			}
			
			//--- CSSファイル作成 ---//
			/*
			foreach(Ini::$css as $en => $ja){
				$dir = Ini::$dir.$member_file."css/{$en}/";
				if(!file_exists($dir)){
					mkdir($dir) or error("Make dir error:{$dir}");
					chmod($dir,0777);
				}
			}
			*/
		}
		
		self::top();
	}
}

?>