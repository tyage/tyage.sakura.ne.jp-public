<?php
class SpasController extends AppController{
	function index() {
		$user = $this->Self->load();
		$this->set('profile',$user);

		$this->set('spas', $this->paginate());
	}

	//　入浴中
	function bath($id) {
		$user = $this->Self->load();
		$user['Profile']['status'] = 'spa';
		$user['Profile']['spa'] = date('Y-m-d H:i:s');
		$this->Self->saveProfile($user);

		$this->set('profile',$user);
		$this->set('spa', $this->Spa->findById($id));
	}

	// 回復
	function recover($id) {
		$spa = $this->Spa->findById($id);
		$user = $this->Self->load();

		if ($user['Profile']['status'] === 'spa') {
			$user = $this->_recover($user, $spa);
			$this->Self->saveProfile($user);
		}
		
		$this->redirect('/spas/');
	}
	
	function _recover ($user, $spa) {
		$seconds = time() - strtotime($user['Profile']['spa']);
		$recoverEnergy = $spa['Spa']['energy'] * $seconds;
		$recoverSpirit = $spa['Spa']['spirit'] * $seconds;
		if ($user['Profile']['energy'] + $recoverEnergy > $user['Profile']['maxEnergy']) {
			$recoverEnergy = $user['Profile']['maxEnergy'] - $user['Profile']['energy'];
		}
		if ($user['Profile']['spirit'] + $recoverSpirit > $user['Profile']['maxSpirit']) {
			$recoverSpirit = $user['Profile']['maxSpirit'] - $user['Profile']['spirit'];
		}
		
		$user['Profile']['energy'] += $recoverEnergy;
		$user['Profile']['spirit'] += $recoverSpirit;
		$user['Profile']['money'] -= $spa['Spa']['cost'];
		$user['Profile']['status'] = '';
		
		return $user;
	}
}
