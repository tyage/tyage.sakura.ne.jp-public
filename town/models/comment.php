<?php
class Comment extends AppModel {
	var $belongsTo = array('User','Article');

	var $validate = array(
		'body' => array(
			'rule' => 'notEmpty',
			'on' => 'create'
		)
	);

}