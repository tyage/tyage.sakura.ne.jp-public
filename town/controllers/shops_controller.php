<?php
class ShopsController extends AppController{
	var $uses = array('Shop','ShopItem','House','UserItem');

	function add($houseId) {
		$this->House->id = $houseId;
		$house = $this->House->read();
		if ($house['User']['id'] !== $this->Self->id) {
			return false;
		}

		if (!empty($this->data)) {
			if ($this->Shop->save($this->data)) {
				$this->House->save(
					array(
						'shop_id' => $this->Shop->id
					)
				);

				$this->redirect('/houses/edit/'.$houseId);
			}
		}

		$this->set('houseId', $houseId);
	}

	function buy($id = 1) {
		$shop = $this->Shop->findById($id);
		if (!empty($shop['House']['id'])) {
			$this->links[] = array(
				'title' => '家',
				'url' => '/houses/view/'.$shop['House']['id']
			);
		}

		if (!empty($this->data)) {
			$this->_buy($id);
		}

		$this->paginate = array(
			'Shop.id' => $id
		);

		$this->set('shop', $shop);
		$this->set('items', $this->paginate('ShopItem'));
	}

	function _buy($shopId) {
		// 購入商品データ取得
		$quantities = array();
		foreach ($this->data['ShopItem']['quantity'] as $id => $quantity) {
			if ($quantity > 0) {
				$quantities[$id] = $quantity;
			}
		}

		// 購入商品のデータを取得
		$conditions = array(
			'conditions' => array(
				'ShopItem.id' => array_keys($quantities),
				'ShopItem.shop_id' => $shopId
			)
		);
		$buyItems = $this->ShopItem->find('all', $conditions);
		if (empty($buyItems)) {
			return false;
		}

		// 購入個数修正
		foreach ($buyItems as $key => $item) {
			$item['quantity'] = $quantities[$item['ShopItem']['id']];

			if ($item['ShopItem']['stock'] < $item['quantity']) {
				$item['quantity'] = $item['ShopItem']['stock'];
			}

			$buyItems[$key] = $item;
		}

		// コスト計算
		$cost = 0;
		foreach ($buyItems as $item) {
			$cost += $item['ShopItem']['price'] * $item['quantity'];
		}

		// 代金支払い
		$user = $this->Self->load('Profile.money');
		$user['Profile']['money'] -= $cost;
		if ($user['Profile']['money'] < 0) {
			$this->Shop->invalidate('money', 'お金が足りません。');
			return false;
		}
		$this->Self->saveProfile($user);

		// 店アイテムの在庫減らし
		foreach ($buyItems as $item) {
			$item['ShopItem']['stock'] -= $item['quantity'];
			if ($item['ShopItem']['stock'] > 0) {
				$this->ShopItem->id = $item['ShopItem']['id'];
				$this->ShopItem->save($item);
			} else {
				$this->ShopItem->delete($item['ShopItem']['id']);
			}
		}
		$this->set('purchases', $buyItems);

		// ユーザーのアイテム追加
		$userItems = array();
		foreach ($buyItems as $item) {
			$item['UserItem'] = $item['ShopItem'];
			$item['UserItem']['id'] = null;
			$item['UserItem']['user_id'] = $this->Self->id;
			$item['UserItem']['rest'] = $item['Item']['count'];

			for ($i=0;$i<$item['quantity'];$i++) {
				$userItems[] = $item;
			}
		}
		$this->UserItem->saveAll($userItems);
	}

}
