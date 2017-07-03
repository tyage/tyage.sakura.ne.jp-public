<?php
class ContactsController extends AppController {
	var $password = 'tokyo700';
	
	function lists() {
		if (!empty($this->data) and $this->data['Contact']['password'] === $this->password) {
			$this->paginate = array(
				'order' => 'Contact.created'
			);
			$contacts = $this->paginate();
			
			$this->set(compact('contacts'));
		}
	}
	
	function add() {
		if (!empty($this->data)) {
			if ($this->Contact->save($this->data)) {
				$this->flash('ご報告ありがとうございました。', '/');
			}
		}
	}
}