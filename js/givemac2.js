var LoadImages = function(images) {
	for (var i in images) {
		if ($.isArray(images[i])) {
			LoadImages(images[i]);
		} else {
			var image = new Image();
			image.src = images[i];
		}
	}
};

// キー操作
Boku2D.keyPress = {};
jQuery(document).keydown(function (e) {
	Boku2D.keyPress[e.keyCode] = true;
});
jQuery(document).keyup(function (e) {
	Boku2D.keyPress[e.keyCode] = false;
});
Boku2D.Object.prototype.canJump = function() {
	var contacts = this.contacts,
		onGround = false;
	for (var i=0,l=contacts.length;i<l;i++) {
		var contact = contacts[i],
			m0 = contact.manifolds[0],
			m1 = contact.manifolds[1];
		if ((m0.object === this && m0.direction.y < 0) ||
			(m1.object === this && m1.direction.y < 0)) {
			onGround = true;
			break;
		}
	}
	return onGround;
};

// 画像調整
Boku2D.Object.prototype.changeImage = function(time) {
	if (this.images) {
		var margin = 5;
		this.setImage('stop');
		
		if (this.speed.x < -margin) {
			this.setImage('left');
			if (this.accel.x > 0) {
				this.setImage('brakeLeft');
			}
		}
		if (this.speed.x > margin) {
			this.setImage('right');
			if (this.accel.x < 0) {
				this.setImage('brakeRight');
			}
		}
		
		if (this.speed.y < -margin) {
			this.setImage('up');
			if (this.speed.x < -margin) {
				this.setImage('upLeft');
			}
			if (this.speed.x > margin) {
				this.setImage('upRight');
			}
		}
		if (this.speed.y > margin) {
			this.setImage('down');
			if (this.speed.x < -margin) {
				this.setImage('downLeft');
			}
			if (this.speed.x > margin) {
				this.setImage('downRight');
			}
		}
		
		$(this.elem).attr('src', this.image)
	}
	
	this.drawElem(time);
};
Boku2D.Object.prototype.setImage = function(type) {
	if (this.images) {
		var image = this.images[type];
		if ($.isArray(image) && image.length > 0) {
			if (!image.now) {
				image.now = 0;
			}
			this.image = image[image.now++ % image.length]
		} else if (image) {
			this.image = image;
		}
	}
};

Boku2D.Object.prototype.viscosity = 100;
Boku2D.Object.prototype.elastic = 1000;
Boku2D.Object.prototype.afterStep = Boku2D.Object.prototype.drawElem;

Boku2D.Model = {};
// 壁
Boku2D.Model.ground = {
	fixed: true,
	gravity: new Boku2D.Vec()
};
// 固定物体
Boku2D.Model.fixed = {
	fixed: true,
	gravity: new Boku2D.Vec()
};
// 浮遊物体
Boku2D.Model.floating = {
	gravity: new Boku2D.Vec(),
	elastic: 10,
	viscosity: 1
};
// 移動用土管
Boku2D.Model.navi = {
	fixed: true,
	gravity: new Boku2D.Vec(),
	updateContact: function(contact, manifold) {
		if (manifold.opponent.type === 'masao' && 
			manifold.direction.y > 0 && 
			Boku2D.keyPress[40]) {
			this.world.navi = this;
		}
	}
};
// 雲
Boku2D.Model.cloud = {
	elastic: 2,
	viscosity: 1,
	fixed: true,
	gravity: new Boku2D.Vec(),
	createContact: function(contact, manifold) {
		if (manifold.direction.y < 0) {
			var manifolds = contact.manifolds;
			for (var i=0,l=manifolds.length;i<l;i++) {
				manifolds[i].direction.x = 0;
				manifolds[i].direction.y = 0;
			}
		}
	}
};
// 敵
Boku2D.Model.enemy = {
	elastic: 2,
	viscosity: 1,
	life: 10,
	init: function(time) {
		if (this.fixedSpeed) {
			this.speed = this.fixedSpeed.copy();
		}
		LoadImages(this.images);
	},
	tread: function(contact, manifold) {
		if (manifold.opponent.type === 'masao' && 
			manifold.direction.y > 0) {
			this.treaded = true;
			this.images = {
				stop: this.images.treadedLeft
			};
		}
	},
	onTreaded: function() {
		if (this.treaded && --this.life < 0) {
			this.world.destroyObject(this);
			$(this.elem).remove();
		}
	},
	afterStep: function(time) {
		this.changeImage(time);
		this.drawElem();
	}
};
// 亀
Boku2D.Model.kame = $.extend(false, Boku2D.Model.enemy, {
	fixedSpeed: new Boku2D.Vec(-10, 0),
	images: {
		stop : '/image/masao/kame/left.gif',
		left : ['/image/masao/kame/left.gif', '/image/masao/kame/left2.gif'],
		right : ['/image/masao/kame/right.gif', '/image/masao/kame/right2.gif'],
		treadedLeft : '/image/masao/kame/tleft.gif',
		treadedRight : '/image/masao/kame/tright.gif'
	},
	beforeStep: function(time) {
		if (this.fixedSpeed) {
			this.applyForce({
				x: this.force.x * -1,
				y: 0
			});
		}
		
		this.onTreaded();
	},
	createContact: function(contact, manifold) {
		if (this.fixedSpeed && manifold.direction.x * this.speed.x < 0) {
			this.speed.x *= -1;
		}
		this.tread(contact, manifold);
	}
});
// マリリ
Boku2D.Model.mariri = $.extend(false, Boku2D.Model.enemy, {
	randForce: new Boku2D.Vec(100, 0),
	randJump: new Boku2D.Vec(0, -300),
	jumpRate: 0.1,
	images: {
		stop : '/image/masao/mariri/stop.gif',
		upLeft : '/image/masao/mariri/jleft.gif',
		upRight : '/image/masao/mariri/jright.gif',
		downLeft : '/image/masao/mariri/dleft.gif',
		downRight : '/image/masao/mariri/dright.gif',
		treadedLeft : '/image/masao/mariri/tleft.gif',
		treadedRight : '/image/masao/mariri/tright.gif'
	},
	beforeStep: function(time) {
		if (this.randForce) {
			this.applyForce(this.randForce.multiply(Math.random()-0.5));
		}
		if (this.randJump && Math.random() < this.jumpRate && this.canJump()) {
			this.applyForce(this.randJump);
		}
		
		this.onTreaded();
	},
	createContact: function(contact, manifold) {
		this.tread(contact, manifold);
	}
});
// ポッピー
Boku2D.Model.poppi = $.extend(false, Boku2D.Model.kame, {
	gravity: new Boku2D.Vec(0, 0),
	images: {
		stop : '/image/masao/poppi/left.gif',
		left : ['/image/masao/poppi/left.gif', '/image/masao/poppi/left2.gif'],
		right : ['/image/masao/poppi/right.gif', '/image/masao/poppi/right2.gif'],
		treadedLeft : '/image/masao/poppi/tleft.gif',
		treadedRight : '/image/masao/poppi/tright.gif'
	}
});
// 正男
Boku2D.Model.masao = {
	viscosity: 1,
	elastic: 10,
	type: 'masao',
	controll: {
		37: new Boku2D.Vec(-50, 0),
		39: new Boku2D.Vec(50, 0)
	},
	jump: new Boku2D.Vec(0, -800),
	gravity: new Boku2D.Vec(0, 30),
	maxSpeed: new Boku2D.Vec(30, 1000),
	images: {
		stop : '/image/masao/stop.gif',
		left : ['/image/masao/left.gif', '/image/masao/left.gif', '/image/masao/left.gif',
			'/image/masao/left2.gif', '/image/masao/left2.gif', '/image/masao/left2.gif'],
		right : ['/image/masao/right.gif', '/image/masao/right.gif', '/image/masao/right.gif',
			'/image/masao/right2.gif', '/image/masao/right2.gif', '/image/masao/right2.gif'],
		upRight : '/image/masao/uright.gif',
		upLeft : '/image/masao/uleft.gif',
		downRight : '/image/masao/dright.gif',
		downLeft : '/image/masao/dleft.gif'
		// brakeRight : '/image/masao/bright.gif',
		// brakeLeft : '/image/masao/bleft.gif'
	},
	init: function(time) {
		LoadImages(this.images);
	},
	beforeStep: function(time) {
		if (this.jump && Boku2D.keyPress[38] && this.canJump()) {
			// ジャンプ時の高さを一定に
			this.applyForce({
				x: 0,
				y: this.force.y * -1
			});
			this.applyForce(this.jump);
		}
		
		if (-this.maxSpeed.x < this.speed.x && this.speed.x < this.maxSpeed.x) {
			for (var key in this.controll) {
				if (Boku2D.keyPress[key]) {
					var force = this.controll[key];
					this.applyForce(force);
				}
			}
		}
		
		// 横向きに空気抵抗
		this.applyForce({
			x: this.speed.x * -1,
			y: 0
		});
	},
	afterStep: function(time) {
		this.changeImage(time);
		this.drawElem();
	}
};
// タワー
Boku2D.Model.tower = {
	elastic: 1,
	viscosity: 2,
	friction: 10,
	fixed: true,
	gravity: new Boku2D.Vec()
};

$(function() {
	$('#slides').slide();
});
$.fn.Boku2D = function() {
	return this.each(function() {
		var world = $(this).data('world');
		if (!world) {
			var world = new Boku2D.World();
			world.initDOM($(this).get(0));
			
			$('.object', this).each(function() {
				var model = Boku2D.Model[$(this).data('model')];
				var object = new Boku2D.Object(model);
				object.initDOM($(this).get(0));
				world.createObject(object);
			});
			$(this).data('world', world);
		}
		
		var timer = setInterval(function () {
			world.step(0.1);
			if (world.navi) {
				$(world.navi.elem).click();
			}
		}, 13);
		$(this).data('timer', timer);
	});
};
$.fn.stopBoku2D = function() {
	return this.each(function() {
		var timer = $(this).data('timer');
		if (timer) {
			clearInterval(timer);
			
			var world = $(this).data('world');
			world.navi = null;
		}
	});
};
$.fn.startSlide = function() {
	return this.each(function() {
		$(this).show();
		var that = this;
		setTimeout(function() {
			$(that).find('.world').Boku2D();
		}, 300);
	});
};
$.fn.endSlide = function() {
	return this.each(function() {
		$(this).hide()
			.find('.world').stopBoku2D();
	});
};
$.fn.slide = function() {
	return this.each(function() {
		$('.slide', this).endSlide();
		$('.slide:first', this).startSlide();
		
		$('.next', this).bind('click', function() {
			$(this).closest('.slide').endSlide()
				.next('.slide').startSlide();
		});
		$('.prev', this).bind('click', function() {
			$(this).closest('.slide').endSlide()
				.prev('.slide').startSlide();
		});
	});
};