<?php
class GroupsController extends AppController {
	var $uses = array('Group', 'Message');

	function add() {
		$data = array(
			'Group' => array(
				'parent_id' => $this->params['parent_id'],
				'title' => $this->params['title']
			)
		);
		$this->Group->save($data);
		$id = $this->Group->getLastInsertId();
		
		$condition = array(
			'conditions' => array(
				'User.id' => json_decode($this->params['users']);
			)
		);
		$users = $this->Group->User->find('all', $condition);
		foreach ($users as $user) {
			$data = array(
				'user_id' => $user['User']['id'],
				'group_id' => $id
			);
			$this->GroupsUser->save($data);
		}
	}
	
	function view() {
		$group = $this->Group->findById($this->params['id']);
		
		$condition = array(
			'conditions' => array(
				'Group.id' => $this->params['id']
			)
		);
		$messages = $this->Message->find('all', $condition);
		
		$this->set(compact('group', 'messages'));
	}
}