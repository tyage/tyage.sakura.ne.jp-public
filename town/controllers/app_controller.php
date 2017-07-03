<?php
class AppController extends Controller{
	var $components = array('Session','Auth','Self','RequestHandler');
	var $helpers = array('Session','Html','Js','Form');
	var $uses = array('User','Profile','Entry');

	var $links = array(
		array('title' => '街','url' => '/')
	);

	var $autoLogout = 600;

	function beforeFilter(){
		if (isset($this->params['requested'])) {
			return false;
		}
		$this->_initializeAuth();
		$this->_ajax();
		$this->_entry();

		$this->set('self', $this->Auth->user());
	}

	function beforeRender() {
		if (empty($this->Self->id)) {
			$this->links = array();
		}
		$this->set('links', $this->links);

		$this->set('title_for_layout', '退化地方');
	}

	function _initializeAuth(){
		$this->Auth->autoRedirect = false;
		$this->Auth->loginError = 'ログインエラーです';
		$this->Auth->authError = '権限がありません';
	}

	// パスワードが空かどうかを判別
	function _validatePassword($password) {
		return $password != $this->Auth->password('');
	}

	function _ajax() {
		$this->isAjax = $this->RequestHandler->isAjax();
		$this->set('isAjax', $this->isAjax);
		if($this->isAjax) {
			$this->layout = 'ajax';
		}
	}

	function _entry() {
		$condition = array(
			'Entry.updated < ' => date('Y-m-d H:i:s', time() - $this->autoLogout)
		);
		$this->Entry->deleteAll($condition);
		if (!empty($this->Self->id)) {
			$entry = $this->Entry->findByUserId($this->Self->id);
			$data = array(
				'Entry' => array('id' => $entry['Entry']['id']),
				'User' => array('id' => $this->Self->id)
			);
			$this->Entry->saveAll($data);
		}
	}
}