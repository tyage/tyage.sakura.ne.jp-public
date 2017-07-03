<?php
class UserItem extends AppModel{
	var $belongsTo = array('Item','User');
}