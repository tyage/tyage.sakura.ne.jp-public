<?php
class GamesController extends AppController {
	var $uses = array();

	function index() {
		$user = $this->Self->load('Profile.coin');
		$this->set('coin', $user['Profile']['coin']);
	}
}