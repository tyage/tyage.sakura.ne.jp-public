<?php
class Group extends AppModel {
	var $belognsTo = 'Group';
	//var $hasManyAndBelongsTo = 'User';
	//var $hasMany = 'Message';
	
	function userGroup($user_id) {
		$condition = array(
			'conditions' => array(
				'GroupsUser.user_id' => $user_id
			),
			'fields' => array('id')
		);
		$groups = $this->Message->Group->find('list', $condition);
		return $groups;
	}
}