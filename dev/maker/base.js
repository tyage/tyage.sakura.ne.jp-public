function createWorld() {
	var worldAABB = new b2AABB();
	worldAABB.minVertex.Set(-1000, -1000);
	worldAABB.maxVertex.Set(1000, 1000);
	var gravity = new b2Vec2(0, 1500);
	var doSleep = true;
	var world = new b2World(worldAABB, gravity, doSleep);
	createGround(world);
	createBox(world, 0, 0, 500, 10);
	createBox(world, 0, 125, 10, 250);
	createBox(world, 500, 125, 10, 250);
	return world;
}

function initShape(shape) {
	shape.userData = {
		img: null,
		imgList: [],
		draw: function (shape, context) {
			var tV = getVector(shape, 0);
			context.strokeStyle = '#ffffff';
			context.beginPath();
			context.moveTo(tV.x, tV.y);
			for (var i = 0; i < shape.m_vertexCount; i++) {
				var v = getVector(shape, i);
				context.lineTo(v.x, v.y);
			}
			context.lineTo(tV.x, tV.y);
			context.stroke();
		}
	};
	return shape;
}
function initBody(body) {
	body.userData = {
		contact: function() {}
	};
	return body;
}

function createGround(world) {
	var groundSd = new b2BoxDef();
	groundSd = initShape(groundSd);
	groundSd.extents.Set(1000, 50);
	groundSd.restitution = 0.2;
	
	var groundBd = new b2BodyDef();
	groundBd = initBody(groundBd);
	groundBd.AddShape(groundSd);
	groundBd.position.Set(-500, 340);
	return world.CreateBody(groundBd)
}

function createBall(world, x, y) {
	var ballSd = new b2CircleDef();
	ballSd = initShape(ballSd);
	ballSd.density = 1.0;
	ballSd.radius = 20;
	ballSd.restitution = 1.0;
	ballSd.friction = 0;
	
	var ballBd = new b2BodyDef();
	ballBd = initBody(ballBd);
	ballBd.AddShape(ballSd);
	ballBd.position.Set(x,y);
	return world.CreateBody(ballBd);
}

function createBox(world, x, y, width, height, fixed, imgList) {
	if (typeof(fixed) == 'undefined') fixed = true;
	
	var boxSd = new b2BoxDef();
	boxSd = initShape(boxSd);
	boxSd.userData.imgList = imgList;
	if (imgList) {
		var img = new Image();
		img.src = imgList.stop;
		boxSd.userData.img = img;
	}
	if (img) {
		boxSd.userData.draw = function (shape, context) {
			var extents = shape.m_localOBB.extents;
			var v2 = getVector(shape, 2);
			var v3 = getVector(shape, 3);
			var angle = Math.atan2(
				v3.y - v2.y, 
				v3.x - v2.x
			);
			var liner = shape.GetBody().GetLinearVelocity();
			var imgList = this.imgList;
			
			var src = null;
			var x = Math.round(liner.x);
			var y = Math.round(liner.y);
			if (shape.GetBody().GetUserData().onGround) {
				this.step = this.step ? this.step+1 : 1;
				if(x > 1) src = (this.step%2 ? imgList.right2 : imgList.right);
				else if(x < -1) src = (this.step%2 ? imgList.left2 : imgList.left);
				else src = imgList.stop;
			} else if (y < -1) {
				if(x > 1) src = imgList.uright;
				else if(x < -1) src = imgList.uleft;
				else src = imgList.up;
			} else if (y > 1) {
				if(x > 1) src = imgList.dright;
				else if(x < -1) src = imgList.dleft;
				else src = imgList.down;
			} else {
				src = imgList.stop;
			}
			if (src) this.img.src = src;
			
			context.setTransform(Math.cos(angle), Math.sin(angle), -Math.sin(angle), Math.cos(angle), v2.x, v2.y);
			context.drawImage(this.img, 0, 0, extents.x*2, extents.y*2);
			context.setTransform( 1, 0, 0, 1, 0, 0 );
		}
	}
	if (!fixed) boxSd.density = 1.0;
	boxSd.extents.Set(width, height);
	
	var boxBd = new b2BodyDef();
	boxBd = initBody(boxBd);
	boxBd.AddShape(boxSd);
	boxBd.position.Set(x,y);
	return world.CreateBody(boxBd)
}

function getVector(shape, no) {
	return b2Math.AddVV(shape.m_position, b2Math.b2MulMV(shape.m_R, shape.m_vertices[no]));
}