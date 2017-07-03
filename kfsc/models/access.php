<?php
class Access extends AppModel {
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreign_key' => 'user_id'
		)
	);
}