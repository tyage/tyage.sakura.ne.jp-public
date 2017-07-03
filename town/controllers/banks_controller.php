<?php
class BanksController extends AppController{
	var $helpers = array('Paginator');

	var $interestRate = 0.01;

	function index(){
		$user = $this->Self->load(array('Profile.money','Profile.bank'));
		$this->set('money', $user['Profile']['money']);
		$this->set('bank', $user['Profile']['bank']);
	}

	//----- 入出金 -----//
	function trade(){
		$user = $this->Self->load(array('Profile.money','Profile.bank'));

		switch ($this->data['Bank']['work']) {
			case 'in':
				$amount = empty($this->data['Bank']['all']) ?
					$this->data['Bank']['amount'] :
					$user['Profile']['money'];
				$work = '振り込み';
				break;

			case 'out':
				$amount = empty($this->data['Bank']['all']) ?
					-$this->data['Bank']['amount'] :
					-$user['Profile']['bank'];
				$work = '引き出し';
				break;

			default:
				$this->redirect('/banks/');
				break;
		}

		$money = $user['Profile']['money'] - $amount;
		$bank = $user['Profile']['bank'] + $amount;
		if ($money >= 0 and $bank >= 0) {
			$this->Bank->log(
				array(
					'user' => $user,
					'work' => $work,
					'amount' => $amount
				)
			);
			$this->Self->saveProfile(
				array(
					'money' => $money,
					'bank' => $bank
				)
			);
		}

		$this->redirect('/banks/');
	}

	//----- 通帳表示 -----//
	function view(){
		$this->links[] = array(
			'title' => '銀行',
			'url' => '/banks/'
		);

		$this->paginate = array(
			'order' => 'Bank.created DESC',
			'conditions' => array(
				'User.id' => $this->Self->id
			)
		);

		$this->set('logs', $this->paginate());
	}

	function send() {
		// 振込主のデータ
		$from = $this->Self->load(array('Profile.money','Profile.bank'));

		// 振込先のデータ
		$to = $this->User->findByUsername($this->data['User']['username']);

		if (
			$this->data['Bank']['amount'] > 0 and
			$this->data['Bank']['amount'] <= $from['Profile']['bank'] and
			!empty($to['User']['id']) and
			$to['User']['id'] != $this->Self->id
		) {
			// 振込主
			$this->Bank->log(
				array(
					'user' => $from,
					'work' => $to['User']['username'].'さんへの振込',
					'amount' => -$this->data['Bank']['amount']
				)
			);
			$data = array(
				'Profile' => array(
					'bank' => $from['Profile']['bank'] - $this->data['Bank']['amount']
				)
			);
			$this->Self->saveProfile($data);

			// 振込先
			$this->Bank->log(
				array(
					'user' => $to,
					'work' => $this->Auth->user('username').'さんからの振込',
					'amount' => $this->data['Bank']['amount']
				)
			);
			$this->Profile->id = $to['Profile']['id'];
			$data = array(
				'Profile' => array(
					'bank' => $to['Profile']['bank'] + $this->data['Bank']['amount']
				)
			);
			$this->Profile->save($data);
		}

		$this->redirect('/banks/');
	}

}