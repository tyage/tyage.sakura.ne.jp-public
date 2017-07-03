<?php
class AppController extends Controller {
	var $components = array('Auth');
	var $helpers = array('Javascript','Form', 'Session');

	function beforeFilter() {
		$this->Auth->loginError = 'ユーザー名かパスワードが間違っとるよ！';
		$this->Auth->authError = 'ログインしてね！';
		$this->Auth->authenticate = ClassRegistry::init(__CLASS__); // 暗号化無効化
		$this->Auth->autoRedirect = false;

		if (!empty($this->params['admin'])) {
			$this->Auth->authorize = 'controller';
		}

		$this->set('user',$this->Auth->user());
	}

	function isAuthorized() {
		return $this->Auth->user('role') === 'admin';
	}

	// 暗号化無効化
	function hashPasswords($data){
		return $data;
	}
}