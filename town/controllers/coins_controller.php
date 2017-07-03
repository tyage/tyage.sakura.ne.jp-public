<?php
class CoinsController extends AppController {
	var $uses = array();

	var $buyRate = 100;
	var $sellRate = 80;

	function index() {
		$this->links[] = array(
			'title' => 'ゲーセン',
			'url' => '/games/'
		);

		$user = $this->Self->load(array('Profile.coin','Profile.money'));
		$this->set('coin', $user['Profile']['coin']);
		$this->set('money', $user['Profile']['money']);

		$this->set('buyRate', $this->buyRate);
		$this->set('sellRate', $this->sellRate);
	}

	function buy(){
		$user = $this->Self->load(array('Profile.coin','Profile.money'));

		if (!empty($this->data['Coin']['all'])) {
			$this->data['Coin']['amount'] = intval($user['Profile']['money'] / $this->buyRate);
		}

		$user['Profile']['money'] -= $this->data['Coin']['amount'] * $this->buyRate;
		$user['Profile']['coin'] += $this->data['Coin']['amount'];
		if ($user['Profile']['money'] >= 0 and $this->data['Coin']['amount'] > 0) {
			$this->Self->saveProfile($user);
		}

		$this->redirect('/coins/');
	}

	function sell(){
		$user = $this->Self->load(array('Profile.coin','Profile.money'));

		if (!empty($this->data['Coin']['all'])) {
			$this->data['Coin']['amount'] = $user['Profile']['coin'];
		}

		$user['Profile']['money'] += $this->data['Coin']['amount'] * $this->sellRate;
		$user['Profile']['coin'] -= $this->data['Coin']['amount'];
		if ($user['Profile']['coin'] >= 0 and $this->data['Coin']['amount'] > 0) {
			$this->Self->saveProfile($user);
		}

		$this->redirect('/coins/');
	}

}