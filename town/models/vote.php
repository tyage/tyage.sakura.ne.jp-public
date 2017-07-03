<?php
class Vote extends AppModel {
	var $belongsTo = array('User','Answer');
}