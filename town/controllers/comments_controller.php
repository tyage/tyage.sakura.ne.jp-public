<?php
class CommentsController extends AppController {
	var $uses = array('Article','Blog','Comment');

	function add() {
		if (!empty($this->data)) {
			$this->data['Comment']['user_id'] = $this->Self->id;
			$fieldList = array('article_id','user_id','body');
			$this->Comment->save($this->data, true, $fieldList);
		}
		$this->redirect('/articles/view/'.$this->data['Comment']['article_id']);
	}
}