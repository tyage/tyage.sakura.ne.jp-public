<?php
class Post extends AppModel {
	var $belongsTo = 'Category';
	
	var $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'message' => '必ず入力してください'
		)
	);
	
	var $masterPassword = 'lovekoyo';
	
	function validatePassword($id, $password) {
		$post = $this->findById($id);
		
		if ($password === $this->masterPassword) {
			$return = true;
		} else if (empty($post['Post']['password'])) {
			$return = false;
		} else {
			$return = $post['Post']['password'] === $password;
		}
		
		if (!$return) {
			$this->invalidate('password', 'パスワードが違います');
		}
		return $return;
	}
}