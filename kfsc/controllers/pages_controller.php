<?php
class PagesController extends AppController {
	var $uses = array('News','User');
	var $helpers = array('Javascript');

	function beforeFilter() {
		$this->Auth->allow('display');
		parent::beforeFilter();
	}

	function display() {
		$path = func_get_args();

		$count = count($path);
		if (!$count) {
			$this->redirect('/');
		}
		$page = $subpage = $title = null;

		if (!empty($path[0])) {
			$page = $path[0];
		}
		if (!empty($path[1])) {
			$subpage = $path[1];
		}
		if (!empty($path[$count - 1])) {
			$title = Inflector::humanize($path[$count - 1]);
		}
		$this->set(compact('page', 'subpage', 'title'));

		switch ($page) {
			case 'top':
				$this->top();
				break;
			case 'schools':
				$this->schools();
				break;
		}

		$this->render(implode('/', $path));
	}

	function top() {
		$this->set('news',$this->News->find('all',array('limit' => 5,'order' => 'created DESC')));
	}
	
	function schools() {
		$this->set('users', $this->User->find('all'));
	}

	function admin_index() {
		$this->set('base',VIEWS);
	}
	function admin_files() {
		uses('folder');
		$folder = new Folder($this->data['Pages']['base']);
		list($dirs,$files) = $folder->read();
		
		$this->set('dirs',$dirs);
		$this->set('files',$files);
		
		$this->layout = 'ajax';
	}
	function admin_source() {
		$path = $_POST['base'].DS.$_POST['name'];
		$this->set('source', file_get_contents($path));
		
		$this->layout = 'ajax';
	}
	function admin_edit($file) {
		$oldPath = $this->data['Pages']['base'].DS.$this->data['Pages']['name'];
		$newPath = $this->data['Pages']['base'].DS.$this->data['Pages']['newName'];
    if(file_exists($oldPath)) {
			if ($oldPath !== $newPath) rename($oldPath,$newPath);
		} else {
			if (!$file) mkdir($newPath);
		}
		if ($file) {
			file_put_contents($newPath,$this->data['Pages']['source']);
		}
	}
	function admin_delete($file) {
		$path = $this->data['Pages']['base'].DS.$this->data['Pages']['name'];
		if ($file) {
			unlink($path);
		} else {
			uses('Folder');
			$folder = new Folder($path);
			$folder->delete();
		}
	}
}

?>