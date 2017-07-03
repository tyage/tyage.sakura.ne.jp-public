<?php
class ItemsController extends AppController {
	var $uses = array('Item','User');

	function index() {
		$this->set('items', $this->paginate());
		$this->set('abilities', $this->User->abilities);
	}
}