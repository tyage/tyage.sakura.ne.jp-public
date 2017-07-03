<?php
class ExformHelper extends AppHelper{
	var $helpers = array('Html','Form');

	function imageSelector($fieldName, $images, $imageAttr = array(), $radioAttr = array()) {
		$i = 0;
		foreach($images as $image){
			$options[$image] = $this->Html->image(
				$imageAttr['base'].$image,
				$imageAttr
			);
			if(++$i >= $radioAttr['line']){
				$options[$image] .= '<br />';
				$i = 0;
			}
		}
		$out = $this->Form->radio($fieldName,$options,$radioAttr);

		return $this->output($out);
	}
}