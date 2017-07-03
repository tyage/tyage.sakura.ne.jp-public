<?php

class Style{
	static $command = array("js","css");

	//----- JS -----//
	static function js(){
		$js = unserialize(str_replace('\"','"',urldecode($_GET["js"])));

		if(!$js) exit();
		if(!is_array($js)) $js = array($js);

		foreach($js as $file){
			$file = Ini::$dir."js/".$file;
			self::file_sanitize($file);
			if(file_exists($file)) $code .= file_get_contents($file);
		}

		//$code = str_replace("\r\n","\r",$code);
		//$code = str_replace("\r","\n",$code);
		$code = preg_replace("/\/\/.*\n/","",$code);

		header("Content-Type:text/javascript; charset=UTF-8");
		print str_replace(array("\t"),array("",""),$code);
	}

	//----- CSS -----//
	static function css(){
		$css = unserialize(str_replace('\"','"',urldecode($_GET["css"])));

		$my = new User();
		$my->setup();
		if(!$my->checkPass()) $css = array("type" => "default");

		$pass = Ini::$dir;
		switch($css["type"]){
			case "original":
				$pass .= "member/{$my->id}/css/original/{$css["id"]}.css";
				break;
			case "theme":
				$pass .= "log/css/{$css["id"]}.css";
				break;
			default:
				$pass .= "log/css/0.css";
				break;
		}
		self::file_sanitize($pass);
		if(file_exists($pass)) $code .= file_get_contents($pass).$code;

		$code = str_replace("@import","",$code);
		$code = str_replace("\r\n","\r",$code);
		$code = str_replace("\r","\n",$code);
		$code = preg_replace("/\/\*---.*---\*\//","",$code);

		header("Content-Type:text/css; charset=UTF-8");
		print "@charset 'utf-8';".str_replace(array("\t","\n"," +: +"," +;"),array("","",":",";"),$code);
	}

	private static function file_sanitize(&$data){
		$data = str_replace("\0","",$data);
		if(preg_match("/\.\./",$data)) exit();
	}

	static function analyze(){
		$parentchild = array();
		//--- 解析 ---//
		preg_match_all("/(.*){(.*)}/",$code,$matches);
		foreach($matches as $match){
			preg_match_all("/(.*):(.*);/",$match[2],$values);

			if($ie and preg_match_all("/(.*) */",$match[1],$selecters)){

			}
			foreach($values as $value){
				$ele[$match[1]][$value[1]] = $value[2];
			}
		}

	}
}

?>