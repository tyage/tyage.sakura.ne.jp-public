<?php
class MessagesController extends AppController {
	var $uses = array('Message', 'GroupsUser');
	
	function add() {
		$data = array(
			'Message' => $this->params
		);
		$this->Message->save($data);
	}
	
	function last() {
		$groups = $this->Message->Group->userGroup($this->params['user_id']);
		$condition = array(
			'conditions' => array(
				'Message.created >= ' => $this->params['time'],
				'Message.group_id' => $groups
			)
		);
		$messages = $this->Message->find('all', $condition);
		
		$this->_renderJson(compact('messages'));
	}
}