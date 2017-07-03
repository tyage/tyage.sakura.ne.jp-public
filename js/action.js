AL = function(){

var 
timer = null,
items = [],
key = {
	up : false,
	down : false,
	left : false,
	right : false,
	space : false
},
ini = {
	timerSpeed : 50,
	keyno : {
		37 : "left",
		38 : "up",
		39 : "right",
		40 : "down",
		32 : "space"
	},
	window : {
		width : 750,
		height : 500,
		bounce : {
			top : 0.5,
			right : 0.5,
			bottom : 0.5,
			left : 0.5
		},
		scroll : {
			top : 0,
			right : 0,
			bottom : 0,
			left : 0
		},
		loop : {
			x : false,
			y : false
		}
	}
},
def = {
	type : "block",
	x : "rand",
	y : "rand",
	width : 32,
	height : 32,
	jumpRate : 20,
	limit : -1,
	baseSpeed : {
		x : 0,
		y : 0,
		jx : 0,
		jy : 0,
		gx : 0,
		gy : 0,
		rx : 0,
		ry : 0
	},
	maxSpeed : {},
	minSpeed : {},
	speed : {},
	bounce : {
		top : 1/2,
		right : 1/2,
		bottom : 1/2,
		left : 1/2
	},
	scroll : {},
	img : {},
	elem : {},
	isBlocked : {},
	isBlockable : ["top","right","bottom","left"],
	isPushable : true
},
elems = {
	room : null,
	link : null,
	help : null
};

var item = function(config){
	this.no = items.length;
	this.x = this.y = this.width = this.height = this.link = this.href = this.linkDetail = this.jumpRate = this.limit = this.content = this.type = null;
	this.isDeleted = this.isTreadable = this.isDeletable = this.isPushable = false;
	this.old = {x:0,y:0,sx:0,sy:0};
	this.max = {x:0,y:0};
	this.min = {x:0,y:0};
	this.baseSpeed = {x:0,y:0,jx:0,jy:0,gx:0,gy:0,rx:0,ry:0};
	this.maxSpeed = {x:0,y:0};
	this.minSpeed = {x:0,y:0};
	this.speed = {x:0,y:0};
	this.bounce = {top:0,right:0,bottom:0,left:0};
	this.scroll = {top:0,right:0,bottom:0,left:0};
	this.img = {now:null,stop:null,up:null,right:null,right2:null,down:null,left:null,left2:null,uleft:null,uright:null,dleft:null,dright:null,bleft:null,bright:null,tleft:null,tright:null};
	this.elem = {};
	this.isBlocked = {
		up : false,
		right : false,
		down : false,
		left : false
	};
	this.isBlockable = [];
	
	overwriteObject(this,def);
	overwriteObject(this,config);
	
	this.makeIsBlockable();
	if(this.x == "rand") this.x = Math.round(Math.random()*ini.window.width);
	if(this.y == "rand") this.y = Math.round(Math.random()*ini.window.height);
	this.old.sx = this.speed.x;
	this.old.sy = this.speed.y;
	this.old.x = this.x;
	this.old.y = this.y;
	
	this.createElement();
	overwriteObject(this.elem,def.elem);
	overwriteObject(this.elem,config.elem);
	
	items.push(this);
};
item.prototype.makeIsBlockable = function(){
	var isBlockable = {top:false,right:false,bottom:false,left:false};
	for(var i=this.isBlockable.length;i>0;i--){
		isBlockable[this.isBlockable[i-1]] = true;
	}
	this.isBlockable = this.isBlockable.length > 0 ? isBlockable : null;
};
item.prototype.createElement = function(){
	if(this.img.stop){
		this.elem = document.createElement("img");
		this.elem.setAttribute("src",this.img.stop);
		this.img.now = this.img.stop;
	}else{
		this.elem = document.createElement("div");
		this.elem.innerHTML = this.content || "";
	}
	
	//IEだとリンク詳細表示の時に、リンクの内部のエレメントからnoを取るため。
	this.elem.setAttribute("no",this.no);
	
	if(this.href != null){
		var linkElem = document.createElement("a");
		linkElem.setAttribute("href",this.href);
		linkElem.setAttribute("title",this.linkDetail);
		linkElem.appendChild(this.elem);
		this.elem = linkElem;
		
		addEvent(this.elem,"mouseover",function(e,obj){
			var link = items[obj.getAttribute("no")];
			reloadLink(link.x+link.width,link.y+link.height,link);
		});
		addEvent(this.elem,"mouseout",function(e,obj){
			elems.linkDetail.style.display = "none";
		});
	}
	
	if(this.isDeletable){
		addEvent(this.elem,"mousedown",function(e,obj){
			items[obj.getAttribute("no")].kill();
		});
	}
	
	this.elem.className = "ALitem";
	this.elem.style.position = "absolute";
	this.elem.setAttribute("no",this.no);
	this.elem.style.width = this.width + "px";
	this.elem.style.height = this.height + "px";
	this.reload();
	
	elems.room.appendChild(this.elem);
};
item.prototype.move = function(){
	if(--this.limit == 0) this.kill();
	if(this.isDeleted) return false;
	
	this.old.sx = this.speed.x;
	this.old.sy = this.speed.y;
	this.old.x = this.x;
	this.old.y = this.y;
	
	this.changeSpeed();
	
	this.x += this.speed.x;
	this.y += this.speed.y;
	
	if(this.link != null){
		if(!this.isTouch(this.link)){
			this.link = null;
			elems.linkDetail.style.display = "none";
		}else if(key.space){
			clearInterval(timer);
			window.location = this.link.href;
		}
	}
	
	if(this.isPushable) this.blockAll();
	this.changeImage();
	this.reload();
};
item.prototype.changeSpeed = function(){
	this.speed.x += this.baseSpeed.gx;
	this.speed.y += this.baseSpeed.gy;
	
	switch(this.type){
		case "control":
			this.speed.x += (key.right ? this.baseSpeed.x : 0) - (key.left ? this.baseSpeed.x : 0);
			this.speed.y += (key.down ? this.baseSpeed.y : 0) - (key.up ? this.baseSpeed.y : 0);
			if(key.up && this.isBlocked.down) this.speed.y = -this.baseSpeed.jy;
			if(key.down && this.isBlocked.up) this.speed.y = this.baseSpeed.jy;
			if(key.left && this.isBlocked.right) this.speed.x = -this.baseSpeed.jx;
			if(key.right && this.isBlocked.left) this.speed.x = this.baseSpeed.jx;
			
			break;
		case "rand":
			this.speed.x += Math.round(Math.random()*this.baseSpeed.x*2 - this.baseSpeed.x);
			this.speed.y += Math.round(Math.random()*this.baseSpeed.y*2 - this.baseSpeed.y);
			if(this.isBlocked.down && Math.random()*this.jumpRate < 1) this.speed.y = -this.baseSpeed.jy;
			if(this.isBlocked.up && Math.random()*this.jumpRate < 1) this.speed.y = this.baseSpeed.jy;
			if(this.isBlocked.right && Math.random()*this.jumpRate < 1) this.speed.x = -this.baseSpeed.jx;
			if(this.isBlocked.left && Math.random()*this.jumpRate < 1) this.speed.x = this.baseSpeed.jx;
			
			break;
		case "elevator":
			if(this.speed.x == 0) this.speed.x = this.baseSpeed.x;
			if(this.speed.y == 0) this.speed.y = this.baseSpeed.y;
			if(this.max.x < this.x || this.isBlocked.left) this.speed.x = -Math.abs(this.baseSpeed.x);
			else if(this.min.x > this.x || this.isBlocked.right) this.speed.x = Math.abs(this.baseSpeed.x);
			if(this.max.y < this.y || this.isBlocked.down) this.speed.y = -Math.abs(this.baseSpeed.y);
			else if(this.min.y > this.y || this.isBlocked.up) this.speed.y = Math.abs(this.baseSpeed.y);
			
			break;
		case "block":
			
			break;
	}
	
	if(this.speed.x > 0) this.speed.x = this.speed.x - this.baseSpeed.rx > 0 ? this.speed.x - this.baseSpeed.rx : 0;
	if(this.speed.x < 0) this.speed.x = this.speed.x + this.baseSpeed.rx < 0 ? this.speed.x + this.baseSpeed.rx : 0;
	if(this.speed.y > 0) this.speed.y = this.speed.y - this.baseSpeed.ry > 0 ? this.speed.y - this.baseSpeed.ry : 0;
	if(this.speed.y < 0) this.speed.y = this.speed.y + this.baseSpeed.ry < 0 ? this.speed.y + this.baseSpeed.ry : 0;
	
	if(this.maxSpeed.x > 0 && Math.abs(this.speed.x) > this.maxSpeed.x) this.speed.x = this.speed.x > 0 ? this.maxSpeed.x : -this.maxSpeed.x;
	if(this.maxSpeed.y > 0 && Math.abs(this.speed.y) > this.maxSpeed.y) this.speed.y = this.speed.y > 0 ? this.maxSpeed.y : -this.maxSpeed.y;
	if(this.minSpeed.x > 0 && Math.abs(this.speed.x) < this.minSpeed.x) this.speed.x = this.speed.x > 0 ? this.minSpeed.x : -this.minSpeed.x;
	if(this.minSpeed.y > 0 && Math.abs(this.speed.y) < this.minSpeed.y) this.speed.y = this.speed.y > 0 ? this.minSpeed.y : -this.minSpeed.y;
};
item.prototype.blockAll = function(){
	this.isBlocked = {
		up : false,
		right : false,
		down : false,
		left : false
	};
	
	for(var i=items.length;i>0;i--){
		var item = items[i-1];
		if(i-1 == this.no || item.isDeleted) continue;
		
		if(this.type == "control" && item.href != null && this.isTouch(item)) this.reloadLink(item);
		
		if(isArray(item.isBlockable)) item.makeIsBlockable();
		if(!item.isBlockable) continue;
		
		if(this.x + this.width > item.x && this.x < item.x + item.width){
			if(item.isBlockable.top && this.old.y + this.height <= item.old.y && this.y + this.height >= item.y)
				this.block(item,"down");
			if(item.isBlockable.bottom && this.old.y >= item.old.y + item.height && this.y <= item.y + item.height)
				this.block(item,"up");
		}
		if(this.y + this.height > item.y && this.y < item.y + item.height){
			if(item.isBlockable.right && this.old.x >= item.old.x + item.width && this.x <= item.x + item.width)
				this.block(item,"left");
			if(item.isBlockable.left && this.old.x + this.width <= item.old.x && this.x + this.width >= item.x)
				this.block(item,"right");
		}
		
	}
	
	this.windowBlock();
};
item.prototype.block = function(block,direction){
	switch(direction){
		case "right":
			var blockDirection = "left";
			this.x = block.x - this.width + (block.speed.x < 0 ? block.speed.x : 0);
			break;
			
		case "left":
			var blockDirection = "right";
			this.x = block.x + block.width + (block.speed.x > 0 ? block.speed.x : 0);
			break;
			
		case "up":
			var blockDirection = "bottom";
			if(this.isTreadable) this.beTreated();
			this.y = block.y + block.height + (block.speed.y > 0 ? block.speed.y : 0);
			break;
			
		case "down":
			var blockDirection = "top";
			if(block.isTreadable) block.beTreated();
			this.y = block.y - this.height + (block.speed.y < 0 ? block.speed.y : 0);
			break;
	}
	switch(direction){
		case "right":
		case "left":
			if(block.isPushable) block.speed.x += this.speed.x;
			this.speed.x *= -block.bounce[blockDirection];
			this.y += block.speed.y + (block.scroll[blockDirection] < 0 && !this.isBlocked.up) || (block.scroll[blockDirection] > 0 && !this.isBlocked.down) ? block.scroll[blockDirection] : 0;
			break;
			
		case "up":
		case "down":
			if(block.isPushable) block.speed.y += this.speed.y;
			this.speed.y *= -block.bounce[blockDirection];
			this.x += block.speed.x + (block.scroll[blockDirection] < 0 && !this.isBlocked.left) || (block.scroll[blockDirection] > 0 && !this.isBlocked.right) ? block.scroll[blockDirection] : 0;
			break;
	}
	this.isBlocked[direction] = true;
};
item.prototype.windowBlock = function(){
	if(this.x + this.width > ini.window.width){
		if(ini.window.loop.x){
			if(this.x > ini.window.width) this.old.x = this.x = 0;
		}else{
			this.isBlocked.right = true;
			this.x = ini.window.width - this.width;
			this.speed.x *= -ini.window.bounce.right;
		}
		this.y += this.isBlocked.down || this.isBlocked.up ? 0 : ini.window.scroll.right;
	}
	if(this.x < 0){
		if(ini.window.loop.x){
			if(this.x + this.width < 0) this.old.x = this.x = ini.window.width - this.width;
		}else{
			this.isBlocked.left = true;
			this.x = 0;
			this.speed.x *= -ini.window.bounce.left;
		}
		this.y += this.isBlocked.down || this.isBlocked.up ? 0 : ini.window.scroll.left;
	}
	if(this.y < 0){
		if(ini.window.loop.y){
			if(this.y + this.height < 0) this.old.y = this.y = ini.window.height - this.height;
		}else{
			this.isBlocked.up = true;
			this.y = 0;
			this.speed.y *= -ini.window.bounce.top;
		}
		this.x += (ini.window.scroll.top < 0 && !this.isBlocked.left) || (ini.window.scroll.top > 0 && !this.isBlocked.right) ? ini.window.scroll.top : 0;
	}
	if(this.y + this.height > ini.window.height){
		if(ini.window.loop.y){
			if(this.y > ini.window.height) this.old.y = this.y = 0;
		}else{
			this.isBlocked.down = true;
			this.y = ini.window.height - this.height;
			this.speed.y *= -ini.window.bounce.bottom;
		}
		this.x += (ini.window.scroll.bottom < 0 && !this.isBlocked.left) || (ini.window.scroll.bottom > 0 && !this.isBlocked.right) ? ini.window.scroll.bottom : 0;
	}
};
item.prototype.reloadLink = function(link){
	if(this.link == null) this.link = link;
	reloadLink(this.x + this.width,this.y + this.height,link);
};
item.prototype.changeImage = function(){
	var img = null;
	
	// スピードだと、跳ねるときの誤差でうまくいかないので、座標差で調べる
	dif = {};
	dif.x = this.x - this.old.x;
	dif.y = this.y - this.old.y;
	dif.sx = this.speed.x - this.old.sx;
	dif.sy = this.speed.y - this.old.sy;
	
	if(dif.y < 0){
		if(dif.x > 0) img = this.img.uright;
		else if(dif.x < 0) img = this.img.uleft;
		else img = this.img.up;
	}else if(dif.y > 0){
		if(dif.x > 0) img = this.img.dright;
		else if(dif.x < 0) img = this.img.dleft;
		else img = this.img.down;
	}else{
		// なぜか分らないが、こうしないとブレーキ画像が上手く表示されない
		if(dif.x > 0) img = (dif.sx < 0 ? this.img.bright : (this.img.now == this.img.right ? this.img.right2 : this.img.right) );
		else if(dif.x < 0) img = (dif.sx < 0 ? (this.img.now == this.img.left ? this.img.left2 : this.img.left) : this.img.bleft );
		else img = this.img.stop;
	}
	
	if(img && img != this.img.now){
		this.img.now = img;
		this.elem.setAttribute("src",img);
	}
};
item.prototype.reload = function(){
	this.elem.style.left = Math.round(this.x) + "px";
	this.elem.style.top = Math.round(this.y) + "px";
};
item.prototype.kill = function(){
	elems.room.removeChild(this.elem);
	this.isDeleted = true;
};
item.prototype.beTreated = function(){
	this.limit = 10;
	this.isDeleted = true;
	
	img = this.speed.x < 0 ? this.img.tleft : this.img.tright;
	if(img) this.elem.setAttribute("src",img);
};
item.prototype.isTouch = function(block){
	return (Math.abs( (this.x + this.width/2) - (block.x + block.width/2) ) < this.width/2 + block.width/2 && Math.abs( (this.y + this.height/2) - (block.y + block.height/2) ) < this.height/2 + block.height/2);
};

function set(){
	elems = {
		room : document.getElementById("ALroom") || function(){
			var elem = document.createElement("div");
			elem.setAttribute("id","ALroom");
			var currentScript = document.getCurrentScript();
			currentScript.parentNode.insertBefore(elem,currentScript);
			return elem;
		}(),
		linkDetail : document.getElementById("ALlinkDetail") || function(){
			var elem = document.createElement("div");
			elem.setAttribute("id","ALlinkDetail");
			elem.style.position = "absolute";
			document.getElementById("ALroom").appendChild(elem);
			return elem;
		}(),
		help : document.getElementById("ALhelp") || function(){
			var elem = document.createElement("div");
			elem.setAttribute("id","ALhelp");
			document.getElementById("ALroom").appendChild(elem);
			return elem;
		}(),
		helpToggle : document.getElementById("ALhelpToggle") || function(){
			var elem = document.createElement("div");
			elem.style.width = "32px";
			elem.style.height = "32px";
			elem.innerHTML = "？";
			elem.setAttribute("id","ALhelpToggle");
			document.getElementById("ALhelp").appendChild(elem);
			return elem;
		}(),
		helpContent : document.getElementById("ALhelpContent") || function(){
			var elem = document.createElement("div");
			elem.style.position = "absolute";
			elem.innerHTML = "カーソルキーで移動<br>スペースでリンク先へ飛ぶ";
			elem.setAttribute("id","ALhelpContent");
			document.getElementById("ALhelp").appendChild(elem);
			return elem;
		}()
	};
	elems.helpContent.style.display = "none";
	elems.linkDetail.style.display = "none";
	
	addEvent(document,"keydown",function(e,obj){
		var keycode = GetKeyCode(e);
		if(ini.keyno[keycode]){
			key[ini.keyno[keycode]] = true;
			preventDefault(e);
			false;
		}
	});
	addEvent(document,"keyup",function(e,obj){
		var keycode = GetKeyCode(e);
		if(ini.keyno[keycode]){
			key[ini.keyno[keycode]] = false;
			preventDefault(e);
			false;
		}
	});
	
	addEvent(elems.helpToggle,"mousedown",function(e,obj){
		elems.helpContent.style.display = (elems.helpContent.style.display == "block" ? "none" : "block");
	});
}
function reloadLink(x,y,link){
	elems.linkDetail.style.left = x + "px";
	elems.linkDetail.style.top = y + "px";
	if(elems.linkDetail.style.display == "none"){
		elems.linkDetail.style.display = "block";
		elems.linkDetail.innerHTML = link.linkDetail || link.href;
	}
}
function start(){
	elems.room.style.width = ini.window.width + "px";
	elems.room.style.height = ini.window.height + "px";
	
	timer = setInterval(function(){
		for(var i=items.length;i>0;i--) items[i-1].move();
	},ini.timerSpeed);
}
function stop(){
	clearInterval(timer);
}
function restart(){
	stop();
	start();
}

function GetKeyCode(e){
	if(document.all) return event.keyCode;
	else if(document.getElementById) return (e.keyCode) ? e.keyCode: e.charCode;
	else if(document.layers) return e.which;
}
function preventDefault(e){
	if(!e || !e.preventDefault) window.event.returnValue = false;
	else e.preventDefault();
}
var addEvent = (function(){
	if(window.addEventListener){
		return function(el,type,fn){
			el.addEventListener(type,function(e){fn(e,this);},false);
		};
	}else if(window.attachEvent){
		return function(el,type,fn){
			var f = function(){
				fn.call(el,window.event);
			};
			el.attachEvent("on"+type,function(){fn(null,event.srcElement);});
		};
	}else{
		return function(el,type,fn){
			el["on"+type] = fn;
		};
	}
})();
function overwriteObject(old,over){
	for(var key in over){
		if(isObject(over[key])){
			if(!isObject(old[key])) old[key] = {};
			overwriteObject(old[key],over[key]);
		}else{
			old[key] = over[key];
		}
	}
}
function isArray(array){
	return array != null && array.constructor === Array;
}
function isObject(obj){
	return typeof obj == "object" && !isArray(obj);
}
document.getCurrentScript = function(){
	return (function (e) {
		if (e.nodeName.toLowerCase() == 'script') return e;
			return arguments.callee(e.lastChild)
	})(document)
};

return {
	key : key,
	ini : ini,
	def : def,
	items : items,
	elems : elems,
	item : item,
	set : set,
	start : start,
	stop : stop,
	restart : restart
};

}();
