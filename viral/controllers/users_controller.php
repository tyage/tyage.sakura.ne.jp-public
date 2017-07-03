<?php
class UsersController extends AppController {
	function is_registered() {
		$isRegistered = $this->User->isRegistered($this->passedArgs['sim']);
		$this->_renderJson(compact('isRegistered'));
	}
	
	function add() {
		if (!$this->User->isRegistered($this->passedArgs['sim'])) {
			$data = array(
				'User' => $this->passedArgs
			);
			$this->User->save($data);
		}
	}
	
	function login() {
		$user = $this->User->findBySim($this->passedArgs['sim']);
		$this->User->id = $user['User']['id'];
		$data = array(
			'User' => array(
				'online' => true
			)
		);
		$this->User->save($data);
		
		$this->_renderJson(compact('user'));
	}
	function logout() {
		$user = $this->User->findBySim($this->passedArgs['sim']);
		$this->User->id = $user['User']['id'];
		$data = array(
			'User' => array(
				'online' => false
			)
		);
		$this->User->save($data);
	}
	
	function lists() {
		$condition = array(
			'conditions' => array(
				'User.phone' => json_decode($this->passedArgs['phone'])
			)
		);
		$users = $this->User->find('all', $condition);
		$this->_renderJson(compact('users'));
	}
}