<?php
class AnswersController extends AppController {
	function add() {
		$this->data['User']['id'] = $this->Self->id;
		$fieldList = array('question_id','user_id','body');
		$this->Answer->saveAll($this->data, array('fieldList' => $fieldList));
		$this->redirect('/questions/view/'.$this->data['Question']['id']);
	}
}