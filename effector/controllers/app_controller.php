<?php
class AppController extends Controller {
	function beforeRender() {
		$this->set('title_for_layout', 'エフェクターレビュー');
	}
}