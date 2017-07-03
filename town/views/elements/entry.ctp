<? $entries = $this->requestAction('/entries/index/'); ?>
<div id='entry'>
	<span>参加者（<?= count($entries) ?>人）</span>
	<ul>
		<? foreach ($entries as $entry): ?>
			<li>
				<?= $html->image('chara'.DS.$entry['User']['image']); ?>
				<?= $html->link(
					$entry['User']['username'],
					'/users/view/'.$entry['User']['id'],
					array('target' => '_blank')
				); ?>
			</li>
		<? endforeach; ?>
	</ul>
</div>