<?php
class Database {
	static $connect = array(
		'host' => '',
		'login' => 'tyage',
		'password' => '',
		'database' => 'tyage',
		'encoding' => 'utf8'
	);
	
	static function connect () {
		$connect = Database::$connect;
		try {
			$pdo = new PDO(
				'mysql:host='.$connect['host'].'; dbname='.$connect['database'], 
				$connect['login'], 
				$connect['password']
			);
		} catch (PDOException $e) {
			var_dump($e->getMessage());
		}
		
		$pdo->prepare('SET NAMES '.$connect['encoding'])->execute();
		mysql_set_charset($connect['encoding']);
		
		return $pdo;
	}
}

function h($text, $charset = null) {
	if (is_array($text)) {
		return array_map('h', $text);
	}

	return htmlspecialchars($text, ENT_QUOTES);
}

function ellipsis ($text, $length = 25) {
	return mb_strimwidth($text, 0, $length, '...', 'UTF-8');
}