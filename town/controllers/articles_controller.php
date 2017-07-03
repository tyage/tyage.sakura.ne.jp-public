<?php
class ArticlesController extends AppController{
	var $uses = array('Article','Blog','Comment');

	function index($blogId) {
		$blog = $this->Blog->findById($blogId);
		if (!empty($blog['House']['id'])) {
			$this->links[] = array(
				'title' => '家',
				'url' => '/houses/view/'.$blog['House']['id']
			);
		}

		$this->paginate = array(
			'order' => 'Article.created DESC',
			'limit' => '5',
			'conditions' => array(
				'Article.blog_id' => $blog['Blog']['id']
			)
		);

		$this->set('blog', $blog);
		$this->set('articles', $this->paginate());
	}

	function add($blogId) {
		$blog = $this->Blog->findById($blogId);
		$this->links[] = array(
			'title' => 'ブログ',
			'url' => '/articles/index/'.$blog['Blog']['id']
		);
		$this->set('blog', $blog);
		if ($blog['House']['user_id'] !== $this->Self->id) {
			return false;
		}

		if (!empty($this->data)) {
			$this->data['Article']['blog_id'] = $blogId;
			$fieldList = array('blog_id','title','body');
			if ($this->Article->save($this->data, true, $fieldList)) {
				$this->redirect('/articles/index/'.$blog['Blog']['id']);
			}
		}
	}

	function view($id) {
		$article = $this->Article->findById($id);
		$this->links[] = array(
			'title' => 'ブログ',
			'url' => '/articles/index/'.$article['Blog']['id']
		);
		$this->set('article', $article);

		$conditions = array(
			'order' => 'Comment.created DESC',
			'conditions' => array(
				'Article.id' => $id
			)
		);
		$comments = $this->Comment->find('all',$conditions);
		$this->set('comments', $comments);
	}
}