<?php
class Question extends AppModel {
	var $belongsTo = array('User');
	var $hasMany = array('Answer');
}