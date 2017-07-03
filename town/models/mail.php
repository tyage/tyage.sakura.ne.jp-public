<?php
class Mail extends AppModel {
	var $belongsTo = array(
		'From' => array(
			'className' => 'User',
			'foreignKey' => 'from'
		),
		'To' => array(
			'className' => 'User',
			'foreignKey' => 'to'
		)
	);

}
