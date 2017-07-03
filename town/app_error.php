<?php
class AppError extends ErrorHandler {
	function ajax($params) {
		$this->controller->layout = 'ajax';
		$this->controller->set('message', $params['message']);
		$this->_outputMessage('ajax');
	}
}