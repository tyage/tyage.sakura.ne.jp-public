$ufo = {
	left : 0,
	catcher : null,
	down : false
};

$ufo.set = function(){
	$ufo.catcher = $("#ufoCatcher");
	$ufo.window = $("#ufoCatch");
	
	$(document).bind("keydown",{
		combi : "right",
		disableInInput : true
	},function(e){
		$ufo.moveRight();
		e.preventDefault();
	});
	$(document).bind("keydown",{
		combi : "down",
		disableInInput : true
	},function(e){
		$ufo.moveDown();
		e.preventDefault();
	});
};

$ufo.moveRight = function(){
	if($ufo.down || $ufo.left > $ufo.window.width() - 32) return;
	$ufo.left += 3;
	$ufo.catcher.css("left",$ufo.left);
};
$ufo.moveDown = function(){
	if($ufo.down) return;
	$ufo.down = true;
	$ufo.catcher.animate({top:$ufo.window.height() - 50},2000,$ufo.getItem);
};
$ufo.getItem = function(){
	$.ajax({
		type : "POST",
		url : "./?mode=Game&command=ufoCatch",
		success : function(data){
			$message.add(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$message.add("機械が故障しました。やり直してください。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			$ufo.catcher.animate({top:0,left:0},2000,function(){
				$ufo.left = 0;
				$ufo.down = false;
			});
			
			this;
		}
	});
};