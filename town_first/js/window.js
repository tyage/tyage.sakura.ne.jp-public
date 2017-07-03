var $win = {
	down : null,
	active : null,
	windows : []
};

$win.set = function(show){
	//一部表示
	$.each(show,function(name,type){
		if(type == "show") $win.open(name);
	});
	
	//マウスがダウンされていたらウィンドウ移動
	$(document).mousemove(function(e){
		if($win.down == null) return;
		e.preventDefault();
		var self = $win.windows[$win.down];
		self.elem.css({
			left : self.offset.left + e.clientX - self.x,
			top : self.offset.top + e.clientY - self.y
		});
	});
};

$win.open = function(type){
	//レイヤーをあげる
	if($win.active) $win.windows[$win.active].elem.css("z-index",999);
	$win.active = type;
	
	var winNow = $win.windows[type];
	if(winNow){
		winNow.elem.css("z-index",1000).slideToggle("slow");
		return;
	}
	
	switch(type){
		case "chat":
			$win.windows[type] = new $windowMenu(type,"チャット",function(){
				$chat.reload();
				$chat.set();
			});
			break;
			
		case "mail":
			$win.windows[type] = new $windowMenu(type,"メール",function(){
				$mail.reload();
				$mail.set();
				$("#mailWindow > div.content .justify").labelJustify();
			});
			break;
			
		case "item":
			$win.windows[type] = new $windowMenu(type,"アイテム",function(){
				$item.reload();
				$item.set();
			});
			break;
			
		case "config":
			$win.windows[type] = new $windowMenu(type,"設定",function(){
				$config.set();
				$("#configWindow > div.content .justify").labelJustify();
			});
			break;
			
		case "house":
			$win.windows[type] = new $windowMenu(type,"家",function(){
				$house.set();
				$("#houseWindow > div.content .justify").labelJustify();
			});
			break;
			
		case "status":
			$win.windows[type] = new $windowMenu(type,"ステータス",function(){
				$("#statusWindow > div.content .justify").labelJustify();
				$("#status").tab();
			});
			break;
	}
	
};

var $windowMenu = function(type,name,callback){
	this.x = this.y = this.width = this.height = 0;
	this.offset = {};
	this.type = type;
	this.elem = $("<div id='"+type+"Window' class='window'></div>").appendTo("body");
	
	var self = this;
	$.ajax({
		type : "POST",
		url : "./?mode=Window&command="+type,
		success : function(data){
    	self.elem.html("<div class='top'>"+
			"<div class='title'><span class='title'>"+name+"</span><a href='./?mode=Window&amp;command="+type+"&amp;type=new' target='_blank'>新しいウィンドウで開く</a></div>"+
			"<div class='command'><span class='min'>小</span><span class='middle'>中</span><span class='max'>大</span><span class='close'>×</span></div>"+
			"</div>"+
			"<div class='content'>"+data+"</div>");
			
			self.bind();
			self.width = self.elem.width();
			self.height = self.elem.height();
			self.elem.find(" > div.top > div.command > span.middle").hide();
			self.show();
			
			if(callback) callback();
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			self.elem.html("ウィンドウ情報取得に失敗しました。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};
$windowMenu.prototype.bind = function(){
	//ウィンドウを押したら前面に表示
	this.elem.bind("mousedown",{type:this.type},function(e){
		var type = e.data.type;
		if($win.active) $win.windows[$win.active].elem.css("z-index",999);
		$(this).css("z-index",1000);
		$win.active = type;
	});
	
	//ウィンドウ移動準備
	this.elem.find(" > div.top").bind("mousedown",{type:this.type},function(e){
		var type = e.data.type;
		var self = $win.windows[type];
		
		self.offset = self.elem.offset();
		self.x = e.clientX;
		self.y = e.clientY;
		
		$win.down = type;
	});
	//ウィンドウ移動終了
	this.elem.find(" > div.top").bind("mouseup",{type:this.type},function(e){		
		var type = e.data.type;
		$win.down = null;
	});
	
	//ウィンドウ最小クリック
	this.elem.find(" > div.top > div.command > span.min").bind("click",{type:this.type},function(e){
		var type = e.data.type;
		var self = $win.windows[type];
		self.elem.css("height","").animate({width:self.width}).find(" > div.content").slideUp("slow");
		$(this).parent().find(" > span.min").hide().parent().find(" > span.middle,span.max").show();
	});
	//ウィンドウ中くらいクリック
	this.elem.find(" > div.top > div.command > span.middle").bind("click",{type:this.type},function(e){
		var type = e.data.type;
		var self = $win.windows[type];
		self.elem.animate({width:self.width,height:self.height}).find(" > div.content:hidden").slideDown("fast");
		$(this).parent().find(" > span.middle").hide().parent().find(" > span.min,span.max").show();
	});
	//ウィンドウ最大クリック
	this.elem.find(" > div.top > div.command > span.max").bind("click",{type:this.type},function(e){
		var type = e.data.type;
		var self = $win.windows[type];
		self.elem.animate({width:"100%",height:"100%",left:$(window).scrollLeft(),top:$(window).scrollTop()}).find(" > div.content").animate({height:"80%"});
		$(this).parent().find(" > span.max").hide().parent().find(" > span.min,span.middle").show();
	});
	
	//ウィンドウ非表示
	this.elem.find(" > div.top > div.command > span.close").bind("click",{type:this.type},function(e){
		var type = e.data.type;
		$win.windows[type].elem.slideUp("slow");
	});
};
$windowMenu.prototype.show = function(){
	//アニメーション
	var offset = $("#windowMenu").offset();
	this.elem.css({
		left : offset.left,
		top : offset.top,
		width : 0,
		height : 0
	}).animate({
		left : Math.ceil(Math.random() * 150) + 50,
		top : offset.top + Math.ceil(Math.random() * 150) + 50,
		width : this.width,
		height :this.height
	},1000,function(){
		$(this).css("height","");
	});
};
