<?php
class NewsController extends AppController {
	var $components = array('Security');
	var $helpers = array('Paginator');

	var $paginate = array(
		'order' => 'News.created DESC'
	);

	function beforeFilter() {
		$this->Security->requireAuth('admin_add','admin_edit','admin_delete');
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	function index() {
		$this->set('news', $this->paginate());
	}
	function admin_index() {
		$this->set('news', $this->paginate());
	}
	function admin_add() {
		if (!empty($this->data)) {
			if ($this->News->save($this->data)) {
				$this->redirect('/admin/news/');
			}
		}
	}
	function admin_edit($id) {
		$this->News->id = $id;
		if (empty($this->data)) {
			$this->data = $this->News->read();
		} else {
			if ($this->News->save($this->data)) {
				$this->redirect('/admin/news/');
			}
		}
	}
	function admin_delete($id) {
		$this->News->delete($id);
		$this->redirect('/admin/news/');
	}
}