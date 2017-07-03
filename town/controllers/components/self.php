<?php
class SelfComponent extends Object{
	var $id = null;

	function initialize(&$controller){
		$this->controller =& $controller;
		$this->id = $this->controller->Auth->user('id');
	}

	function saveProfile($data, $validate = true, $fieldList = array()) {
		$profile = $this->controller->Profile->findByUserId(
			$this->id,
			array('fields' => 'id')
		);
		$this->controller->Profile->id = $profile['Profile']['id'];
		$this->controller->Profile->save($data, $validate, $fieldList);
	}

	function saveUser($data, $validate = true, $fieldList = array()) {
		$this->controller->User->id = $this->id;
		if (
			empty($data['User']['password']) or
			$this->controller->_validatePassword($data['User']['password'])
		) {
			$this->controller->User->save($data, $validate, $fieldList);
		}

		$user = $this->controller->User->read();
		$data = array(
			'User' => array(
				'username' => $user['User']['username'],
				'password' => $user['User']['password']
			)
		);
		$this->controller->Auth->logout();
		$this->controller->Auth->login($data);
	}

	function load($fields = array()) {
		$conditions = array(
			'conditions' => array(
				'User.id' => $this->id
			)
		);
		if (!empty($fields)) {
			$conditions['fields'] = $fields;
		}

		return $this->controller->User->find('first', $conditions);
	}
}