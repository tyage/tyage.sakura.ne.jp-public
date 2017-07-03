<?php
class User extends AppModel {
	//var $hasMany = 'Message';
	
	function isRegistered($sim) {
		$user = $this->findBySim($sim);
		return $user['User']['id'];
	}
}