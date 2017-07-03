var $chara = {
	my : false,
	myName : false,
	timer : false,
	key : {right:0,left:0,down:0,up:0},
	next : {x:0,y:0},
	left : 0,
	top : 0,
	speed : 0,
	zindex : 20,
	width : 0,
	height : 0
};

$chara.move = function(){
	if($map.reload_flag || $chara.hide) return;
	
	$chara.next.x += $chara.key.right - $chara.key.left;
	$chara.next.y += $chara.key.down - $chara.key.up;
	if($chara.next.x == 0 && $chara.next.y == 0) return;
	
	if($chara.next.x > 0) $chara.next.x -= $chara.speed / 2;
	if($chara.next.x < 0) $chara.next.x += $chara.speed / 2;
	if($chara.next.y > 0) $chara.next.y -= $chara.speed / 2;
	if($chara.next.y < 0) $chara.next.y += $chara.speed / 2;
	
	$chara.left_tmp = $chara.left;
	$chara.top_tmp = $chara.top;
	
	$chara.left += $chara.next.x;
	$chara.top += $chara.next.y;
	
	if($chara.left < 0){
		$chara.next.x = 0;
		if($map.data.left){
			$chara.next.y = 0;
			$chara.left = $map.width - $chara.width;
			$map.reload("left",$chara.left,$chara.top_tmp);
			return false;
		}else{
			$chara.left = 0;
		}
	}
	if($chara.left + $chara.width > $map.width){
		$chara.next.x = 0;
		if($map.data.right){
			$chara.next.y = 0;
			$chara.left = 0;
			$map.reload("right",$chara.left,$chara.top_tmp);
			return false;
		}else{
			$chara.left = $map.width - $chara.width;
		}
	}
	if($chara.top < 0){
		$chara.next.y = 0;
		if($map.data.up){
			$chara.next.x = 0;
			$chara.top = $map.height - $chara.height;
			$map.reload("up",$chara.left_tmp,$chara.top);
			return false;
		}else{
			$chara.top = 0;
		}
	}
	if($chara.top + $chara.height > $map.height){
		$chara.next.y = 0;
		if($map.data.down){
			$chara.next.x = 0;
			$chara.top = 0;
			$map.reload("down",$chara.left_tmp,$chara.top);
			return false;
		}else{
			$chara.top = $map.height - $chara.height;
		}
	}
	
	$chara.myName.css({left:$chara.next.x + "px",top:$chara.next.y + "px"});
	$chara.my.css({left:$chara.left + "px",top:$chara.top + "px"});
};

var $map = {
	timer : false,
	reload_flag : false,
	reload_time : 30,
	width : 320,
	height : 320,
	navi_closed : {}
};

$map.set = function(){
	$("#map_reload").hide();
	
	//キャラ移動
	$map.width = $("#map_now").width();
	$map.height = $("#map_now").height();
	
	$chara.my = $("#chara_my");
	$chara.myName = $chara.my.find(" > span");
	
	$chara.left = parseInt($chara.my.css("left"));
	$chara.top = parseInt($chara.my.css("top"));
	$chara.width = $chara.my.width();
	$chara.height = 48;//$chara.my.height()だと誤差が生じる
	
	var cursor = ["left","up","right","down"];
	var keycodeToCursor = {37:"left",38:"up",39:"right",40:"down"};
	var moveChara = function(key){
		$chara.key[key] = $chara.speed;
	};
	var stopChara = function(key){
		$chara.key[key] = 0;
	};
	for(var i=0;i<4;i++){
		$(document).bind("keydown",{
			combi : cursor[i],
			disableInInput : true
		},function(e){
			moveChara(keycodeToCursor[e.which]);
			e.preventDefault();
		});
		$(document).bind("keyup",{
			combi : cursor[i],
			disableInInput : true
		},function(e){
			stopChara(keycodeToCursor[e.which]);
		});
		
		$("#cursor img."+cursor[i]).bind("mousedown",{vector:cursor[i]},function(e){
			$(this).attr("src","./img/arrow/"+e.data.vector+"2.gif");
			moveChara(e.data.vector);
		});
		$("#cursor img."+cursor[i]).bind("mouseup",{vector:cursor[i]},function(e){
			$(this).attr("src","./img/arrow/"+e.data.vector+".gif");
			stopChara(e.data.vector);
		});
	}
	
	$chara.timer = setInterval(function(){
		$chara.move();
	},50);
	
	//キャラ表示/非表示
	$("#chara_delete").toggle(
		function(){
			if($(".chara").is(":animated")) return;
			$(".chara").fadeOut("slow");
			$chara.hide = true;
			$(this).html("キャラ全て蘇生");
		},
		function(){
			if($(".chara").is(":animated")) return;
			$(".chara").fadeIn("normal");
			$chara.hide = false;
			$(this).html("キャラ全て死亡");
		}
	);
	
	//マップ自動更新
	$map.timer = setInterval($map.my_reload,1000 * $map.reload_time);
	
	//キャラ情報表示
	$("div.chara").live("click",function(e){
		$(this).chara_view(e);
	});
	
	$("#map_now > div img").navi();
};
$map.reload = function(next_town,town_x,town_y){
	if($map.reload_flag) return;
	$map.reload_flag = true;
	
	$("#map_reload").html("<span><img src='./img/loading.gif'>　" + (next_town ? "移動中..." : "参加者情報更新中...") + "</span>").fadeIn("fast");
	
	$.ajax({
		type : "POST",
		url : "./?mode=Map&command=reload",
		data : {
			town : next_town,
			x : town_x,
			y : town_y
		},
		success : function(data){
			$map.redraw(data,next_town);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$("#map_reload").html("<span>更新に失敗しました！</span>");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			setTimeout(function(){
				$("#map_reload").fadeOut("fast");
				$map.reload_flag = false;
			},2000);
			this;
		}
	});
};

$map.my_reload = function(){
	$map.reload("",parseInt($("#chara_my").css("left")),parseInt($("#chara_my").css("top")));
};

$map.redraw = function(data,next_town){
	$("#map_chara").remove();
	
	if(next_town){
		var left = 0,top = 0;
		switch(next_town){
			case "left":
				left = -$map.width;
				break;
				
			case "up":
				top = -$map.height;
				break;
				
			case "right":
				left = $map.width;
				break;
				
			case "down":
				top = $map.height;
				break;
		}
		
		$("#map_data").remove();
		$("#town_data").remove();
		
		$("#map_now").attr("id","map_old");
		$("#map").append(data);
		$("#map_now,#map_chara").css({left:left,top:top,position:"absolute"});
		$("#map").animate({left:-left,top:-top},2000,function(){
			$("#map_old").remove();
			$("#map_now,#map").css({position:"relative",left:0,top:0});
			$("#map_chara").css({position:"static",left:0,top:0});
		});
		
		$map.data = {
			now : $("#map_data").attr("now"),
			up : $("#map_data").attr("up"),
			down : $("#map_data").attr("down"),
			left : $("#map_data").attr("left"),
			right : $("#map_data").attr("right")
		};
		
		$("#mapNow").attr("src","./img/map/thumbs/"+$("#mapNow").attr("title")+"_d.jpg").attr("id","");
    var nextMap = $("#mapImages").find("img[title='"+$map.data.now+"']");
		nextMap.attr("src","./img/map/thumbs/"+nextMap.attr("title")+".jpg").attr("id","mapNow");
		
		$("#map_now > div img").navi();
	}else{
		$("#map").append(data);
	}
	
	//自分のキャラ情報更新
	$chara.my = $("#chara_my:last");
	$chara.myName = $chara.my.find(" > span");
	$chara.left = parseInt($chara.my.css("left"));
	$chara.top = parseInt($chara.my.css("top"));
	
	$("div.chara").css("z-index",$chara.zindex);
	if($chara.hide) $("div.chara").hide();
};

$map.unit_get = function(unit,house){
	var key = house ? "house" : "unit";
	var post_data = {};
	post_data[key] = unit;
	$.ajax({
		type : "POST",
		url : "./?mode=Ajax&command=unit_data",
		data : post_data,
		success : function(data){
			$("#unit_data").append("<div title='"+unit+"'>"+data+"</div>");
		}
	});
};
