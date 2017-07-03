<?php
class UserItemsController extends AppController{
	var $uses = array('UserItem','User');

	function index() {
		$this->set('abilities', $this->User->abilities);
		$this->set('items', $this->UserItem->findAllByUserId($this->Self->id));
	}

	function consume($id) {
		// アイテム参照
		$condition = array(
			'conditions' => array(
				'UserItem.id' => $id,
				'UserItem.user_id' => $this->Self->id
			)
		);
		$item = $this->UserItem->find('first', $condition);

		// アイテムの消費
		$this->UserItem->id = $id;
		$item['UserItem']['rest']--;
		if ($item['UserItem']['rest'] > 0) {
			$this->UserItem->save($item);
		} else {
			$this->UserItem->delete($id);
		}

		// 能力up
		$user = $this->Self->load();
		foreach ($this->User->abilities as $ability) {
			$user['Profile'][$ability] += $item['Item'][$ability];
		}
		$this->Self->saveProfile($user);

		$this->redirect('/user_items/index/');
	}

	function sell($id) {
		// アイテム参照
		$condition = array(
			'conditions' => array(
				'UserItem.id' => $id,
				'UserItem.user_id' => $this->Self->id
			)
		);
		$item = $this->UserItem->find('first', $condition);

		// アイテムの削除
		$this->UserItem->delete($id);

		// 商品の売却
		$price = $item['UserItem']['price'] * ($item['UserItem']['rest'] / $item['Item']['count']);
		$user = $this->Self->load('Profile.money');
		$user['Profile']['money'] += $price;
		$this->Self->saveProfile($user);

		$this->redirect('/user_items/index/');
	}
}