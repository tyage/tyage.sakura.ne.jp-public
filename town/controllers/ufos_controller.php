<?php
class UfosController extends AppController{
	var $uses = array('Ufo','Item','UserItem');

	var $rule = array(
		'interval' => 60,
		'limit' => 1
	);
	var $cost = 1;
	var $coinMax = 10;
	var $coinRate = 35;
	var $itemRate = 10;

	function index(){
		$this->links[] = array(
			'title' => 'ゲーセン',
			'url' => '/games/'
		);

		$this->paginate = array(
			'order' => 'Ufo.created DESC'
		);
		$this->set('rule', $this->rule);
		$this->set('logs', $this->paginate());
	}

	function get(){
		$user = $this->Self->load('Profile.coin');
		if(!$this->_canCatch($user['Profile']['coin'])) {
			$this->cakeError('ajax', array('message' => 'まだ取れません。'));
		}

		srand();

		$coin = $this->_getCoin();
		$user['Profile']['coin'] += $coin - $this->cost;
		$this->Self->saveProfile($user);

		$item = $this->_getItem();
		if (!empty($item['Item']['id'])) {
			$data = array(
				'User' => array('id' => $this->Self->id),
				'Item' => array('id' => $item['Item']['id']),
				'UserItem' => array(
					'rest' => $item['Item']['count'],
					'price' => $item['Item']['price']
				)
			);
			$this->UserItem->saveAll($data);
		}

		$data = array(
			'User' => array('id' => $this->Self->id),
			'Ufo' => array('coin' => $coin),
			'Item' => array('id' => $item['Item']['id'])
		);
		$this->Ufo->saveAll($data);

		$this->set('coin', $coin);
		$this->set('item', $item);
	}

	function _canCatch($coin) {
		if ($coin < $this->cost) {
			return false;
		}

		$condition = array(
			'order' => 'Ufo.created DESC',
			'conditions' => array(
				'User.id' => $this->Self->id
			),
			'limit' => $this->rule['limit']
		);
		$logs = $this->Ufo->find('all', $condition);

		if (empty($logs[$this->rule['limit']-1])) {
			return true;
		}

		$log = $logs[$this->rule['limit']-1];
		return empty($log['Ufo']['created']) or $log['Ufo']['created'] < date('Y-m-d H:i:s', time() - $this->rule['interval']);
	}

	function _getCoin(){
		return rand(1,100) <= $this->coinRate ? rand(1, $this->coinMax) : 0;
	}
	function _getItem(){
		if(rand(1,100) <= $this->itemRate){
			$id = $this->Item->find('count');
			return $this->Item->findById(rand(1,$id));
		}
		return null;
	}
}