<?php
class HelpsController extends AppController{
	var $helpers = array('Html','Folder');
	var $uses = array();

	function beforeFilter(){
		$this->Auth->allow('display');
		parent::beforeFilter();
	}

	function display(){
		$this->layout = 'help';

		// copy from cake/libs/controller/pages_controller
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title'));
		// end of copy

		$path = join('/',$path);
		$this->set('current',$path);
		$this->render($path);
	}
}