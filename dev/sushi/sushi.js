$(function(){
var 
$dropped = $money = $count = $gram = $speed = 0,
$hungry_up = 10,
$hungry = 500,
$clicked = false,
$mouse = {
	left : 0,
	top : 0
},
$sushi_offset = {
	left : 0,
	top : 0
},
$table = $("#table"),
$table_data = {
	offset : $table.find('img').offset(),
	width : $table.width(),
	height : $table.height()
},
$neta = {
	ebi : {name:"海老",url:"ebi.gif",price:120,gram:50},
	maguro : {name:"マグロ",url:"maguro.gif",price:150,gram:60},
	tako : {name:"たこ",url:"tako.gif",price:100,gram:40},
	tamago : {name:"たまご",url:"tamago.gif",price:100,gram:40},
	toro : {name:"トロ",url:"toro.gif",price:300,gram:60},
	unagi : {name:"うなぎ",url:"unagi.gif",price:150,gram:60},
	uni : {name:"うに",url:"uni.gif",price:200,gram:50},
	inari : {name:"いなり",url:"inari.gif",price:100,gram:40},
	kappa : {name:"かっぱ巻き",url:"kappa.gif",price:100,gram:40},
	ika : {name:"いか",url:"ika.gif",price:120,gram:60},
	ikrua : {name:"いくら",url:"ikura.gif",price:200,gram:50},
	yaki_samon : {name:"焼きサーモン",url:"yaki_samon.gif",price:150,gram:60},
	samon : {name:"サーモン",url:"samon.gif",price:120,gram:60}
},
$names = [],
$result = $("#result > tbody > tr"),
$table_top = $("#scroll_table").position().top,
$parentPos = $('#Content').position();

//----- 結果画面更新 -----//

function result_reload(){
	$result.find(" > td.count").html($count+"個");
	$result.find(" > td.dropped").html($dropped+"個");
	$result.find(" > td.gram").html($gram+"グラム");
	$result.find(" > td.hungry").html($hungry > 0 ? $hungry + "グラム" : "<span style='color:red'>お腹いっぱいです！</span>");
	$result.find(" > td.money").html($money+"円");
}

//----- 寿司メーカー -----//

function make_sushi(){
	var $name = $names[Math.floor(Math.random() * $names.length)],$sushi = $neta[$name];
	
	$("<div style=\"background-image: url('/image/sushi/"+$sushi.url+"')\"></div>")
	.css("top",$table_top - 16)
	.appendTo("#scroll")
	.data("name",$name)
	.animate({
		left : "10%"
	},{
		duration : 1000*($speed + Math.floor(Math.random()*5)),
	 	complete : function(){
			$(this).addClass("clicked").fadeOut("slow",function(){
				$(this).remove();
			});
		}
	});
}

//----- ドラッグ＆ドロップ処理 -----//

$("#scroll > div:not(div.clicked)").live("mousedown",function(e){
	if($hungry <= 0) return false;
	if($clicked) $clicked.mouseup();
	
	$clicked = $(this);
	$clicked.stop().addClass("clicked");
	$mouse = {
		left : e.clientX + $parentPos.left,
		top : e.clientY + $parentPos.top
	};
	$sushi_offset = $clicked.offset();
});

$(document).mousemove(function(e){
	if(!$clicked) return false;
	$clicked.css({
		left : e.clientX - $mouse.left + $sushi_offset.left,
		top : e.clientY - $mouse.top + $sushi_offset.top
	});
});

$(document).mouseup(function(e){
	if(!$clicked) return false;
	
	var $sushi_data = $neta[$clicked.data("name")],$offset = $clicked.offset();
	
	if($offset.left > $table_data.offset.left + $table_data.width || $offset.left + $clicked.width() < $table_data.offset.left || $offset.top > $table_data.offset.top + $table_data.height/2){
		var $drop = 150 + Math.floor(Math.random()*750);
		$clicked.html("<span>アーッ！</span>").animate({
			top : "+="+$drop+"px"
		},{
			complete : function(){
				$(this).fadeOut("slow",function(){
					$(this).remove();
				});
			}
		});
		
		$money += $sushi_data.price;
		$dropped++;
		
		result_reload();
		
		$result.find(" > td.dropped").append("<span>1UP</span>");
		$result.find(" > td.money").append("<span>"+$sushi_data.price+"UP</span>");
	}else{
		$clicked.html("<span>ウマイ！</span>").animate({
			top : $table_data.offset.top
		},{
			complete : function(){
				$(this).fadeOut("slow",function(){
					$(this).remove();
				});
			}
		});
		
		$gram += $sushi_data.gram;
		$hungry -= $sushi_data.gram;
		$money += $sushi_data.price;
		$count++;
		
		result_reload();
		
		$result.find(" > td.gram").append("<span>"+$sushi_data.gram+"UP</span>");
		$result.find(" > td.hungry").append("<span>"+$sushi_data.gram+"DOWN</span>");
		$result.find(" > td.money").append("<span>"+$sushi_data.price+"UP</span>");
		$result.find(" > td.count").append("<span>1UP</span>");
	}
	
	$clicked = false;
});

//----- 追加処理 -----//
$("#add").submit(function(e){
	var $add = $(":text:first",this).val();
	for($i = 0;$i < $add;$i++) make_sushi();
	
	e.preventDefault();
	e.stopPropagation();
});

//----- スピード変更処理 -----//
$("#speed").submit(function(e){
	$speed = Math.floor($(":text:first",this).val());
	
	e.preventDefault();
	e.stopPropagation();
});

//----- 開始 -----//

$("#shokunin").css("top",$table_top - 32);

result_reload();

$("#speed").submit();

var $neta_table = $("#neta > tbody");
$.each($neta,function($name,$data){
	$neta_table.append("<tr><td><img src='/image/sushi/"+$data.url+"' />"+$data.name+"</td><td>"+$data.price+"円</td><td>"+$data.gram+"グラム</td></tr>");
	$names.push($name);
});

setInterval(function(){
	make_sushi();
	
	$hungry = $hungry + $hungry_up;
	$result.find(" > td.hungry").append("<span>"+$hungry_up+"UP</span>");
	
	result_reload();
	
},1000);

});