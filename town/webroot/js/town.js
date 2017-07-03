$chara = function(elem){
	this.elem = elem;
	this.name = elem.find(' > span');
	this.speed = {x:0,y:0};
	this.left = parseInt(elem.css('left'));
	this.top = parseInt(elem.css('top'));
	this.width = parseInt(elem.width());
	this.height = 48;
};
$chara.prototype.speedUp = function(x,y){
	if(!x && !y) return;

	this.speed.x += x;
	this.speed.y += y;
};
$chara.prototype.move = function(x,y){
	this.left += x || this.speed.x;
	this.top += y || this.speed.y;
	
	if(this.left < 0){
		this.left = 0;
		this.spped.x = 0;
		
		if($town.towns.left) this.moveMap('left');
	}else if(this.left + this.width > $town.width){
		this.left = $town.width - this.width;
		this.spped.x = 0;
		
		if($town.towns.right) this.moveMap('right');
	}else if(this.top < 0){
		this.top = 0;
		this.speed.y = 0;
		
		if($town.towns.up) this.moveMap('up');
	}else if(this.top + this.height > $town.height){
		this.top = $town.height - this.height;
		this.speed.y = 0;
		
		if($town.towns.down) this.moveMap('down');
	}
	
	this.name.css({left:this.speed.x,top:this.speed.y});
	this.elem.css({left:this.left,top:this.top});
};
$chara.prototype.moveMap = function(vactor){
	var top = this.top,
		left = this.left;
	
	switch(vector){
		case 'left':
			left = $town.width - $chara.width;
			break;
		case 'right':
			left = 0;
			break;
		case 'up':
			top = $town.height - this.height;
			break;
		case 'down':
			top = 0;
			break;
	}
	$town.reload(vector,left,top);
};
$chara.key = {
	left : null,
	right : null,
	up : null,
	down : null
};
$chara.baseSpeed = 0;
$chara.hide = false;

//$start.town = 
$town = function(){
	$("#mapReload").hide();
	
	$town.width = $("#towns").width();
	$town.height = $("#towns").height();
	
	$chara.user = new $chara($("#user"));
	
	var cursor = ["left","up","right","down"];
	var keyCodeToCursor = {37:"left",38:"up",39:"right",40:"down"};
	var move = function(key){
		$chara.key[key] = true;
	};
	var stop = function(key){
		$chara.key[key] = false;
	};
	for(var i=0;i<4;i++){
		$(document).bind("keydown",{
			combi : cursor[i],
			disableInInput : true
		},function(e){
			move(keycodeToCursor[e.which]);
			e.preventDefault();
		});
		$(document).bind("keyup",{
			combi : cursor[i],
			disableInInput : true
		},function(e){
			stop(keycodeToCursor[e.which]);
		});
	}
	
	setInterval(function(){
		var x = 0,y = 0;
		if($chara.key.left) x -= 1;
		if($chara.key.right) x += 1;
		if($chara.key.up) x -= 1;
		if($chara.key.down) x += 1;
		
		$chara.user.speedUp(x*$chara.baseSpeed,y*$chara.baseSpeed);
		$chara.user.move();
	},50);
	
	$("#charaHide").toggle(
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
	
	// マップ自動更新
	$town.timer = setInterval($town.charaReload,$town.interval);
};

$town.timer = null;
$town.reload = false;
$town.interval = 30*1000;
$town.width = 320;
$town.height = 320;

$town.reload = function(vector,x,y){
	if($town.reload) return;
	$town.reload = true;
	
	$("#townReload").html("<span><img src='./img/loading.gif'>　" + (vector ? "移動中..." : "更新中...") + "</span>").fadeIn("fast");
	
	$.ajax({
		url : "/towns/index/" + vector,
		data : {
			x : x,
			y : y
		},
		dataType : 'json',
		success : function(data){
			$town.redraw(data,vector);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$("#townReload").addError("マップ更新に失敗しました！");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			setTimeout(function(){
				$("#townReload").fadeOut("fast");
				$town.reload = false;
			},2000);
			this;
		}
	});
};

$town.charaReload = function(){
	$town.reload('',$chara.user.left,$chara.user.top);
};

$town.redraw = function(data,vector){
	$("#charas").remove();
	
	if(vector){
		var left = 0,top = 0;
		switch(vector){
			case "left":
				left = -$town.width;
				break;
				
			case "up":
				top = -$town.height;
				break;
				
			case "right":
				left = $town.width;
				break;
				
			case "down":
				top = $town.height;
				break;
		}
		
		$("#town").attr("id","preTown");
		$(data.html).setup().appendTo('#towns');
		$("#town,#chara").css({left:left,top:top,position:'absolute'});
		$("#towns").animate({left:-left,top:-top},2000,function(){
			$("#preTown").remove();
			$("#towns").css({position:"relative",left:0,top:0});
			$("#chara").css({position:"static"});
		});
		
		$town.towns = data.towns;
		
		$('#mapNow').attr('id','');
		$('#map').find("img[title='"+$town.towns.now+"']").attr('id','mapNow');
	}else{
		$(data.html).setup().appendTo('#towns');
	}
	
	$chara.user = new Chara($("#user"));
	
	if($chara.hide) $("div.chara").hide();
};
