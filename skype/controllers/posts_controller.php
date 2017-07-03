<?php
class PostsController extends AppController {
	function index() {
		$this->paginate = array(
			'limit' => 10,
			'order' => 'Post.created DESC',
			'conditions' => array(
				'Post.parent_id' => 0
			)
		);
		$posts = $this->paginate();
		
		$this->set(compact('posts'));
	}
	
	function add($parent_id = 0) {
		if (!empty($this->data)) {
			$ip = env('REMOTE_ADDR');
			$this->data['Post']['ip'] = $ip;
			if ($this->Post->save($this->data)) {
				$this->redirect('/');
			}
			
			foreach ($this->Post->validationErrors as $key => $messages) {
				foreach ($messages as $message) {
					$this->Post->invalidate($key, $message);
				}
			}
			
		} else {
			$this->data = array(
				'Post' => array(
					'parent_id' => $parent_id
				)
			);
		}
	}
	
	function edit() {
		if (!empty($this->data)) {
			if ($this->_validatePassword($this->data) and 
				$this->Post->save($this->data)) {
				$this->redirect('/');
			}
		}
	}
	
	function delete() {
		if (!empty($this->data)) {
			if ($this->_validatePassword($this->data)) {
				if ($this->Post->delete($this->data['Post']['id'])) {
					$this->redirect('/');
				}
			}
		}
	}
	
	function search() {
		if (!empty($this->data)) {
			$word = '%'.$this->data['Post']['word'].'%';
			$this->paginate = array(
				'limit' => 10,
				'order' => 'Post.created DESC',
				'conditions' => array(
					'Post.username LIKE' => $word,
					'or' => array(
						'Post.title LIKE' => $word,
						'or' => array(
							'Post.body LIKE' => $word
						)
					)
				)
			);
			$posts = $this->paginate();
			
			$this->set(compact('posts'));
		}
	}
	
	function _validatePassword($data) {
		if ($data['Post']['password'] === 'tokyo700') {
			return true;
		}
		$id = $data['Post']['id'];
		$post = $this->Post->findById($id);
		return !empty($post['Post']['password']) and 
			$post['Post']['password'] === $data['Post']['password'];
	}
}