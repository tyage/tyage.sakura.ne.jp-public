<div class="info">
	<p>カーソルキーで操作します。</p>
	<p>コインを一枚払い、右キーを押してUFOを移動した後に、下キーを押して商品を取ります。</p>
	<p><?= $rule['interval']; ?>秒に<?= $rule['limit']; ?>回の間隔で操作できます。</p>
	<p>商品は主にコインですが、稀に他のアイテムが取れることがあります。</p>
	<p>もちろん、何も取れないこともあります。</p>
</div>

<div id='ufoCatch'>
	<?= $html->image('ufo.gif', array('id' => 'ufo')); ?>
	<? for($i=0;$i<25;$i++): ?>
		<?= $html->image('coin.gif', array('class' => 'coin')); ?>
	<? endfor; ?>
</div>

<table>
	<caption>過去のゲーム</caption>
	<thead>
		<tr>
			<th>ユーザー名</th>
			<th>コイン</th>
			<th>アイテム</th>
			<th>時間</th>
		</tr>
	</thead>
	<tbody>
		<? foreach($logs as $log): ?>
			<tr>
				<td>
					<?= $html->image('chara'.DS.$log['User']['image']); ?>
					<?= $html->link(
						$log['User']['username'],
						'/users/view/'.$log['User']['id'],
						array('target' => '_blank')
					); ?>
				</td>
				<td><?= $log['Ufo']['coin']; ?>枚</td>
				<td><?= $log['Item']['name']; ?></td>
				<td><?= $log['Ufo']['created']; ?></td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
<?= $paginator->numbers(true); ?>

<?= $html->script('ufo', array('inline' => false)); ?>
<?= $html->script('jquery/hotkeys', array('inline' => false)); ?>
<?= $html->css('ufo', null, array('inline' => false)); ?>