<?php
class EntriesController extends AppController {
	function beforeFilter(){
		$this->Auth->allow('index');
		parent::beforeFilter();
	}

	function index() {
		$entries = $this->Entry->find('all');
		if (isset($this->params['requested'])) {
			return $entries;
		} else {
			$this->set('entries', $entries);
		}
	}
}