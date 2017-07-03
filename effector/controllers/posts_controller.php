<?php
class PostsController extends AppController {
	function index($category_id = null) {
		$this->paginate = array(
			'limit' => 10,
			'order' => 'Post.created DESC'
		);
		
		if ($category_id) {
			$this->paginate['conditions'] = array(
				'Post.category_id' => $category_id
			);
		}
		
		if (!empty($this->data)) {
			$word = '%'.$this->data['Post']['word'].'%';
			$this->paginate['conditions'] = array(
				'or' => array(
					'Post.name LIKE' => $word,
					'Post.cause LIKE' => $word,
					'Post.instrument LIKE' => $word,
					'Post.review LIKE' => $word
				)
			);
		}
		
		$posts = $this->paginate();
		
		$categories = $this->Post->Category->find('all');
		
		$this->set(compact('posts', 'categories'));
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Post->save($this->data)) {
				$this->redirect('/');
			}
		}
		
		$categories = $this->_categoryList();
		$this->set(compact('categories'));
	}
	
	function edit($id) {
		if (!empty($this->data)) {
			$id = $this->data['Post']['id'];
			if ($this->Post->validatePassword($id, 
				$this->data['Post']['password'])) {
				unset($this->data['Post']['password']);
				$this->Post->save($this->data);
			}
		}
		$this->data = $this->Post->findById($id);
		$this->data['Post']['password'] = null;
		
		$categories = $this->_categoryList();
		$this->set(compact('categories'));
	}
	
	function delete($id) {
		if (!empty($this->data)) {
			if ($this->Post->validatePassword($id, 
				$this->data['Post']['password'])) {
				$this->Post->delete($id);
				$this->redirect('/');
			}
		}
		
		$this->set(compact('id'));
	}
	
	function _categoryList() {
		$condition = array(
			'fields' => array('Category.id', 'Category.name')
		);
		return $this->Post->Category->find('list', $condition);
	}
}