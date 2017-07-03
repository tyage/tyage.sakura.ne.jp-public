<?php
class UsersController extends AppController {
	var $uses = array('User','Access');
	var $components = array('RequestHandler','Security');

	var $paginate = array(
		'order' => 'User.id'
	);

	function beforeFilter() {
		$this->Security->requireAuth('admin_add','admin_edit','admin_delete');
		parent::beforeFilter();
	}

	function _access() {
		$this->Access->save(
			array(
				'user_id' => $this->Auth->user('id'),
				'ip' => $this->RequestHandler->getClientIP()
			)
		);
	}

	function login() {
		if ($this->Auth->user()) {
			$this->_access();
			$this->redirect($this->Auth->redirect());
		}
	}

	function logout() {
		$this->redirect($this->Auth->logout());
	}

	function admin_login() {
		$this->redirect('/users/login');
	}
	function admin_add() {
		if ($this->data) {
			if ($this->User->save($this->data)) {
				$this->redirect('/admin/users/');
			}
		}
	}

	function admin_delete($id) {
		$this->User->delete($id);
		$this->redirect('/admin/users/');
	}

	function admin_edit($id) {
		$this->User->id = $id;
		if (empty($this->data)) {
			$this->data = $this->User->read();
		} else {
			$this->data = $this->Auth->hashPasswords($this->data);
			if ($this->User->save($this->data)) {
				$this->redirect('/admin/users/');
			}
		}
	}

	function admin_index() {
		$this->set('users', $this->paginate());
	}
}