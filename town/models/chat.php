<?php
class Chat extends AppModel {
	var $belongsTo = array(
		'User',
		'Profile' => array(
			// User.id == Profile.idだからOK?
			'foreignKey' => 'user_id',
			'className' => 'Profile'
		)
	);

	var $validate = array(
		'body' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty'
			),
			'maxlength' => array(
				'rule' => array('maxLength', 100)
			)
		)
	);
}