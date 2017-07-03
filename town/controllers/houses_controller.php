<?php
class HousesController extends AppController{
	var $uses = array('House','Town','Forum','Shop','Blog','Store');
	var $helpers = array('Exform');

	function index() {
		$houses = $this->House->findAllByUserId($this->Self->id);
		$this->set('houses', $houses);

		$prices = array();
		foreach ($houses as $house) {
			$prices[$house['House']['id']] = $this->_calcPrice($house);
		}
		$this->set('prices', $prices);
	}

	function add() {
		$towns = array();
		foreach ($this->Town->find('all') as $town) {
			$towns[$town['Town']['id']] = $town['Town']['name'].' ('.$town['Town']['price'].'円)';
		}
		$this->set('towns', $towns);

		$self = $this->Self->load(array('Profile.money','Profile.town_id'));
		$this->set('town', $this->Town->findById($self['Profile']['town_id']));

		$this->set('images', $this->House->getImages());

		if (!empty($this->data)) {
			$this->data['House']['user_id'] = $this->Self->id;

			// 空き地検査
			$this->House->set($this->data);
			if ($this->House->validates()) {
				$conditions = array(
					'conditions' => array(
						'House.x' => $this->data['House']['x'],
						'House.y' => $this->data['House']['y'],
						'Town.id' => $this->data['House']['town_id']
					)
				);
				$house = $this->House->find('first', $conditions);
				$town = $this->Town->getData($this->data['House']['town_id']);
				if (
					empty($town[$this->data['House']['y']][$this->data['House']['x']]) or
					$town[$this->data['House']['y']][$this->data['House']['x']] !== '地' or
					!empty($house)
				) {
					$this->House->invalidate('x','そこには建てられません。');
					$this->House->invalidate('y','そこには建てられません。');
				}
			}

			// 支払い
			$price = $this->_calcPrice($this->data);
			$self['Profile']['money'] -= $price;
			if ($self['Profile']['money'] < 0) {
				$this->House->invalidate('money','お金が足りないです。');
			}else {
				$this->Self->saveProfile($self);
			}

			$fieldList = array('user_id','town_id','image','x','y');
			$this->House->save(null, true, $fieldList);
		}
	}

	function view($id){
		$house = $this->House->findById($id);
		$this->set('house', $house);
		$this->set('rooms', $this->House->rooms);
	}

	function edit($id = null) {
		$this->House->id = $id;

		$house = $this->House->read();
		if ($house['House']['user_id'] !== $this->Self->id) {
			return false;
		}

		if (empty($this->data)) {
			$this->data = $house;
		} else {
			$this->House->save($this->data, true, array('image','title'));
		}

		$rooms = array();
		foreach ($this->House->rooms as $key => $room) {
			if ($house[$key]['id'] == 0) {
				$rooms[$key] = $room;
			}
		}

		$this->set('houseId', $this->data['House']['id']);
		$this->set('rooms', $rooms);
		$this->set('images', $this->House->getImages());
	}

	function delete($id) {
		$house = $this->House->findById($id);
		if ($house['User']['id'] !== $this->Self->id) {
			return false;
		}

		$self = $this->Self->load('Profile.money');
		$price = $this->_calcPrice($house);
		$self['Profile']['money'] += $price;
		$this->Self->saveProfile($self);

		$this->House->delete($id, false);
		$this->Forum->delete($house['Forum']['id']);
		$this->Shop->delete($house['Shop']['id']);
		$this->Blog->delete($house['Blog']['id']);
		$this->Store->delete($house['Store']['id']);

		$this->redirect('/houses/');
	}

	function _calcPrice($data) {
		$town = $this->Town->findById($data['House']['town_id']);
		return $town['Town']['price'];
	}
}