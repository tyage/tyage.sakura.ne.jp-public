<?php
class FolderHelper extends AppHelper{
	var $helpers = array('Html');

	function tree($base,$pass = '',$current = ''){
		$current = $pass.DS.$current;

		uses('folder');
		return $this->output($this->_tree($base,$pass,$current));
	}
	function _tree($base,$pass,$current){
		$out = null;

		$folder = new Folder($base.$pass);
		list($dirs,$files) = $folder->read();

		foreach($dirs as $dir){
			$out .= '<li>';
			$out .= '<p class="folder">'.$dir.'</p>';
			$out .= $this->_tree($base,$pass.DS.$dir,$current);
			$out .= '</li>';
		}

		foreach($files as $file){
			$file = basename($file,'.ctp');

			$open = $pass.DS.$file === $current ? " class='open'" : '';
			$out .= '<li'.$open.'>'.$this->Html->link($file,$pass.DS.$file).'</li>';
		}

		return '<ul>'.$out.'</ul>';
	}
}