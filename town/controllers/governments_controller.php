<?php
class GovernmentsController extends AppController {
	var $uses = array('News','User');

	function index() {
		$codition = array(
			'order' => 'News.created DESC',
			'limit' => 50
		);
		$this->set('news', $this->News->find('all', $codition));
	}

}