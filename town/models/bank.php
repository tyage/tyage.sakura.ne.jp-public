<?php
class Bank extends AppModel {
	var $belongsTo = array('User');

	var $validate = array(
		'rest' => array(
			'rule' => array('range',0)
		)
	);

	function log($data) {
		$user = $data['user'];
		$data = array(
			'User' => array(
				'id' => $user['User']['id']
			),
			'Bank' => array(
				'work' => $data['work'],
				'amount' => $data['amount'],
				'rest' => $user['Profile']['bank'] + $data['amount']
			)
		);
		return $this->saveAll($data);
	}

}