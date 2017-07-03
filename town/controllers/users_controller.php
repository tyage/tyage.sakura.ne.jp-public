<?php
class UsersController extends AppController {
	var $uses = array('User','Town','House','UserJob');
	var $components = array('Cookie','Auth');
	var $helpers = array('Exform');

	function beforeFilter() {
		$this->Cookie->time = '+2 weeks';
		$this->Auth->allow('add','view');
		parent::beforeFilter();
	}

	function abilities() {
		return $this->User->abilities;
	}

	function index() {
		$this->links = array();
		$town = $this->Self->load('Profile.town_id');
		$this->set('town', $this->Town->findById($town['Profile']['town_id']));
	}

	function view($id) {
		$user = $this->User->findById($id);
		$this->set('user', $user);
		$this->set('abilities', $this->User->abilities);
		$this->set('town', $this->Town->findById($user['Profile']['town_id']));
		$this->set('userJob', $this->UserJob->findById($user['Profile']['user_job_id']));
	}

	function login() {
		$this->set('town', $this->Town->findById(rand(1,9)));

		if (!empty($this->data) and $this->Auth->login()) {
			$this->Cookie->write($this->Auth->sessionKey, $this->data['User']);
		} else {
			$cookie = $this->Cookie->read($this->Auth->sessionKey);
			$this->Auth->login($cookie);
		}

		if ($this->Auth->user()) {
			$this->redirect($this->Auth->redirect());
		}
	}

	function logout(){
		$condition = array(
			'User.id' => $this->Self->id
		);
		$this->Entry->deleteAll($condition);

		$this->Cookie->delete($this->Auth->sessionKey);
		$this->redirect($this->Auth->logout());
	}

	function add(){
		$this->set('images', $this->User->getImages());

		if (!empty($this->data)) {
			$this->data['Ip'] = array(
				array('ip' => $this->RequestHandler->getClientIP())
			);

			if (!$this->_validatePassword($this->data['User']['password'])) {
				$this->User->invalidate('password', 'パスワードを入力してください。');
			} elseif ($this->User->regist($this->data)) {
				new Folder(LOGS.DS.'users'.DS.$this->Auth->user('id'),true);
				$this->Auth->login($this->data);

				$this->redirect($this->Auth->redirect());
			}
			$this->data['User']['password'] = ''; //暗号化しているため
		}
	}

	function edit(){
		$this->set('images', $this->User->getImages());

		if (!empty($this->data)) {
			$this->Self->saveUser($this->data, true, array('username','image'));
			$this->Self->saveProfile($this->data, true, array('email'));
		} else {
			$this->data = $this->Self->load();
		}
	}

	function editPassword() {
		if (!empty($this->data)) {
			$user = $this->Self->load('User.password');
			if ($user['User']['password'] != $this->Auth->password($this->data['User']['pre_password'])) {
				$this->User->invalidate('pre_password', 'パスワードが違うよ');
			} else {
				$this->data['User']['password'] = $this->Auth->password($this->data['User']['password']);
				$this->Self->saveUser($this->data, true, array('password'));
			}
		}
		$this->data = null;
	}

	function delete(){
		$this->User->delete($this->Auth->user('id'));
	}

	function rank(){
		$this->links[] = array(
			'title' => '役場',
			'url' => '/governments/'
		);
		
		// Houseモデルとの関連があると同一ユーザーが複数出現する
		$this->User->unbindModel(
			array(
				'hasOne' => array('House')
			),
			false
		);
		
		$this->set('abilities', $this->User->abilities);
		$this->set('users', $this->paginate());
	}
	
	function move ($x, $y) {
		$data = array(
			'Profile' => array(
				'x' => $x,
				'y' => $y
			)
		);
		$this->Self->saveProfile($data);
	}
}
