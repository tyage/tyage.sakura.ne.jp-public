<?php
class AppController extends Controller {
	var $components = array('Security');
	
	function beforeFilter() {
		$ip = env('REMOTE_ADDR');
		if ($ip === '199.48.147.43' or $ip === '87.106.138.84' or 
			$ip === '83.170.92.9' or $ip === '46.19.138.242' or
			$ip === '192.251.226.206'or $ip === '199.48.147.4' or 
			$ip === '199.48.147.36' or $ip === '83.227.30.29' or
			$ip === '81.218.219.122' or $ip === '87.118.101.175') {
			$this->redirect('http://twitter.com/tyage');
		}
	}
	
	function beforeRender() {
		$this->set('title_for_layout', 'スカイプ友達募集掲示板「Skype × Skype」（スカイプスカイプ）');
	}
}