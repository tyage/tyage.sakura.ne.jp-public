<?php
class AccessesController extends AppController {
	var $helpers = array('Paginator');

	var $paginate = array(
		'order' => 'Access.created DESC'
	);
	
	function admin_index() {
		$this->set('accesses', $this->paginate());
	}
}