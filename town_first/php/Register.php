<?php

/***** 新規登録 *****/
class Register{
	
	static $command = array("top","start");
	
	static $abilityRand = 0.1;//能力値の誤差（）
	static $average = array("max" => 500,"brain" => 50,"physical" => 50,"other" => 50);
	static $height = array("m" => 170,"f" => 160);
	static $weight = array("m" => 60,"f" => 50);
	static $town = "chubu";
  static $money = 5000000;
  static $move = "徒歩";
	
	//----- 新規登録画面 -----//
	static function top(){
		//キャラ画像
		foreach(getCharaImages() as $file){
			$charaImages .= "<option value='{$file}'>{$file}</option>";
		}
		
		for($i=1;$i<=12;$i++){
			$months .= "<option value='{$i}'>{$i}</option>";
		}
		for($i=1;$i<=31;$i++){
			$days .= "<option value='{$i}'>{$i}</option>";
		}
		
		View::header(array("jquery/jquery.js","basic.js","register.js"));
		
		print <<<EOF
<script type="text/javascript"><!-- 
\$(function(){
	set();
	\$register.set();
});
 --></script>
<div id="content">

<table width="90%">
<tr>
<td bgcolor=#ffffff>
ここであなたのキャラクターを登録することができます。<br>
名前（本名よりＨＮのほうが良）とパスワードと性別などを決めて入力してください。<br>
また、最初の能力値は指定値以下になるように振り分けてください。<br>
<font color="red">多重登録は禁止です。</font>
</td>
<td bgcolor="#333333" align="center" width="35%" valign="middle"><h1><font color="#ffffff">新規登録場</font></h1></td>
</tr></table><br>

<form action="?mode=Register&amp;command=start" method="POST" id="registerForm" class="justify">

<label>
	<h4>ＩＤ</h4>
	<ul>
		<li>入力必須</li>
		<li>登録後変更不可</li>
		<li>半角英数のみ</li>
	</ul>
</label>
<div>
	<input type="text" name="id" size="30" maxlength="20" class="required english">
</div>

<hr class="clear">

<label>
	<h4>名前</h4>
	<ul>
		<li>入力必須</li>
	</ul>
</label>
<div>
	<input type="text" name="name" size="30" maxlength="20" class="required">
</div>

<hr class="clear">

<label>
	<h4>パスワード</h4>
	<ul>
		<li>入力必須</li>
		<li>登録後変更不可</li>
	</ul>
</label>
<div>
	<input type="text" name="pass" size="30" maxlength="20" class="required">
</div>

<hr class="clear">

<label>
	<h4>メールアドレス</h4>
	<p></p>
</label>
<div>
	<input type="text" name="mail" size="50" class="mail">
</div>

<hr class="clear">

<label>
	<h4>性別</h4>
	<ul>
		<li>選択必須</li>
		<li>登録後変更不可</li>
	</ul>
</label>
<div>
	<input type="radio" name="sex" value="m" checked="checked">男
	<input type="radio" name="sex" value="f">女
</div>

<hr class="clear">

<label>
	<h4>誕生日</h4>
	<ul>
		<li>選択必須</li>
		<li>登録後変更不可</li>
	</ul>
</label>
<div>
	<select name="birthmonth">{$months}</select>月
	<select name="birthday">{$days}</select>日<br>
</div>

<hr class="clear">

<label>
	<h4>画像</h4>
	<ul>
		<li>選択必須</li>
	</ul>
</label>
<div>
	<select name="img" id="charaImageSelect">
	{$charaImages}
	</select>
  <img id="charaImage">
</div>

<hr class="clear">

<label></label><input type="submit" value="登録する">

</form>
EOF;
	
	}
	
	//----- 新規登録 -----//
	static function start(){
		$tmp = array(&$_POST,&$_SERVER["HTTP_USER_AGENT"]);
		m($tmp);
		$tmp = array(&$_POST["birthmonth"],&$_POST["birthday"]);
		i($tmp);
		
		if(empty($_POST["id"]) or empty($_POST["name"]) or empty($_POST["pass"]) or empty($_POST["sex"])) error("記入されていない部分があります。");
		
		if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST["id"])) error("IDに半角英数字以外の文字が含まれています。");
		if(!isIdAvailable($_POST["id"])) error("そのIDは使えません。");
		if(!isNameAvailable($_POST["name"])) error("その名前は使えません。");
		if(!empty($_POST["mail"]) and !preg_match("/^[A-Za-z0-9]+[\w-]+@[\w\.-]+\.\w{2,}$/",$_POST["mail"])) error("メールアドレスが不正かもしれません。");
		if($_POST["birthmonth"] > 12 or $_POST["birthmonth"] < 1) error("誕生月がおかしいです。");
		if($_POST["birthday"] > 31 or $_POST["birthday"] < 1) error("誕生日がおかしいです。");
		if($_POST["sex"] != "m" and $_POST["sex"] != "f") error("性別が中間です。");
		if(!in_array($_POST["img"],getCharaImages())) error("画像がおかしいです。");
		
		Ini::$ability_type["max"] = array("max_energy","max_spirit");
		foreach(self::$average as $type => $average){
			foreach(Ini::$ability_type[$type] as $ability){
				$abilities[$ability] = $average + rand(-$average*self::$abilityRand,$average*self::$abilityRand);
			}
		}
		
		$height = self::$height[$_POST["sex"]];
		$weight = self::$weight[$_POST["sex"]];
    
		//多重登録チェック
		$ip = get_ip();
		$doubleFlag = FALSE;
		m($ip);
		$query = mq("SELECT `id`,`ip` FROM `member` WHERE `ip` LIKE '%{$ip}%' AND `id` != 'test'");
		while($row = massoc($query)){
			m($row["id"]);
			$query = mq("SELECT * FROM `double` WHERE `id1` IN ('{$row["id"]}','{$_POST["id"]}') AND `id2` IN ('{$row["id"]}','{$_POST["id"]}')");
			if(massoc($query)) continue;
			
			//そのIPだけであれば
			if(count(unserialize($row["ip"])) <= 1){
				$doubleFlag =TRUE;
				break;
			}
			$double[] = $row["id"];
		}
		if($doubleFlag) error("多重登録禁止です。");
		
		$ip = serialize(array($ip => 1));
		
		$ini = Ini::load("CharaNo");
		$ini["CharaNo"] ++;
		Ini::save($ini);
		
		$time = serialize(array("start" => mkdate(),"reload" => mkdate(),"eat" => mkdate()));
		m($time);
		mq("INSERT INTO `member` (`id`,`name`,`pass`,`no`,`ip`,`sex`,`mail`,`birthday`,`img`,`time`,`town`,`money`,`height`,`weight`,`move`,`energy`,`spirit`,".makeColumns(array_keys($abilities)).") VALUES ('{$_POST["id"]}','{$_POST["name"]}','{$_POST["pass"]}','{$ini["CharaNo"]}','{$ip}','{$_POST["sex"]}','{$_POST["mail"]}','0000-{$_POST["birthmonth"]}-{$_POST["birthday"]}','{$_POST["img"]}','{$time}','".self::$town."',".self::$money.",{$height},{$weight},'".self::$move."',{$abilities["max_energy"]},{$abilities["max_spirit"]},".makeValues(array_values($abilities)).")");
		
		make_file(Ini::$dir."member/{$_POST["id"]}/");
		
		Gov::write_news("報告","{$_POST["name"]}さんが新しく入りました。");
		
		if($double){
			$double = implode(",",$double);
			Mail::write("tyage",array("type" => "報告","name" => $_POST["name"],"title" => "多重の可能性があります。","message" => "{$_POST["name"]}さんはID:{$double}と多重である可能性があります。"));
		}else{
			Mail::write("tyage",array("type" => "報告","name" => $_POST["name"],"title" => "登録しました。","message" => "{$_POST["name"]}さんが登録しました。"));
		}
		
		new Chara();
    Main::in();
    
		print <<<EOF
<script type="text/javascript"><!--
$(document).ready(function(){
	\$message.add("新規登録が完了しました！");
});
// --></script>
EOF;
	}
	
}

?>