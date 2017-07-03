<? foreach ($houses as $house): ?>
	<section>
		<h1><?= $house['House']['title']; ?></h1>
		<p>場所：<?= $house['Town']['name']; ?><?= $house['House']['y'] ?>-<?= $house['House']['x'] ?></p>
		<p><?= $html->link('編集' ,'/houses/edit/'.$house['House']['id']) ?></p>
		<p>
			<?= $html->link('売却' ,'/houses/delete/'.$house['House']['id']) ?>
			（<?= $prices[$house['House']['id']]; ?>円）
		</p>
	</section>
	<hr />
<? endforeach; ?>