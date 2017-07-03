<?php
class BuyerItem extends AppModel{
	var $belongsTo = array('Item','Buyer');
}