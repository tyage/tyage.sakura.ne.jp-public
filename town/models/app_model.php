<?php
config('messages');
class AppModel extends Model {
	function beforeValidate() {
		foreach ($this->validate as $fieldName => $ruleSet) {
			if (!is_array($ruleSet) || (is_array($ruleSet) && isset($ruleSet['rule']))) {
				$ruleSet = array($ruleSet);
			}

			$newRuleSet = array();
			foreach ($ruleSet as $index => $validator) {
				if (!is_array($validator)) {
					$validator = array('rule' => $validator);
				}

				if (!isset($validator['message']) and !is_array($validator['rule']) and array_key_exists($validator['rule'],Messages::$error)) {
					$validator['message'] = Messages::$error[$validator['rule']];
				}

				$newRuleSet[$index] = $validator;
			}
			$this->validate[$fieldName] = $newRuleSet;
		}
	}

	function deleteByTime($hour = 0,$minute = 0,$second = 0){
		$this->del(
			array(
				'conditions' => array(
					'time <= ' => "SUBTIME(NOW(),'".$hour.":".$minute.":".$second."')"
				)
			)
		);
	}
	function deleteByLine($line,$params = array()){
		$params['limit'] = $line;
		$params['page'] = 2;

		$last = $this->find('all',$params);
		$this->del(
			array(
				'conditions' => array(
					'time <= ' => $last['time']
				)
			)
		);
	}
}
?>