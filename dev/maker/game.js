jQuery(function ($) {
	var select = null;
	var oldIni = $.extend(true, {}, AL.ini);
	var oldDef = $.extend(true, {}, AL.def);
	var defaultCharas = [
		{
			width: 64,
			img: {
				stop: "/image/masao/item/cloud.gif"
			},
			isBlockable:["top","right","left"],
			isPushable: false
		},
		{
			bounce : {
				top : 1.5
			},
			img : {
				stop : "/image/masao/item/bound.gif"
			},
			isPushable: false
		},
		{
			type: "rand",
			baseSpeed : {
				x : 5,
				y : 0,
				gy: 3
			},
			img : {
				stop : "/image/masao/kame/left.gif",
				left : "/image/masao/kame/left.gif",
				left2 : "/image/masao/kame/left2.gif",
				right : "/image/masao/kame/right.gif",
				right2 : "/image/masao/kame/right2.gif",
				tleft : "/image/masao/kame/tleft.gif",
				tright : "/image/masao/kame/tright.gif"
			},
			isTreadable: true
		},
		{
			type: "rand",
			baseSpeed : {
				x : 5,
				jy : 30,
				gy: 3
			},
			jumpRate : 5,
			img : {
				stop : "/image/masao/mariri/stop.gif",
				jleft : "/image/masao/mariri/jleft.gif",
				jright : "/image/masao/mariri/jright.gif",
				dleft : "/image/masao/mariri/dleft.gif",
				dright : "/image/masao/mariri/dright.gif",
				tleft : "/image/masao/mariri/tleft.gif",
				tright : "/image/masao/mariri/tright.gif"
			},
			isTreadable: true
		},
		{
			type: "rand",
			bounce : {
				top : 0
			},
			baseSpeed : {
				x : 5,
				y : 0,
				gy: 3
			},
			img : {
				stop : "/image/masao/hino/left.gif",
				left : "/image/masao/hino/left.gif",
				left2 : "/image/masao/hino/left2.gif",
				right : "/image/masao/hino/right.gif",
				right2 : "/image/masao/hino/right2.gif"
			},
			isTreadable: false
		},
		{
			type: "rand",
			baseSpeed : {
				x : 5,
				y : 0,
				gy : 0
			},
			img : {
				stop : "/image/masao/poppi/left.gif",
				left : "/image/masao/poppi/left.gif",
				left2 : "/image/masao/poppi/left2.gif",
				right : "/image/masao/poppi/right.gif",
				right2 : "/image/masao/poppi/right2.gif",
				tleft : "/image/masao/poppi/tleft.gif",
				tright : "/image/masao/poppi/tright.gif"
			},
			isTreadable: true
		},
		{
			type: "control",
			baseSpeed: {
				x: 1,
				jy: 30,
				gy: 3
			},
			img: {
				stop: "/image/masao/stop.gif",
				left: "/image/masao/left.gif",
				left2: "/image/masao/left2.gif",
				right: "/image/masao/right.gif",
				right2: "/image/masao/right2.gif",
				uright: "/image/masao/uright.gif",
				uleft: "/image/masao/uleft.gif",
				dright: "/image/masao/dright.gif",
				dleft: "/image/masao/dleft.gif",
				bright: "/image/masao/bright.gif",
				bleft: "/image/masao/bleft.gif"
			}
		}
	];
	for(i=0;i<10;i++){
		defaultCharas[defaultCharas.length] = {
			img : {
				stop : "/image/masao/item/block" + (i+1) + ".gif"
			},
			isPushable: false
		};
	}

	// 選択キャラの追加
	AL.set();
	for (var i=0,l=defaultCharas.length;i<l;i++) {
		var chara = defaultCharas[i];
		var item = new AL.item(chara);
		item.kill();
		$('<li></li>').append(
			$('<div></div>', {
				className: 'charaWrapper'
			})
			.append(
				$(item.elem).clone().css('position', '')
			)
			.data('option', chara)
		)
		.appendTo('#charaList');
	}
	
	$('#charaList').resizable();
	$('#charaList li').live('click', function () {
		var selected = $(this).hasClass('selected');
		$('#charaList li.selected').removeClass('selected');
		if (!selected) {
			$(this).addClass('selected');
			select = $('.charaWrapper', this);
		} else {
			select = null;
		}
	});
	
	$('#gameWorld').resizable().click(function (e) {
		if (!select) {
			return false;
		}
		
		var position = $(this).position();
		var x = e.pageX - position.left - $(select).width()/2;
		var y = e.pageY - position.top - $(select).height()/2;
		
		$(select)
			.clone(true)
			.appendTo(this)
			.css({
				left: x,
				top: y
			})
			.reloadPosition()
			.draggable({
				containment: 'parent',
				stop: function () {
					$(this).reloadPosition();
				}
			})
			.createOption()
			.mouseover(function () {
				$(this).showOption().find('.chara').toggleClass('hover');
			})
			.mouseout(function () {
				$(this).hideOption().find('.chara').toggleClass('hover');
			});
	});
	
	$('#gameTabs').tabs();
	
	$('#executeHandler').click(function () {
		$('#ALroom').width($('#gameWorld').width());
		$('#ALroom').height($('#gameWorld').height());
		
		$.each(AL.items, function () {
			if (!this.isDeleted) {
				this.kill();
			}
		});
		AL.stop();
		
		var newIni = (new Function($('#worldConfig').val()))();
		var newDef = (new Function($('#charaDefault').val()))();
		$.extend(AL.ini, oldIni, newIni);
		$.extend(AL.def, oldDef, newDef);
		
		$('#gameWorld .charaWrapper').each(function () {
			new AL.item($(this).data('option'));
		});
		
		AL.start();
	});
	
	$('#box2dHandler').click(function () {
		if ($(this).data('timer')) clearInterval($(this).data('timer'));
		
		var canvas = $('#gameCanvas');
		var ctx = canvas.get(0).getContext('2d');
		var canvasWidth = canvas.width();
		var canvasHeight = canvas.height();
		var world = createWorld();
		var timer;
		var controller = [];
		var antiGravity = [];
		var key = {
			left: 0,
			top: 0,
			down: 0,
			right: 0
		};
		
		$('#gameWorld .charaWrapper').each(function () {
			var data = $(this).data('option');
			data.isPushable = data.isPushable === undefined ? true : data.isPushable;
			var body = createBox(world, data.x, data.y, (data.width || 32)/2,(data.height || 32)/2, !data.isPushable, data.img);
			body.m_userData.body = body;
			body.m_userData.onGround = false;
			body.m_userData.contact = function (contactBody, Nrm) {
				if(Nrm.y > 0.707){//４５度より平坦な坂の上でないとジャンプできない
					this.onGround = true;
				}
			};
			
			if (data.baseSpeed && data.baseSpeed.gy == 0) {
				antiGravity.push(body);
			}
			if (data.type === 'control') {
				body.m_angularDamping = 0;
				body.AllowSleeping(false);
				controller.push(body);
			}
			if (data.type === 'rand') {
				body.m_userData.contact = (function (body) {
					return function (contactBody, Nrm) {
						if(Nrm.y > 0.707){
							this.onGround = true;
						}
						if(Nrm.y > 0){
							var liner = body.GetLinearVelocity();
							body.SetLinearVelocity(
								new b2Vec2(
									liner.x + Math.random()*100 - 50, 
									liner.y + 0
								)
							);
						}
					}
				})(body);
				body.AllowSleeping(false);
			}
		});
		
		$(document).keydown(function (e) {
			switch (e.keyCode) {
				case 37: 
					key.left = 1;
					break;
				case 38:
					key.top = 1;
					break;
				case 39: 
					key.right = 1;
					break;
				case 40: 
					key.down = 1;
					break;
			}
		});
		$(document).keyup(function (e) {
			switch (e.keyCode) {
				case 37: 
					key.left = 0;
					break;
				case 38:
					key.top = 0;
					break;
				case 39: 
					key.right = 0;
					break;
				case 40: 
					key.down = 0;
					break;
			}
		});
		
		timer = setInterval(function () {
			var timeStep = 1.0/60;
			var iteration = 1;
			var userData;
			
			for (var i=0,l=antiGravity.length;i<l;i++) {
				var body = antiGravity[i];
				body.m_force.Add(b2Math.MulFV(body.GetMass(), new b2Vec2(0, -1500)));
				body.GetUserData().contact(body, {y:1});
			}
			
			for (var contact = world.GetContactList(); contact != null; contact = contact.GetNext()) {
				if (contact.GetManifoldCount() <= 0 ) continue;
				
				var Nrm = contact.GetManifolds()[0].normal;
				var body1 = contact.GetShape1().GetBody();
				var body2 = contact.GetShape2().GetBody();
				
				body1.GetUserData().contact(body2, Nrm);
				body2.GetUserData().contact(body1, {x:-Nrm.x,y:-Nrm.y});
			}
			
			world.Step(timeStep, iteration);
			
			var c,liner,x,y;
			ctx.clearRect(0, 0, canvasWidth, canvasHeight);
			drawWorld(world, ctx);
			for (var i=0,l=controller.length;i<l;++i) {
				c = controller[i];
				userData = c.GetUserData();
				liner = c.GetLinearVelocity();
				x = liner.x + key.right * 10 - key.left * 10;
				y = liner.y + key.down * 10 - (userData.onGround ? key.top*300 : 0);
				if (Math.abs(x) > 500) x = (Math.abs(x)/x) * 500;
				if (Math.abs(y) > 500) y = (Math.abs(y)/y) * 500;
				
				c.SetLinearVelocity(
					new b2Vec2(x, y)
				);
				c.m_userData.onGround = false;
			}
		}, 30);
		$(this).data('timer', timer);
	})
	
	$.fn.extend({
		reloadPosition: function () {
			return $(this).each(function () {
				var option = $(this).data('option');
				var position = $(this).position();
				option.x = position.left;
				option.y = position.top;
				$(this).data('option', option);
			});
		},
		createOption: function () {
			return $(this).each(function () {
				$(this)
					.filter(':not(:has(.option))')
					.append(
						$('#charaOption')
							.clone(true)
							.attr('id', '')
								.find('.delete')
								.click(function (e) {
									e.stopPropagation();
									e.preventDefault();
									$(this).closest('.charaWrapper').remove();
								})
							.end()
					)
				.end()
				.hideOption();
			});
		},
		showOption: function () {
			return $(this).each(function () {
				$(this).find('.option').show();
			});
		},
		hideOption: function () {
			return $(this).each(function () {
				$(this).find('.option').hide();
			});
		}
	});
});