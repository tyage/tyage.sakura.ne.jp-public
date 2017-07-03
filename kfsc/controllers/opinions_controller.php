<?php
class OpinionsController extends AppController {
	var $uses = array('Topic','Opinion');
	var $components = array('Security');

	function beforeFilter() {
		$this->Security->requireAuth('add');
	}

	function add() {
		$topic = $this->Topic->findById($this->data['Topic']['id']);
		if (!empty($this->data) and $topic['Topic']['created'] > $this->Auth->user('created')) {
			$user = $this->Auth->user();
			$this->data['User'] = $user['User'];

			$this->Opinion->saveAll($this->data);
		}
		$this->redirect('/topics/view/'.$this->data['Topic']['id']);
	}
}