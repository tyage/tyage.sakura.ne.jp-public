<?php
class StoresController extends AppController{
	var $uses = array('Store', 'House', 'StoreItem', 'UserItem');

	function add($houseId) {
		$this->House->id = $houseId;
		$house = $this->House->read();
		if ($house['User']['id'] !== $this->Self->id) {
			return false;
		}

		if (!empty($this->data)) {
			if ($this->Store->save($this->data)) {
				$this->House->save(
					array(
						'store_id' => $this->Store->id
					)
				);

				$this->redirect('/houses/edit/'.$houseId);
			}
		}

		$this->set('houseId', $houseId);
	}

	function out($id) {
		$store = $this->Store->findById($id);
		if (!$this->_isAllowed($store)) {
			return false;
		}

		$this->_link($store);
		$this->links[] = array(
			'title' => '預ける',
			'url' => '/stores/in/'.$id
		);
		
		if (!empty($this->data)) {
			$this->_out($id);
		}

		$this->set('items', $this->StoreItem->findAllByStoreId($id));

		$this->set('store', $store);
	}

	function _out($id) {
		$conditon = array(
			'conditions' => array(
				'StoreItem.id' => $this->data['StoreItem']['id'],
				'Store.id' => $id
			)
		);
		$items = $this->StoreItem->find('all', $conditon);

		$this->StoreItem->deleteAll($conditon['conditions']);

		foreach ($items as $item) {
			$userItem = array();
			$userItem['User']['id'] = $this->Self->id;
			$userItem['UserItem'] = $item['StoreItem'];
			$this->UserItem->saveAll($userItem);
		}
	}

	function in($id) {
		$store = $this->Store->findById($id);
		if (!$this->_isAllowed($store)) {
			return false;
		}

		$this->_link($store);
		$this->links[] = array(
			'title' => '引き出す',
			'url' => '/stores/out/'.$id
		);
		
		if (!empty($this->data)) {
			$this->_in($id);
		}

		$this->set('items', $this->UserItem->findAllByUserId($this->Self->id));

		$this->set('store', $store);
	}

	function _in($id) {
		$conditon = array(
			'conditions' => array(
				'UserItem.id' => $this->data['UserItem']['id'],
				'User.id' => $this->Self->id
			)
		);
		$items = $this->UserItem->find('all', $conditon);

		$this->UserItem->deleteAll($conditon['conditions']);

		foreach ($items as $item) {
			$storeItem = array();
			$storeItem['Store']['id'] = $id;
			$storeItem['StoreItem'] = $item['UserItem'];
			$this->StoreItem->saveAll($storeItem);
		}
	}

	function _isAllowed($store) {
		return !empty($store['House']['id']);
	}
	
	function _link($store) {
		$this->links[] = array(
			'title' => '家',
			'url' => '/houses/view/'.$store['House']['id']
		);
	}
}