<?php
class Message extends AppModel {
	var $belongsTo = array('User', 'Group');
}