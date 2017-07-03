<?php
class ThreadsController extends AppController {
	var $uses = array('Thread', 'Response');
	var $helpers = array('Paginator');

	function view($id) {
		$thread = $this->Thread->findById($id);
		$this->links[] = array(
			'title' => 'スレ一覧',
			'url' => '/threads/lists/'.$thread['Forum']['id']
		);
		$this->set('thread', $thread);

		$condition = array(
			'conditions' => array(
				'Forum.id' => $thread['Forum']['id']
			),
			'limit' => 10,
			'order' => 'Thread.created DESC'
		);
		$this->set('threads', $this->Thread->find('all', $condition));

		$this->paginate = array(
			'order' => 'Response.created DESC',
			'conditions' => array(
				'Thread.id' => $id
			),
			'limit' => 10
		);
		$this->set('responses', $this->paginate('Response'));
		$this->set('paginatorOption', array('url' => array($id)));
	}

	function add(){
		$this->data['Thread']['user_id'] = $this->Self->id;
		$fieldList = array(
			'forum_id','user_id','title'
		);
		$this->Thread->saveAll($this->data, array('fieldList' => $fieldList));
		$this->redirect('/forums/view/'.$this->data['Forum']['id']);
	}
}