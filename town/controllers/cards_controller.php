<?php
class Cardscontroller extends AppController{
	var $uses = array('Card');

	var $rule = array(
		'interval' => 30,
		'limit' => 1
	);
	var $rate = 1;
	var $cardMin = 1;
	var $cardMax = 5;

	function index() {
		$this->links[] = array(
			'title' => 'ゲーセン',
			'url' => '/games/'
		);

		$this->set('rule', $this->rule);
		$this->set('cardMin', $this->cardMin);
		$this->set('cardMax', $this->cardMax);
		$this->set('rate', $this->rate);

		$user = $this->Self->load('Profile.coin');
		$this->set('coin', $user['Profile']['coin']);

		// 貯まっているカードを調べる
		$condition = array(
			'conditions' => array(
				'success' => 0
			),
			'order' => 'Card.created DESC'
		);
		$card = $this->Card->find('first', $condition);

		$condition = array(
			'conditions' => array(
				'Card.id > ' => $card['Card']['id']
			),
			'order' => 'Card.created DESC'
		);
		$cards = $this->Card->find('all', $condition);
		$this->set('cards', $cards);

		// 過去ログ一覧
		$this->paginate = array(
			'order' => 'Card.created DESC'
		);
		$this->set('logs', $this->paginate());
	}


	function draw(){
		if (!$this->_canDraw()) {
			return false;
		}

		$user = $this->Self->load('Profile.coin');

		// 貯まっているカードの数を調べる
		$condition = array(
			'conditions' => array(
				'success' => 0
			),
			'order' => 'Card.created DESC'
		);
		$card = $this->Card->find('first', $condition);
		$condition = array(
			'conditions' => array(
				'Card.id > ' => $card['Card']['id']
			),
			'order' => 'Card.created DESC'
		);
		$count = $this->Card->find('count', $condition);

		if ($count * $this->rate > $user['Profile']['coin']) {
			$this->flash('コインが足りません。', '/cards/');
			return false;
		}

		// 当たり外れを調べる
		$number = rand($this->cardMin,$this->cardMax);
		$condition = array(
			'order' => 'Card.created DESC'
		);
		$last = $this->Card->find('first', $condition);
		$success = ($last['Card']['number'] != $number);

		// ログ更新
		$coin = $count * $this->rate * ($success ? 1 : -1);
		$data = array(
			'User' => array('id' => $this->Self->id),
			'Card' => array(
				'number' => $number,
				'coin' => $coin,
				'success' => $success
			)
		);
		$this->Card->saveAll($data);

		// ユーザーデータ更新
		$user['Profile']['coin'] += $coin;
		$this->Self->saveProfile($user);

		$this->redirect('/cards/');
	}

	function _canDraw() {
		// 連続チェック
		$condition = array(
			'order' => 'Card.created DESC'
		);
		$last = $this->Card->find('first', $condition);
		if ($this->Self->id == $last['User']['id']) {
			$this->flash('連続でカードを引けません。', '/cards/');
			return false;
		}

		// 制限時間を調べる
		$condition = array(
			'order' => 'Card.created DESC',
			'conditions' => array(
				'User.id' => $this->Self->id
			),
			'limit' => $this->rule['limit']
		);
		$logs = $this->Card->find('all', $condition);
		$log = $logs[$this->rule['limit']-1];
		$limit = date('Y-m-d H:i:s', time() - $this->rule['interval']*60);
		if (!empty($log['Card']['created']) and
			$log['Card']['created'] > $limit) {
			$this->flash('まだカードを引けません。', '/cards/');
			return false;
		}

		return true;
	}
}