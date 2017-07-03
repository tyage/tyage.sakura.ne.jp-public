<?php
class UserJob extends AppModel {
	var $belongsTo = array('User','Job');
}