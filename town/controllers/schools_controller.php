<?php
class SchoolsController extends AppController{
	var $uses = array('School', 'User');

	function index() {
		$this->set('abilities', $this->User->abilities);
		$this->set('schools', $this->paginate());

		$user = $this->Self->load();
		$this->set('rest', strtotime($user['Profile']['study']) - time());
		$this->set('user', $user);
	}

	function study() {
		$school = $this->School->findById($this->data['School']['id']);

		$user = $this->Self->load();
		$user['Profile']['money'] -= $school['School']['price'];
		foreach ($this->User->abilities as $ability) {
			$user['Profile'][$ability] += $school['School'][$ability];
		}
		if (
			$user['Profile']['money'] >= 0 and
			$user['Profile']['spirit'] >= 0 and
			$user['Profile']['energy'] >= 0 and
			$user['Profile']['study'] <= date('Y-m-d H:i:s')
		) {
			$user['Profile']['study'] = date('Y-m-d H:i:s', time() + $school['School']['time']);
			$this->Self->saveProfile($user);
		}

		$this->redirect('/schools/');
	}
}