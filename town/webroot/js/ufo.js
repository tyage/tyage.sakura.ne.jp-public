$start.ufo = function(){
	$ufo.start();

	$('.coin').each(function () {
		$(this).css({
			left: Math.random()*90+'%',
			bottom: Math.random()*10
		});
	});
};

$ufo = {
	start: function () {
		$(document).bind('keydown',{
			combi : 'right',
			disableInInput : true
		},function(e){
			$ufo.moveRight();
			e.preventDefault();
		});

		$(document).bind('keydown',{
			combi : 'down',
			disableInInput : true
		},function (e) {
			$ufo.moveDown();
			e.preventDefault();
		});
	},
	moveRight: function(){
		var left = $('#ufo').position().left;
		var maxLeft = $('#ufoCatch').width() - $('#ufo').width();
		$('#ufo').css('left', (left <= maxLeft ? left+3 : left));
	},
	moveDown: function(){
		$(document).unbind('keydown','down');
		$(document).unbind('keydown','right');
		$('#ufo').animate({
			top: $('#ufoCatch').height() - $('#ufo').height()
		},2000, $ufo.finish);
	},
	finish: function(){
		$.ajax({
			url : '/town/ufos/get/',
			success : function(data){
				if (data.error) {
					var message = data.error;
				} else {
					var gets = [];
					if (data.coin) gets.push('コインを'+data.coin+'枚ゲットだぜ！');
					if (data.item) gets.push('「'+data.item.Item.name+'」ゲットだぜ！');
					var message = gets ? gets.join('<br>') : 'なにも取れませんでした。';
				}
				$.addMessage('UFOキャッチャー', message);
			},
			complete : function(XMLHttpRequest, textStatus){
				$('#ufo').animate({
					top: 0,
					left: 0
				},2000,function(){
					$ufo.start();
				});
			},
			dataType: 'json'
		});
	}
};