<?php
class StoreItem extends AppModel{
	var $belongsTo = array('Item','Store');
}