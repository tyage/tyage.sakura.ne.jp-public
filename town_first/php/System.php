<?php

/***** システム *****/
class System{
	
	static $command = array("cookie_delete");
	
	//----- クッキー消去 -----//
	static function cookie_delete(){
		setcookie("id");
		setcookie("pass");
		
		View::header();
		
		print <<<EOF
クッキーを削除致しました。
EOF;
	}
}

?>