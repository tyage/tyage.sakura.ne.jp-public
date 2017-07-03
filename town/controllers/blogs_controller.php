<?php
class BlogsController extends AppController {
	var $uses = array('Blog', 'House');

	function add($houseId) {
		$this->House->id = $houseId;
		$house = $this->House->read();
		if ($house['User']['id'] !== $this->Self->id) {
			return false;
		}

		if (!empty($this->data)) {
			if ($this->Blog->save($this->data)) {
				$this->House->save(
					array(
						'blog_id' => $this->Blog->id
					)
				);

				$this->redirect('/houses/edit/'.$houseId);
			}
		}

		$this->set('houseId', $houseId);
	}
}