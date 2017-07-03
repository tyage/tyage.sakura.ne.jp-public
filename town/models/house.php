<?php
class House extends AppModel {
	var $belongsTo = array('Forum','Shop','Blog','Store','User','Town');

	var $validate = array(
		'image' => array(
			'rule' => 'validateImage',
			'message' => '画像を選択してください'
		),
		'x' => array(
			'rule' => array('range', -1, 16),
			array(
				'rule' => 'notEmpty',
				'on' => 'create'
			)
		),
		'y' => array(
			'rule' => array('range', -1, 16),
			array(
				'rule' => 'notEmpty',
				'on' => 'create'
			)
		)
	);

	var $roomCost = 1000;
	var $rooms = array(
		'Forum' => array(
			'name' => '掲示板',
			'view' => '/forums/view/',
			'add' => '/forums/add/'
		),
		'Shop' => array(
			'name' => '売店',
			'view' => '/shops/buy/',
			'add' => '/shops/add/'
		),
		'Blog' => array(
			'name' => 'ブログ',
			'view' => '/articles/index/',
			'add' => '/blogs/add/'
		),
		'Store' => array(
			'name' => '倉庫',
			'view' => '/stores/out/',
			'add' => '/stores/add/'
		)
	);

	function getImages(){
		uses('folder');
		$folder = new Folder(WWW_ROOT.'img'.DS.'house');
		list($dirs,$files) = $folder->read();
		return $files;
	}

	function validateImage($data){
		return in_array($data['image'], $this->getImages());
	}

}