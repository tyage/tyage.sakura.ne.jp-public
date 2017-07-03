<?php
class Answer extends AppModel {
	var $belongsTo = array('User','Question');
	var $hasMany = array('Vote');
}