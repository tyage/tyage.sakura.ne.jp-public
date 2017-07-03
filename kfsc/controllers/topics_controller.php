<?php
class TopicsController extends AppController {
	var $uses = array('Topic','Opinion');
	var $helpers = array('Comment','Javascript');
	var $components = array('Security');

	var $paginate = array(
		'order' => 'Topic.updated DESC'
	);

	function beforeFilter() {
		$this->Security->requireAuth('admin_add','admin_edit','admin_delete');
		parent::beforeFilter();
	}

	function index() {
		$this->set('topics', $this->paginate());
	}
	function view($id) {
		//議題情報
		$topic = $this->Topic->findById($id);
		$this->set('topic',$topic);

		// 学校別に初回投稿順で表示
		$schools = array();
		foreach($topic['Opinion'] as $opinion) {
			$schools[$opinion['user_id']] = $opinion;
		}
		$this->set('schools',$schools);

		// 意見一覧
		$opinions = array();
		$condition = array(
			'conditions' => array('Opinion.topic_id' => $id),
			'order' => array('Opinion.created DESC')
		);
		$opinion = $this->Opinion->find('all',$condition);
		foreach ($opinion as $o) {
			$opinions[$o['Opinion']['user_id']][] = $o;
		}
		$this->set('opinions',$opinions);
	}

	function admin_add() {
		if (!empty($this->data)) {
			if ($this->Topic->save($this->data)) {
				$this->redirect('/admin/topics/');
			}
		}
	}
	function admin_delete($id) {
		$this->Topic->delete($id,true);
		$this->redirect('/admin/topics/');
	}
	function admin_edit($id) {
		$this->Topic->id = $id;
		if (empty($this->data)) {
			$this->data = $this->Topic->read();
		} else {
			if ($this->Topic->save($this->data)) {
				$this->redirect('/admin/topics/');
			}
		}
	}
	function admin_index() {
		$this->set('topics', $this->paginate());
	}
}