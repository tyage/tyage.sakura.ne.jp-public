<?php
class CommentHelper extends AppHelper{
	var $helpers = array('Html');

	function last($data,$add = false,$log = false) {
		$out = null;
		$out .= $this->title($data['User']['username']);
		$out .= $this->action($data['Opinion']['id'],$add,$log);
		$out .= $this->body($data['Opinion']['body']);
		$out .= $this->time($data['Opinion']['created']);

		return $this->output($out);
	}
	function past($data) {
		$out = null;
		$out .= $this->body($data['Opinion']['body']);
		$out .= $this->time($data['Opinion']['created']);
		return $this->output($out);
	}
	function title($data) {
		return "<h4 class='title'>".
			(empty($data) ? 'この学校は削除されました。' : $data.'の意見').
			"</h4>";
	}
	function action($id,$add,$log) {
		$out = null;
		$out .= "<p class='action'>";
		if ($add) $out .= $this->Html->link('追加','#',array('class' => 'add'));
		if ($log) $out .= $this->Html->link('ログ','#',array('class' => 'log'));
		$out .= '</p>';
		return $out;
	}
	function body($data) {
		return "<pre class='body'>".h($data)."</pre>";
	}
	function time($data) {
		return "<p class='time'>".$data."</p>";
	}
}