<p>買出しをする店を選んでください。</p>
<? foreach($houses as $house): ?>
	<div>
		<?= $html->link($house['Shop']['title'], '/buyers/buy/'.$buyer['Buyer']['id'].DS.$house['Shop']['id']); ?>
		（<?= $house['Town']['name']; ?>の<?= $house['House']['y']; ?>-<?= $house['House']['x']; ?>にある店）
	</div>
<? endforeach; ?>