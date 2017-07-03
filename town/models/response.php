<?php
class Response extends AppModel {
	var $belongsTo = array('User','Thread');

	var $validation = array(
		'body' => array(
			'rule' => 'notEmpty',
			'on' => 'create'
		)
	);

}