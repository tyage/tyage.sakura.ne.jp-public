<?php
class Ip extends AppModel{
	var $belongsTo = array('User');

	var $validate = array(
		'ip' => array(
			'rule' => 'isUnique',
			'message' => '多重登録禁止です。'
		)
	);

}