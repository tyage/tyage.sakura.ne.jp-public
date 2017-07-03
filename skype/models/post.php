<?php
class Post extends AppModel {
	var $hasMany = array(
		'Comment' => array(
			'className' => 'Post',
			'foreignKey' => 'parent_id',
			'order' => 'Comment.created',
			'dependent' => true
		)
	);
	
	var $validate = array(
		'ip' => array(
			'timeLimit' => array(
				'rule' => array('timeLimit'),
				'message' => array('前回の投稿から60秒以内に投稿することはできません'),
				'on' => 'create'
			),
			'doubleLimit' => array(
				'rule' => array('doubleLimit'),
				'message' => array('連続投稿は禁止されています'),
				'on' => 'create'
			)
		)
	);
	
	var $masterIp = '180.147.82.90';
	
	var $limit = 100;
	function afterSave($created) {
		if ($created) {
			$count = $this->find('count');
			$delete = $count - $this->limit;
			if ($delete > 0) {
				$condition = array(
					'order' => 'Post.created',
					'limit' => $delete,
					'fields' => array('Post.id')
				);
				$lists = $this->find('list', $condition);
				
				foreach ($lists as $id) {
					$this->delete($id);
				}
			}
		}
	}
	
	function timeLimit($check) {
		$ip = current($check);
		if ($ip === $this->masterIp) {
			return true;
		}
		
		$condition = array(
			'conditions' => array(
				'Post.ip' => $ip
			),
			'order' => 'Post.created DESC'
		);
		$post = $this->find('first', $condition);
		
		return time() - strtotime($post['Post']['created']) > 60;
	}
	
	function doubleLimit($check) {
		$ip = current($check);
		if ($ip === $this->masterIp) {
			return true;
		}
		
		$condition = array(
			'order' => 'Post.created DESC'
		);
		$post = $this->find('first', $condition);
		
		return $post['Post']['ip'] !== $ip;
	}
}