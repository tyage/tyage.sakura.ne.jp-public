<?php
class ReportsController extends AppController {
	var $uses = array('Report','News');
	var $helpers = array('Paginator');
	var $components = array('Security');

	var $paginate = array(
		'order' => 'Report.created DESC'
	);

	function beforeFilter() {
		$this->Security->requireAuth('admin_add','admin_edit','admin_delete');
		$this->Auth->allow('index','view');
		parent::beforeFilter();
	}

	function index() {
		$this->set('reports', $this->paginate());
	}
	function view($id) {
		$this->set('report',$this->Report->findById($id));
	}
	function admin_index() {
		$this->set('reports', $this->paginate());
	}
	function admin_add() {
		if (!empty($this->data)) {
			if ($this->Report->save($this->data) and $this->News->save($this->data)) {
				$this->redirect('/admin/reports/');
			}
		}
	}
	function admin_edit($id) {
		$this->Report->id = $id;
		if (empty($this->data)) {
			$this->data = $this->Report->read();
		} else {
			if ($this->Report->save($this->data)) {
				$this->redirect('/admin/reports/');
			}
		}
	}
	function admin_delete($id) {
		$this->Report->delete($id);
		$this->redirect('/admin/reports/');
	}
}