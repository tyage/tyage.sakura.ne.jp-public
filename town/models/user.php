<?php
class User extends AppModel{
	var $hasOne = array('Profile','House');
	var $hasMany = array('Ip');

	var $validate = array(
		'username' => array(
			'isUnique',
			'notEmpty'
		),
		'img' => array(
			'rule' => 'validateImage',
			'message' => '正しい画像を選んでください。'
		)
	);

	var $abilities = array(
		'language','math','science','society',
		'arm','leg','quick','soft','beauty',
		'attent','skill','lucky'
	);

	var $startWeight = array(60,50); //男,女
	var $startHeight = array(170,160); //男,女

	function regist($data){
		$fieldList = am(array(
			'username','password','image',
			'sex','born','height','weight',
			'ip','user_id'
		),$this->abilities);

		$data['Profile']['weight'] = $this->startWeight[$data['Profile']['sex']];
		$data['Profile']['height'] = $this->startHeight[$data['Profile']['sex']];
		foreach($this->abilities as $ability){
			$data['Profile'][$ability] = 50;
		}

		$options = array(
			'fieldList' => $fieldList,
			'validate'=>'first'
		);
		return $this->saveAll($data,$options);
	}

	function del($id){
		uses('folder');
		$folder = new Folder(LOGS.DS.'member'.DS.$id);
		$folder->delete();

		parent::del($id);
	}

	function getBMI($user) {
		return $user['Profile']['height'] > 0 ?
			$user['Profile']['weight'] / ($user['Profile']['height']/100) / ($user['Profile']['height']/100) :
			false;
	}

	function getMaxEnergy ($user) {
		$elements = array('arm','leg','quick','soft');
		$energy = 0;
		foreach ($elements as $element) {
			$energy += $user['Profile'][$element];
		}
		return $energy / count($elements);
	}

	function getMaxSpirit($user) {
		$elements = array('language','math','science','society');
		$energy = 0;
		foreach ($elements as $element) {
			$energy += $user['Profile'][$element];
		}
		return $energy / count($elements);
	}

	function getImages(){
		uses('folder');
		$folder = new Folder(WWW_ROOT.'img'.DS.'chara');
		list($dirs,$files) = $folder->read();
		return $files;
	}

	function validateImage($data){
		return in_array($data['img'],$this->getImages());
	}
}