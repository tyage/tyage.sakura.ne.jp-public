<?php

/***** ヘルプ *****/
class Help{
	static $command = array("top");
	
	static function top(){
		View::header();
		
		print <<<EOF
まだできとらんよ。
EOF;
	}
}

?>