<?php
class ExtimeHelper extends AppHelper{
	function toJa($time){
		$out .= empty($time['year']) ? '' : $time['year'].'年';
		$out .= empty($time['month']) ? '' : $time['month'].'月';
		$out .= empty($time['day']) ? '' : $time['day'].'日';
		$out .= empty($time['hour']) ? '' : $time['hour'].'時';
		$out .= empty($time['second']) ? '' : $time['second'].'分';
		$out .= empty($time['minute']) ? '' : $time['minute'].'秒';
		return $this->output($out);
	}
}