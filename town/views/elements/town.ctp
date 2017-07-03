<div id='town-container'>
	<? $units = $this->requestAction('/towns/units/'.$town['Town']['id']); ?>
	<table class='town blank' style='background-image:url(/town/img/town/<?= $town['Town']['image']; ?>)'>
		<tbody>
			<? for ($y=0;$y<16;$y++): ?>
				<tr>
					<? for ($x=0;$x<16;$x++): ?>
						<td>
							<? if (!empty($units[$y][$x])): ?>
								<? $unit = $units[$y][$x]; ?>
								<? empty($unit['class']) ? $unit['class'] = 'navi' : $unit['class'] .= ' navi'; ?>
								<? $unit['name'] .= ' <small>('.$y.'-'.$x.')</small>'; ?>
								<?= $html->image($unit['src'],$unit); ?>
							<? endif; ?>
						</td>
					<? endfor; ?>
				</tr>
			<? endfor; ?>
		</tbody>
	</table>
	
	<? $users = $this->requestAction('/towns/user/'.$town['Town']['id']) ?>
	<? foreach ($users as $user): ?>
		<div 
			style='top:<?= $user['Profile']['y'] ?>px;left:<?= $user['Profile']['x'] ?>px;' 
			class='chara <?= ($self['User']['id'] == $user['User']['id'] ? 'self' : '') ?>'
		>
			<?= $this->Html->image('chara/'.$user['User']['image']) ?>
		</div>
	<? endforeach ?>
</div>