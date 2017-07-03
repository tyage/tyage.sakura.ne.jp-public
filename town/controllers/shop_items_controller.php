<?php
class ShopItemsController extends AppController{
	var $uses = array('ShopItem','House','Item');

	// 仕入れ
	function _buy() {
		$maxId = $this->Item->find('count');
		$data = array();
		for ($i=0;$i<500;$i++) {
			$id = rand(1,$maxId);
			$item = $this->Item->findById($id);
			$data[] = array(
				'ShopItem' => array(
					'item_id' => $id,
					'shop_id' => 1,
					'price' => $item['Item']['price'] * 2,
					'stock' => $item['Item']['stock']
				)
			);
		}
		$this->ShopItem->saveAll($data);
	}

	function index($shopId) {
		$condition = array(
			'conditions' => array(
				'User.id' => $this->Self->id,
				'Shop.id' => $shopId
			)
		);
		$house = $this->House->find('first', $condition);
		if (empty($house)) {
			return false;
		}

		$this->set('items', $this->ShopItem->findAllByShopId($shopId));
	}

	function edit($id) {
		$item = $this->ShopItem->findById($id);

		$condition = array(
			'conditions' => array(
				'User.id' => $this->Self->id,
				'Shop.id' => $item['Shop']['id']
			)
		);
		$house = $this->House->find('first', $condition);
		if (empty($house)) {
			return false;
		}

		if (!empty($this->data)) {
			$item['ShopItem']['price'] = $this->data['ShopItem']['price'];
			$this->ShopItem->save($item);
		}
		$this->data = $item;
	}
}