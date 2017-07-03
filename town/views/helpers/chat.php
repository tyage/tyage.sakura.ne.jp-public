<?php
class ChatHelper extends AppHelper {
	var $helpers = array('Html');

	function message($message) {
		$out = null;
		$out .= '<dt>';
			$out .= $this->Html->image('chara'.DS.$message['User']['image']);
			$out .= $this->Html->link(
				$message['User']['username'],
				'/users/view/'.$message['User']['id'],
				array('target' => '_blank')
			);
			$out .= '('.$message['Chat']['created'].')';
		$out .= '</dt>';
		$out .= '<dd>';
			$out .= $message['Chat']['body'];
		$out .= '</dd>';
		return $this->output($out);
	}
}