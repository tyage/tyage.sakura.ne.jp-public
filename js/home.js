/*@cc_on
eval((function(props) {
  var code = [];
  for (var i = 0,l = props.length;i<l;i++){
    var prop = props[i];
    window['_'+prop]=window[prop];
    code.push(prop+'=_'+prop)
  }
  return 'var '+code.join(',');
})('document self top parent alert setInterval clearInterval setTimeout clearTimeout'.split(' ')));
@*/

$(function(){
	Boku2D.Object.prototype.elastic = 10;
	Boku2D.Object.prototype.viscosity = 3;
	Boku2D.Object.prototype.afterStep = Boku2D.Object.prototype.drawElem;
	Boku2D.Object.prototype.changeImage = function(time) {
		if (this.images) {
			var tmpImage = this.image;
			var margin = 3;
			
			this.setImage('stop');
			
			if (this.speed.x < -margin) {
				this.setImage('left');
				if (this.accel.x > 0) {
					this.setImage('brakeLeft');
				}
			} else if (this.speed.x > margin) {
				this.setImage('right');
				if (this.accel.x < 0) {
					this.setImage('brakeRight');
				}
			}
			
			if (this.speed.y < -margin) {
				this.setImage('up');
				if (this.speed.x < -margin) {
					this.setImage('upLeft');
				} else if (this.speed.x > margin) {
					this.setImage('upRight');
				}
			} else if (this.speed.y > margin) {
				this.setImage('down');
				if (this.speed.x < -margin) {
					this.setImage('downLeft');
				} else if (this.speed.x > margin) {
					this.setImage('downRight');
				}
			}
			
			if (tmpImage !== this.image) {
				if (typeof this.image !== 'string') {
					var image = this.image;
					if (!image.now) {
						image.now = 0;
					}
					this.image = image[image.now++ % image.length];
				}
				$(this.elem).attr('src', this.image);
			}
		}
	};
	Boku2D.Object.prototype.setImage = function(type) {
		if (this.images) {
			var image = this.images[type];
			if (image) {
				this.image = image;
			}
		}
	};
	
	Boku2D.Model.cloud = $.extend(false, Boku2D.Model.fixed, {
		elastic: 0.3,
		createContact: function(contact, manifold) {
			if (manifold.direction.y < 0) {
				var manifolds = contact.manifolds;
				for (var i=0,l=manifolds.length;i<l;i++) {
					manifolds[i].direction.x = 0;
					manifolds[i].direction.y = 0;
				}
			}
		}
	});
	Boku2D.Model.enemy = $.extend(false, Boku2D.Model.block, {
		randForce: new Boku2D.Vec(100, 0),
		beforeStep: function(time) {
			if (this.randForce) {
				this.applyForce(this.randForce.multiply(Math.random()-0.5));
			}
			if (this.randJump && Math.random() < 0.1 && this.canJump()) {
				this.applyForce(this.randJump);
			}
		},
		afterStep: function(time) {
			this.changeImage(time);
			this.drawElem(time);
		}
	});
	Boku2D.Model.controll.afterStep = function(time) {
		this.changeImage(time);
		this.drawElem(time);
	};
	
	var images = {
		masao: {
			stop : '/image/masao/stop.gif',
			left : ['/image/masao/left.gif', '/image/masao/left.gif', '/image/masao/left.gif',
				'/image/masao/left2.gif', '/image/masao/left2.gif', '/image/masao/left2.gif'],
			right : ['/image/masao/right.gif', '/image/masao/right.gif', '/image/masao/right.gif',
				'/image/masao/right2.gif', '/image/masao/right2.gif', '/image/masao/right2.gif'],
			upRight : '/image/masao/uright.gif',
			upLeft : '/image/masao/uleft.gif',
			downRight : '/image/masao/dright.gif',
			downLeft : '/image/masao/dleft.gif',
			brakeRight : '/image/masao/bright.gif',
			brakeLeft : '/image/masao/bleft.gif'
		},
		kame: {
			stop : '/image/masao/kame/left.gif',
			left : ['/image/masao/kame/left.gif', '/image/masao/kame/left2.gif'],
			right : ['/image/masao/kame/right.gif', '/image/masao/kame/right2.gif'],
			treadedLeft : '/image/masao/kame/tleft.gif',
			treadedRight : '/image/masao/kame/tright.gif'
		},
		mariri: {
			stop : '/image/masao/mariri/stop.gif',
			upLeft : '/image/masao/mariri/jleft.gif',
			upRight : '/image/masao/mariri/jright.gif',
			downLeft : '/image/masao/mariri/dleft.gif',
			downRight : '/image/masao/mariri/dright.gif',
			treadedLeft : '/image/masao/mariri/tleft.gif',
			treadedRight : '/image/masao/mariri/tright.gif'
		},
		hino: {
			stop : '/image/masao/hino/left.gif',
			left : ['/image/masao/hino/left.gif', '/image/masao/hino/left2.gif'],
			right : ['/image/masao/hino/right.gif', '/image/masao/hino/right2.gif']
		},
		poppi: {
			stop : '/image/masao/poppi/left.gif',
			left : ['/image/masao/poppi/left.gif', '/image/masao/poppi/left2.gif'],
			right : ['/image/masao/poppi/right.gif', '/image/masao/poppi/right2.gif'],
			treadedLeft : '/image/masao/poppi/tleft.gif',
			treadedRight : '/image/masao/poppi/tright.gif'
		}
	};
	
	setTimeout(function() {
		var world = new Boku2D.World();
		world.initDOM($('#world').get(0));

		$('.object.float').each(function () {
			var object = new Boku2D.Object(Boku2D.Model.floating);
			object.initDOM($(this).get(0));
			world.createObject(object);
		});
		$('.object.fixed').each(function () {
			var object = new Boku2D.Object(Boku2D.Model.fixed);
			object.initDOM($(this).get(0));
			world.createObject(object);
		});
		$('.object.cloud').each(function () {
			var object = new Boku2D.Object(Boku2D.Model.cloud);
			object.initDOM($(this).get(0));
			world.createObject(object);
		});
		$('.object.controll').each(function () {
			var object = new Boku2D.Object(Boku2D.Model.controll);
			object.initDOM($(this).get(0));
			object.images = images.masao;
			world.createObject(object);
		});
		
		$('#addKame').click(function() {
			var $elem = $('<img />')
			.addClass('object block')
			.css({
				width: 32,
				height: 32,
				top: Math.random()*(world.size.y-64)+32,
				left: Math.random()*(world.size.x-64)+32
			}).appendTo('#world');
			
			var object = new Boku2D.Object(Boku2D.Model.enemy);
			object.initDOM($elem.get(0));
			object.images = images.kame;
			world.createObject(object);
		});
		$('#addMariri').click(function() {
			var $elem = $('<img />')
			.addClass('object block')
			.css({
				width: 32,
				height: 32,
				top: Math.random()*(world.size.y-64)+32,
				left: Math.random()*(world.size.x-64)+32
			}).appendTo('#world');
			
			var object = new Boku2D.Object(Boku2D.Model.enemy);
			object.initDOM($elem.get(0));
			object.images = images.mariri;
			object.randJump = new Boku2D.Vec(0, -300);
			world.createObject(object);
		});
		$('#addHino').click(function() {
			var $elem = $('<img />')
			.addClass('object block')
			.css({
				width: 32,
				height: 32,
				top: Math.random()*(world.size.y-64)+32,
				left: Math.random()*(world.size.x-64)+32
			}).appendTo('#world');
			
			var object = new Boku2D.Object(Boku2D.Model.enemy);
			object.initDOM($elem.get(0));
			object.images = images.hino;
			world.createObject(object);
		});
		$('#addPoppi').click(function() {
			var $elem = $('<img />')
			.addClass('object float')
			.css({
				width: 32,
				height: 32,
				top: Math.random()*(world.size.y-64)+32,
				left: Math.random()*(world.size.x-64)+32
			}).appendTo('#world');
			
			var object = new Boku2D.Object(Boku2D.Model.enemy);
			object.initDOM($elem.get(0));
			object.images = images.poppi;
			object.gravity = new Boku2D.Vec();
			world.createObject(object);
		});
		
		var timer = setInterval(function () {
			world.step(0.1);
		}, 13);
	}, 1000);
});
