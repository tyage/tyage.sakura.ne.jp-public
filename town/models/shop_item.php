<?php
class ShopItem extends AppModel{
	var $belongsTo = array('Item','Shop');
}