<?php
class AppController extends Controller {
	var $components = array('RequestHandler');
	var $helpers = array('Javascript');
	
	// http://c-brains.jp/blog/wsg/11/01/21-232435.php
	function _renderJson($contents=array(), $params=array()) {
		$params = Set::merge(array(
			'header' => true,
			'debugOff' => true,
		), $params);
		if ($params['debugOff']) {
			Configure::write('debug', 0);
		}
		if ($params['header']) {
			$this->RequestHandler->setContent('json');
			$this->RequestHandler->respondAs('application/json; charset=UTF-8');
		}

		$this->layout = false;
		$this->set(compact('contents'));
		$this->render('/json');
	}
}