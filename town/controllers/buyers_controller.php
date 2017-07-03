<?php
class BuyersController extends AppController{
	var $uses = array('Buyer','BuyerItem','House','ShopItem');

	function index() {
		$user = $this->Self->load('Profile.town_id');
		$buyer = $this->Buyer->findByTownId($user['Profile']['town_id']);
		$this->set('buyer', $buyer);
		
		$this->set('houses', $this->House->findAllByUserId($this->Self->id));
	}

	function buy($buyerId, $shopId) {
		$this->links[] = array(
			'title' => '店',
			'url' => '/shops/buy/'.$shopId
		);
		
		if ($this->data) {
			$this->_buy($buyerId, $shopId);
		}

		$this->set('shopId', $shopId);
		$this->set('buyerId', $buyerId);
		$this->set('items', $this->BuyerItem->findAllByBuyerId($buyerId));
	}

	function _buy($buyerId, $shopId) {
		// 店取得
		$condition = array(
			'conditions' => array(
				'Shop.id' => $shopId,
				'User.id' => $this->Self->id
			)
		);
		$house = $this->House->find('first', $condition);
		if (empty($house)) {
			return false;
		}

		// 購入商品データ取得
		$quantities = array();
		foreach ($this->data['BuyerItem']['quantity'] as $id => $quantity) {
			if ($quantity > 0) {
				$quantities[$id] = $quantity;
			}
		}

		// 購入商品のデータを取得
		$conditions = array(
			'conditions' => array(
				'BuyerItem.id' => array_keys($quantities),
				'BuyerItem.buyer_id' => $buyerId
			)
		);
		$buyItems = $this->BuyerItem->find('all', $conditions);
		if (empty($buyItems)) {
			return false;
		}

		// 購入個数修正
		foreach ($buyItems as $key => $item) {
			$item['quantity'] = $quantities[$item['BuyerItem']['id']];

			if ($item['BuyerItem']['stock'] < $item['quantity']) {
				$item['quantity'] = $item['BuyerItem']['stock'];
			}

			$buyItems[$key] = $item;
		}

		// コスト計算
		$cost = 0;
		foreach ($buyItems as $item) {
			$cost += $item['BuyerItem']['price'] * $item['quantity'];
		}

		// 代金支払い
		$user = $this->Self->load('Profile.money');
		$user['Profile']['money'] -= $cost;
		if ($user['Profile']['money'] < 0) {
			$this->Buyer->invalidate('money', 'お金が足りません。');
			return false;
		}
		$this->Self->saveProfile($user);

		// 問屋アイテムの在庫減らし
		foreach ($buyItems as $item) {
			$item['BuyerItem']['stock'] -= $item['quantity'];
			if ($item['BuyerItem']['stock'] > 0) {
				$this->BuyerItem->id = $item['BuyerItem']['id'];
				$this->BuyerItem->save($item);
			} else {
				$this->BuyerItem->delete($item['BuyerItem']['id']);
			}
		}
		$this->set('purchases', $buyItems);

		// 店のアイテム一覧取得
		$shopItems = array();
		$conditions = array(
			'conditions' => array(
				'ShopItem.shop_id' => $house['Shop']['id']
			)
		);
		foreach ($this->ShopItem->find('all', $conditions) as $item) {
			$shopItems[$item['Item']['id']] = $item;
		}

		// 店のアイテム追加
		foreach ($buyItems as $key => $item) {
			if (empty($shopItems[$item['Item']['id']])) {
				$item['ShopItem'] = $item['BuyerItem'];
				$item['ShopItem']['id'] = null;
				$item['ShopItem']['shop_id'] = $house['Shop']['id'];
				$item['ShopItem']['stock'] = $item['quantity'];
				$buyItems[$key] = $item;
			} else {
				unset($buyItems[$key]);
				$shopItem = $shopItems[$item['Item']['id']];
				$shopItem['ShopItem']['stock'] += $item['quantity'];
				$this->ShopItem->save($shopItem);
			}
		}
		if(!empty($buyItems)) {
			$this->ShopItem->saveAll($buyItems);
		}
	}
}
