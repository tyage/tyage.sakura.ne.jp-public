<?
$towns = $this->requestAction('/towns/index/'.$town['Town']['id']);
foreach($towns as $id => $town){
	$map[$town['Town']['y']][$town['Town']['x']] = $town;
	$vectorX[] = $town['Town']['x'];
	$vectorY[] = $town['Town']['y'];
}
$maxX = max($vectorX);
$maxY = max($vectorY);
?>
<table id='townMap' class='blank'>
	<tbody>
		<? for ($y=$maxY;$y>=0;$y--): ?>
			<tr>
				<? for ($x=0;$x<=$maxX;$x++): ?>
					<td>
						<? if (!empty($map[$y][$x])): ?>
							<? $town = $map[$y][$x]; ?>
							<? $present = ($location == $town['Town']['id']); ?>
							<?= $html->image(
								'town'.DS.'thumbs'.DS.$town['Town']['image'],
								array(
									'title' => $town['Town']['name'],
									'url' => '/towns/move/'.$town['Town']['id'],
									'class' => 'light'.($present ? ' present' : ''),
									'town-id' => $town['Town']['id']
								)
							); ?>
							<?= $html->image(
								'town'.DS.'thumbs_d'.DS.$town['Town']['image'],
								array(
									'title' => $town['Town']['name'],
									'url' => '/towns/move/'.$town['Town']['id'],
									'class' => 'dark'.($present ? ' present' : ''),
									'town-id' => $town['Town']['id']
								)
							); ?>
						<? endif; ?>
					</td>
				<? endfor; ?>
			</tr>
		<? endfor; ?>
	</tbody>
</table>