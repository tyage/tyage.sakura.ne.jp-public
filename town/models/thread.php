<?php
class Thread extends AppModel {
	var $belongsTo = array('User','Forum');

	var $validation = array(
		'title' => 'notEmpty'
	);
}