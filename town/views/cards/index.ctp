<div class='info'>
	<p><?= $cardMin; ?>～<?= $cardMax; ?>の数字のかかれたカードを引きます。</p>
	<p>同じ人が連続でカードを引くことはできず、また前に引いてから<?= $rule['interval'] ?>分経つまで引けません。</p>
	<p>最後に引かれたカード（左端のカード）と同じ数字を引くとハズレです。</p>
	<p>ハズレの場合は貯まっているカード×<?= $rate; ?>枚のコインを支払い、貯まったカードが消えます。</p>
	<p>それ以外の場合は貯まっているカード×<?= $rate; ?>枚のコインをもらい、場にカードが貯まります。</p>
</div>

<p>現在コインを<?= $coin; ?>枚持っています。</p>

<div id='cards' class='clearfix'>
	<? foreach ($cards as $card): ?>
		<div class='card'><?= $card['Card']['number'] ?></div>
	<? endforeach; ?>
</div>

<?= $html->link('カードを引く', '/cards/draw/') ?>

<table>
	<caption>過去のゲーム</caption>
	<thead>
		<tr>
			<th>ユーザー</th>
			<th>引いたカード</th>
			<th>獲得コイン枚数</th>
			<th>時間</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($logs as $log): ?>
			<tr>
				<td>
					<?= $html->image('chara'.DS.$log['User']['image']); ?>
					<?= $html->link(
						$log['User']['username'],
						'/users/view/'.$log['User']['id'],
						array('target' => '_blank')
					); ?>
				</td>
				<td><?= $log['Card']['number']; ?></td>
				<td><?= $log['Card']['coin']; ?></td>
				<td><?= $log['Card']['created']; ?></td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>

<?= $paginator->numbers(true); ?>

<?= $html->css('card', null, array('inline' => false)); ?>