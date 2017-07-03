<ul>
	<? foreach ($rooms as $key => $room): ?>
		<? if ($house[$key]['id'] != 0): ?>
			<li>
				<?= $html->link($room['name'], $room['view'].$house[$key]['id']); ?>
			</li>
		<? endif; ?>
	<? endforeach; ?>
</ul>