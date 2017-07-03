<?php
class ChatsController extends AppController{
	var $helpers = array('Chat');

	function index($lastId = -1){
		$this->set('messages', $this->_last($lastId));
	}

	function last($lastId = -1) {
		$this->set('messages', array_reverse($this->_last($lastId)));
	}

	function _last($lastId) {
		$condition = array(
			'order' => 'Chat.created DESC',
			'limit' => 50,
			'conditions' => array(
				'Chat.id > ' => $lastId
			),
			'fields' => array(
				'Chat.id','Chat.body','Chat.created',
				'User.id','User.username','User.image'
			)
		);
		$messages = $this->Chat->find('all', $condition);

		$this->set('lastId', empty($messages[0]) ? 0 : $messages[0]['Chat']['id']);

		return $messages;
	}

	function add() {
		if (!empty($this->data)) {
			$this->data['Chat']['user_id'] = $this->Self->id;
			if ($this->Chat->save($this->data, true, array('body','user_id'))) {
				$bonus = rand(10,100);
				$this->set('bonus', $bonus);

				$user = $this->Self->load('Profile.money');
				$user['Profile']['money'] += $bonus;
				$this->Self->saveProfile(
					array(
						'money' => $user['Profile']['money']
					)
				);
			}
			$this->set('bonus', $bonus);
		}

		if (!$this->isAjax) {
			$this->redirect('/chats/');
		}
	}

}