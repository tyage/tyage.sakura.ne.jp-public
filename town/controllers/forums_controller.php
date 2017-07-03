<?php
class ForumsController extends AppController {
	var $uses = array('Forum', 'Thread');

	function add($houseId) {
		$this->Forum->House->id = $houseId;
		$house = $this->Forum->House->read();
		if ($house['User']['id'] !== $this->Self->id) {
			return false;
		}

		if (!empty($this->data)) {
			if ($this->Forum->save($this->data)) {
				$this->Forum->House->save(
					array(
						'forum_id' => $this->Forum->id
					)
				);

				$this->redirect('/houses/edit/'.$houseId);
			}
		}

		$this->set('houseId', $houseId);
	}

	function view($id = 1) {
		$forum = $this->Forum->findById($id);
		if (!empty($forum['House']['id'])) {
			$this->links[] = array(
				'title' => '家',
				'url' => '/houses/view/'.$forum['House']['id']
			);
		}
		$this->set('forum', $forum);

		$this->paginate = array(
			'order' => 'Thread.updated DESC',
			'conditions' => array(
				'Forum.id' => $id
			)
		);
		$this->set('threads', $this->paginate('Thread'));
	}
}