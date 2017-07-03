<?php
$styles[] = 'css/home';
$scripts[] = 'js/boku2d-base';
$scripts[] = 'js/boku2d-dom';
$scripts[] = 'js/boku2d-extension';
$scripts[] = 'js/home'
?>

<div id='world'>
	<div class='object float' style='left:10px;top:10px;'>
		<a href='/town_dev/'><img src='/image/town.gif'>TOWN</a>
	</div>
	<div class='object float' style='left:100px;top:10px;'>
		<a href='/bottom/'><img src='/image/bottom.png'>Bottom Coder</a>
	</div>
	<div class='object float' style='left:240px;top:10px;'>
		<a href='/akasheet/'>赤シート++</a>
	</div>
	<div class='object float' style='left:10px;top:70px;'>
		<a href='/blog/'><img src='/image/wp.png'>ブログ</a>
	</div>
	<div class='object float' style='left:10px;top:130px;'>
		<a href='/dev/'><img src='/image/dev.png'>デブ</a>
	</div>
	<div class='object float' style='left:10px;top:190px;'>
		<a href='//twitter.com/tyage'><img src='/image/twitter.png'>Twitter</a>
	</div>
	<div class='object float' style='left:10px;top:250px;'>
		<a href='/thanks'><img src='/image/world.png'>御礼</a>
	</div>
	<div class='object fixed' style='top:0;left:0;height:1px;width:100%;'></div>
	<div class='object fixed' style='bottom:0;left:0;height:1px;width:100%;'></div>
	<div class='object fixed' style='left:0;top:0;width:1px;height:300px;'></div>
	<div class='object fixed' style='right:0;top:0;width:1px;height:300px;'></div>
	<img class='object controll' src='/image/masao/stop.gif' style='top:150px;left:150px;'>
	<img class='object cloud' src='/image/masao/item/cloud.gif' style='top:220px;left:300px;'>
	<img class='object cloud' src='/image/masao/item/cloud.gif' style='top:170px;left:400px;'>
	<img class='object cloud' src='/image/masao/item/cloud.gif' style='top:120px;left:500px;'>
	<img class='object cloud' src='/image/masao/item/cloud.gif' style='top:70px;left:600px;'>
	
	<nav id='command'>
		<ul>
			<li id='addKame'><img alt='亀を追加' src='/image/masao/kame/left.gif'></li>
			<li id='addMariri'><img alt='マリリを追加' src='/image/masao/mariri/stop.gif'></li>
			<li id='addHino'><img alt='ヒノララシを追加' src='/image/masao/hino/left.gif'></li>
			<li id='addPoppi'><img alt='ポッピーを追加' src='/image/masao/poppi/left.gif'></li>
		</ul>
	</nav>
</div>
