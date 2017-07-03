<?php
class ResponsesController extends AppController {
	var $uses = array('Response','Thread');
	var $helpers = array('Paginator');

	function add() {
		$this->data['User']['id'] = $this->Self->id;
		$fieldList = array('thread_id','user_id','body');
		$this->Response->saveAll($this->data, array('fieldList' => $fieldList));

		$user = $this->Self->load('Profile.money');
		$earn = rand(1000,5000);
		$this->Self->saveProfile(
			array(
				'money' => $earn + $user['Profile']['money']
			)
		);

		$this->redirect('/threads/view/'.$this->data['Thread']['id']);
	}
}