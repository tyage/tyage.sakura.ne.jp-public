<?php
class Opinion extends AppModel{
	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id'
		),
		'Topic' => array(
			'className' => 'Topic',
			'foreignKey' => 'topic_id'
		)
	);

	var $validate = array(
		'body' => array(
			'rule' => 'notEmpty',
			'message' => '入力してください！',
			'on' => 'create',
		),
		'key' => array(
			'rule' => 'keyCheck',
			'message' => '投稿キーが違います',
			'on' => 'create'
		)
	);

	function keyCheck($data) {
		return $data['key'] === $this->data['User']['post_key'];
	}
}