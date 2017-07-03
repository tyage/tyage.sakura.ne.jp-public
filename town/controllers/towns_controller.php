<?php
class TownsController extends AppController {
	var $uses = array('Town','House','Entry');

	function beforeFilter() {
		$this->Auth->allow('*');
		parent::beforeFilter();
	}

	function move($id) {
		$town = $this->Town->findById($id);
		if (!empty($town)) {
			$this->Self->saveProfile(array('town_id' => $id));
			$this->redirect('/');
		}
	}

	function index() {
		return $this->Town->find('all');
	}

	function view($id) {
		$this->set('town', $this->Town->findById($id));
	}

	function units($id) {
		$units = array();

		$data = $this->Town->getData($id);
		foreach ($data as $y => $row) {
			foreach ($row as $x => $key) {
				if (!empty($this->Town->units[$key])) {
					$units[$y][$x] = $this->Town->units[$key];
				}
			}
		}

		$houses = $this->House->findAllByTownId($id);
		foreach ($houses as $house) {
			$house['House']['name'] = $house['User']['username'].'の家';
			$units[$house['House']['y']][$house['House']['x']] = array(
				'src' => 'house'.DS.$house['House']['image'],
				'url' => array(
					'controller' => 'houses',
					'action' => 'view',
					$house['House']['id']
				),
				'name' => $house['User']['username'].'の家',
				'title' => $house['House']['title']
			);
		}

		return $units;
	}
	
	function user($id) {
		$condition = array(
			'conditions' => array(
				'Profile.town_id' => $id
			),
			'fields' => array('User.id'),
			'recursive' => 2
		);
		$users = $this->Entry->User->find('list', $condition);
		
		$condition = array(
			'conditions' => array(
				'User.id' => $users
			),
			'fields' => array('Entry.user_id'),
			'recursive' => 2
		);
		$users = $this->Entry->find('list', $condition);
		
		$condition = array(
			'conditions' => array(
				'User.id' => $users
			)
		);
		return $this->Entry->User->find('all', $condition);
	}
}