<?php
class AdsController extends AppController {
	var $rate = 3;
	var $click = 4;
	var $key = '';

	function index() {
		$this->set('rate', $this->rate);
		$this->set('click', $this->click);
	}

	function click(){
		$condition = array(
			'limit' => $this->click - 1,
			'page' => 2,
			'order' => 'Ad.created DESC'
		);
		$ad = $this->Ad->find('first', $condition);
		if ($ad['Ad']['created'] > date('Y-m-d H:i:s', time() - 60*60*24)) {
			$this->flash('一日'.$this->click.'回しかクリックできません。', '/ads/');
			return false;
		}

		$data = array(
			'User' => array('id' => $this->Self->id)
		);
		$this->Ad->saveAll($data);

		$user = $this->Self->load('Profile.coin');
		$user['Profile']['coin'] += $this->rate;
		$this->Self->saveProfile($user);

		$this->flash('コインを'.$this->rate.'枚ゲットしました。', '/ads/');
	}

	function view() {
		$url = 'http://axad.shinobi.jp/f/'.$this->key.'/?_-_'.$this->key.'_-_http://tyage.sakura.ne.jp/';
		$this->set('ad', file_get_contents($url));
	}
}