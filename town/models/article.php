<?php
class Article extends AppModel {
	var $belongsTo = 'Blog';

	var $validate = array(
		'title' => 'notEmpty'
	);
}