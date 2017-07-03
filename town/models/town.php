<?php
class Town extends AppModel{
	var $units = array(
		'泉' => array('name' => '温泉','url' => array('controller' => 'spas','action' => 'index'),'src' => 'spa.gif','title' => '入ると回復します。'),
		'学' => array('name' => '学校','url' => array('controller' => 'schools','action' => 'index'),'src' => 'school.gif','title' => '能力をあげれます。'),
		'店' => array('name' => 'デパート','url' => array('controller' => 'shops','action' => 'buy'),'src' => 'shop.gif','title' => 'いろんなお店があります。'),
		'建' => array('name' => '建設会社','url' => array('controller' => 'houses','action' => 'add'),'src' => 'builder.gif','title' => 'おうちを建てたりできます。'),
		'地' => array('name' => '売地','src' => 'sell.gif','title' => 'ここに家が建てられます。','class' => 'sell'),
		'板' => array('name' => '掲示板','url' => array('controller' => 'forums','action' => 'view'),'src' => 'bbs.gif','title' => '掲示板です。'),
		'職' => array('name' => 'ハローワーク','url' => array('controller' => 'jobs','action' => 'index'),'src' => 'hellowork.gif','title' => '職業斡旋所です。'),
		'社' => array('name' => '会社','url' => array('controller' => 'jobs','action' => 'work'),'src' => 'company.gif','title' => '会社員の方はここで働けます。'),
		'銀' => array('name' => '銀行','url' => array('controller' => 'banks','action' => 'index'),'src' => 'bank.gif','title' => 'お金を預けたり...'),
		'役' => array('name' => '役場','url' => array('controller' => 'governments','action' => 'index'),'src' => 'gov.gif','title' => 'ランキングやニュースなどがあります。'),
		'案' => array('name' => 'アンケート','url' => array('controller' => 'questions','action' => 'index'),'src' => 'enq.gif','title' => 'アンケートです。'),
		'問' => array('name' => '問屋','url' => array('controller' => 'buyers','action' => 'index'),'src' => 'buyer.gif','title' => '商品を仕入れることができます。'),
		'遊' => array('name' => 'ゲームセンター','url' => array('controller' => 'games','action' => 'index'),'src' => 'coin.gif','title' => 'カジノみたいな感じ'),
		'宿' => array('name' => 'アパート','url' => array('controller' => 'aparts','action' => 'index'),'src' => 'apart.gif','title' => 'アパートです。','class' => 'apart')
	);

	function getData($id){
		$data = array();
		$handle = fopen(LOGS.'towns'.DS.$id.'.csv','r');
		while ($row = fgetcsv($handle)) {
			$data[] = $row;
		}
		fclose($handle);
		return $data;
	}
}