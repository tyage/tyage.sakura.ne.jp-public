<?php
class MailsController extends AppController{
	var $uses = array('Mail','User');
	var $helpers = array('Mail');

	function index($lastId = -1){
		$this->set('mails', $this->_last($lastId));
	}

	function last($lastId = -1) {
		$this->set('mails', array_reverse($this->_last($lastId)));
	}

	function _last($lastId = -1) {
		// 受信メール
		$condition = array(
			'order' => 'Mail.created DESC',
			'conditions' => array(
				'To.id' => $this->Self->id,
				'Mail.id > ' => $lastId
			),
			'fields' => array(
				'Mail.id','Mail.title','Mail.body','Mail.created',
				'From.id','From.username','From.image'
			)
		);
		if ($lastId < 0) {
			$condition['limit'] = 10;
		}
		$receives = $this->Mail->find('all', $condition);

		// 送信メール
		$condition = array(
			'order' => 'Mail.created DESC',
			'conditions' => array(
				'From.id' => $this->Self->id,
				'Mail.id > ' => $lastId
			),
			'fields' => array(
				'Mail.id','Mail.title','Mail.body','Mail.created',
				'To.id','To.username','To.image'
			)
		);
		if ($lastId < 0) {
			$condition['limit'] = 10;
		}
		$sends = $this->Mail->find('all', $condition);
		
		$lastId = $receives[0]['Mail']['id'] > $sends[0]['Mail']['id'] ?
			$receives[0]['Mail']['id'] :
			$sends[0]['Mail']['id'];
		$this->set('lastId', empty($lastId) ? 0 : $lastId);
		return array(
			'receives' => $receives,
			'sends' => $sends
		);
	}

	function sends() {
		$this->links[] = array(
			'title' => 'メールボックス',
			'url' => '/mails/'
		);

		$this->paginate = array(
			'conditions' => array(
				'From.id' => $this->Self->id
			)
		);
		$this->set('sends', $this->paginate());
	}

	function receives() {
		$this->links[] = array(
			'title' => 'メールボックス',
			'url' => '/mails/'
		);

		$this->paginate = array(
			'conditions' => array(
				'To.id' => $this->Self->id
			)
		);
		$this->set('receives', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$user = $this->User->findByUsername($this->data['Mail']['to']);
			$this->data['Mail']['to'] = $user['User']['id'];

			$this->data['Mail']['from'] = $this->Self->id;

			$fieldList = array('title','body','to','from');
			$this->Mail->save($this->data, true, $fieldList);

			if (!$this->isAjax) {
				$this->redirect('/mails/');
			}
		}
	}
}