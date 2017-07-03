<?php
class MailHelper extends AppHelper {
	var $helpers = array('Html');

	function mail ($mail, $user) {
		$out = null;
		$out .= '<tr class="mailHeader '.($mail['Mail']['unread'] ? 'unread' : '').'" mail="'.($mail['Mail']['id']).'">';
			$out .= '<th>';
				$out .= $this->Html->image('chara'.DS.$user['image']);
				$out .= $this->Html->link(
					$user['username'],
					'/users/view/'.$user['id']
				);
			$out .= '</th>';
			$out .= '<th>'.$mail['Mail']['title'].'</th>';
			$out .= '<th>'.$mail['Mail']['created'].'</th>';
		$out .= '</tr>';
		$out .= '<tr class="mailBody">';
			$out .= "<td colspan='3'><pre>".$mail['Mail']['body']."</pre></td>";
		$out .= '</tr>';
		return $out;
	}

	function receive($receive) {
		$out = $this->mail($receive, $receive['From']);
		return $this->output($out);
	}
	function send($send) {
		$out = $this->mail($send, $send['To']);
		return $this->output($out);
	}
}