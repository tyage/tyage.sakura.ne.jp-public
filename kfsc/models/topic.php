<?php
class Topic extends AppModel {
	var $hasMany = array(
		'Opinion' => array(
			'className' => 'Opinion',
			'foreign_key' => 'topic_id',
			'order' => array('Opinion.created ASC'),
			'dependent' => true
		)
	);
}