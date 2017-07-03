<?php
class Http {
	function post($url, $data, $option = array()) {
		$option += array(
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => $data
		);
		
		$curl = curl_init($url);
		curl_setopt_array($curl, $option);
		$res = curl_exec($curl);
		curl_close($curl);
		return $this->_parseXML($res);
	}
	
	function get($url, $data = null, $option = array()) {
		if (!is_null($data)) {
			$url = $this->_createUrl($url, $data);
		}
		$option += array(
			CURLOPT_RETURNTRANSFER => true
		);
		
		$curl = curl_init($url);
		curl_setopt_array($curl, $option);
		$res = curl_exec($curl);
		curl_close($curl);
		return $this->_parseXML($res);
	}
	
	function _createUrl($url, $data) {
		$url .= '?';
		$params = array();
		foreach ($data as $key => $value) {
			$params[] = $key.'='.$value;
		}
		$url .= implode('&', $params);
		return $url;
	}
	
	function _parseXML($xml) {
		return new SimpleXMLElement($xml);
	}
}

class Flickr extends Http {
	var $key = '';
	var $secret = '';
	var $baseUrl = 'http://api.flickr.com/services/';
	
	function __construct() {
		$this->restUrl = $this->baseUrl.'rest/';
		$this->uploadUrl = $this->baseUrl.'upload/';
		$this->authUrl = $this->baseUrl.'auth/';
	}
	
	function _getApiSig($data) {
		ksort($data);
		$auth_sig = '';
		foreach ($data as $key => $value) {
			$auth_sig .= $key.$value;
		}
		return md5($this->secret.$auth_sig);
	}
	
	function rest($method, $data) {
		$data['method'] = 'flickr.'.$method;
		$data['api_key'] = $this->key;
		if (isset($this->token)) {
			$data['auth_token'] = $this->token;
		}
		$data['api_sig'] = $this->_getApiSig($data);
		
		return parent::post($this->restUrl, $data);
	}
	
	function upload($photo, $data = array()) {
		$data['api_key'] = $this->key;
		if (isset($this->token)) {
			$data['auth_token'] = $this->token;
		}
		$data['api_sig'] = $this->_getApiSig($data);
		// 'photo' parameter should not be included in the signature
		$data['photo'] = '@'.realpath($photo);
		
		$res = parent::post($this->uploadUrl, $data);
		return $data['async'] ? $res->ticketid : $res->photoid;
	}
	
	function auth() {
		$params = array(
			'api_key' => $this->key,
			'perms' => 'write'
		);
		$params['api_sig'] = $this->_getApiSig($params);
		$auth_url = parent::_createUrl($this->authUrl, $params);
		header('Location: '.$auth_url);
		exit();
	}
	
	function getToken($frob) {
		$res = $this->rest('auth.getToken', array(
			'frob' => $frob
		));
		$this->token = $res->auth->token;
	}
}
