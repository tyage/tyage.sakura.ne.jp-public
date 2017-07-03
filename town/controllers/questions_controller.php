<?php
class QuestionsController extends AppController{
	var $uses = array('Question','Answer','Vote');

	function index() {
		$this->paginate = array(
			'order' => 'Question.created DESC'
		);
		$this->set('questions', $this->paginate());
	}

	function add() {
		$this->links[] = array(
			'title' => 'アンケート',
			'url' => '/questions/'
		);
		
		if (!empty($this->data)) {
			$this->data['User']['id'] = $this->Self->id;
			$fieldList = array(
				'Question' => array('title','message','choice','add'),
				'User' => array('id')
			);
			$this->Question->saveAll($this->data, $fieldList);
			
			$this->redirect('/questions/');
		}
	}
	
	function edit($id) {
		$this->links[] = array(
			'title' => 'アンケート',
			'url' => '/questions/'
		);
		
		$this->Question->id = $id;
		$question = $this->Question->read();
		if ($question['User']['id'] !== $this->Self->id) {
			$this->redirect('/questions/');
		}

		if (empty($this->data)) {
			$this->data = $question;
		} else {
			$this->Question->save($this->data, array('title','message','choice','add'));
		}
	}
	
	function delete($id) {
		$question = $this->Question->findById($id);
		if ($question['User']['id'] === $this->Self->id) {
			$this->Question->delete($id);
		}
		$this->redirect('/questions/');
	}

	function view($id) {
		$this->links[] = array(
			'title' => 'アンケート',
			'url' => '/questions/'
		);
		
		$this->set('question', $this->Question->findById($id));
		$this->set('answers', $this->Answer->findAllByQuestionId($id));
		$condition = array(
			'conditions' => array(
				'Vote.user_id' => $this->Self->id
			),
			'fields' => array('Vote.answer_id')
		);
		$this->set('choices', $this->Vote->find('list', $condition));
	}

	function vote() {
		$condition = array(
			'conditions' => array(
				'Answer.question_id' => $this->data['Question']['id']
			)
		);
		$answerIds = $this->Answer->find('list', $condition);

		$conditions = array(
			'Vote.user_id' => $this->Self->id,
			'Vote.answer_id' => $answerIds
		);
		$this->Vote->deleteAll($conditions);

		$data = array();
		foreach ($this->data['Answer']['id'] as $id) {
			if ($id > 0) {
				$data[] = array(
					'answer_id' => $id,
					'user_id' => $this->Self->id
				);
			}
		}
		$this->Vote->saveAll($data);

		$this->redirect('/questions/view/'.$this->data['Question']['id']);
	}
}
