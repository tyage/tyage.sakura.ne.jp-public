var 
balls = [],
num = 0,
square = {
	center : {
		x : 0,
		y : 0
	},
	radis : {
		x : 0,
		y : 0
	}
},
body = null,
drag = null,
mouse = {
	x : null,
	y : null
},
moji = false;

function preventDefault(e){
	if(!e || !e.preventDefault) window.event.returnValue = false;
	else e.preventDefault();
}

window.onload = function(){
	body = document.getElementsByTagName("body")[0];
	
	document.getElementById("moji").onchange = function(){
		moji = this.checked;
	};
	
	if (document.defaultView && document.defaultView.getComputedStyle) {
		var s = document.defaultView.getComputedStyle(document.getElementById("square"), '');
	} else {
		var s = document.getElementById("square").currentStyle;
	}
	square.center.x = parseInt(s.left) + parseInt(s.width) / 2 - 4;
	square.center.y = parseInt(s.top) + parseInt(s.height) / 2 - 4;
	square.radis.x = parseInt(s.width) / 2;
	square.radis.y = parseInt(s.height) / 2;
	
	setInterval(function(){
		for(i in balls){
			balls[i].move();
		}
	},1);
};

document.onmousedown = function(e){
	preventDefault(e);
	drag = setInterval(function(){
		balls.push(new ball());
	},10);
};
document.onmouseup = function(){
	clearInterval(drag);
};
document.onmousemove = function(e){
	mouse.x = document.body.scrollLeft + document.all ? window.event.clientX : e.pageX;
	mouse.y = document.body.scrollTop + document.all ? window.event.clientY : e.pageY;
};

ball = function(){
	this.left = mouse.x;
	this.top = mouse.y;
	this.dis = {
		x: this.left - square.center.x,
		y: this.top - square.center.y
	};
	this.dis.sum = Math.abs(this.dis.x) + Math.abs(this.dis.y);
	this.speed = 5;
	
	this.elem = document.createElement("p");
	this.elem.className = 'ball';
	this.elem.style.left = this.left + "px";
	this.elem.style.top = this.top + "px";
	this.elem.style.background = "RGB(" + Math.floor(Math.random()*255) + "," + Math.floor(Math.random()*255) + "," + Math.floor(Math.random()*255) + ")";
	
	body.appendChild(this.elem);
};
ball.prototype.move = function(){
	if(moji){
		this.speed += Math.floor(Math.random()*6 - 2);
		this.speed *= 3 / 4;
	}else{
		this.speed = 5;
	}
	this.left -= this.speed * this.dis.x/this.dis.sum;
	this.top -= this.speed * this.dis.y/this.dis.sum;
	
	this.elem.style.left = this.left + "px";
	this.elem.style.top = this.top + "px";
	
	if( Math.abs(this.left - square.center.x) < square.radis.x && Math.abs(this.top - square.center.y) < square.radis.y) balls.splice(i,1);
};