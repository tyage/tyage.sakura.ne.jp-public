// 実行
$start = {};
$startup = function(){
	for(key in $start){
		$start[key]();
		delete $start[key];
	}
};
$($startup);

$.extend($start, {
	// 初期化
	setup: function(){
		$.ajaxSetup({
			traditional: true
		});
		
		$(document).setup();
	},
	//ナビ
	navi: function(){
		$('<div />',{
			id: 'navi',
			css: {
				left: -999,
				top: -999
			}
		}).appendTo('body');
	},
	form: function(){
		// IEにてサブミットが効かない問題あり
		//$('form').live('submit',function(){
			//$(':image,:submit').attr('disabled',true);
		//});
		$('form.ajax').live('submit',function(e){
			$stopEvent(e);
			$(this).submitAjax({
				reset : $(this).hasClass('reset')
			});
		});
	}
});

$.fn.extend({
	// 初期化
	setup: function(){
		return $(this).each(function(){
			$('form > div > label',this).justify();
			$('.navi', this).navi();
		});
	},
	// フォームのラベル幅をそろえる
	justify: function(){
		var max = 0;
		$(this).each(function(){
			var width = $(this).width();
			if(width > max) max = width;
		}).each(function(){
			$(this).css('padding-right',max - $(this).width());
		}).nextAll().css('display','inline');
		
		return this;
	},
	navi: function(){
		return this.each(function(){
			var data = {};
			data.title = $(this).attr('title');
			data.src = $(this).attr('src');
			data.name= $(this).attr('name');
			
			// ブラウザ内蔵ツールチップが表示されると邪魔なので、title属性を消す
			$(this).removeAttr('title');
			
			$(this).parent().bind('mouseover',data,function(e){
				$('#navi').html(
					"<h3>" +
						"<img src='"+e.data.src+"'> "+e.data.name+"" +
					"</h3>" +
					"<p>"+e.data.title+"</p>"
				).show();
			});
			$(this).parent().bind('mousemove',function(e){
				$('#navi').css({
					left : e.pageX + 20,
					top : e.pageY + 25
				});
			});
			$(this).parent().bind('mouseout',function(){
				$('#navi').hide();
			});
		});
	},
	//Ajax送信
	submitAjax: function(options){
		var form = $(this);
		var ajax = {
			type : form.attr('method'),
			url : form.attr('action'),
			data : form.serializeArray(),
			success : function(data){
				if(options.reset) {
					form.reset();
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown){
				this;
			},
			complete : function(XMLHttpRequest, textStatus){
				form.endSubmit();
			}
		};
		
		$.ajax($.extend(ajax,options || {}));
		
		return this;
	},
	// submitボタンを押せるようにする
	endSubmit: function(){
		$(':image,:submit',this).attr('disabled',false);
	}
});

$stopEvent = function(e){
	e.preventDefault();
	e.stopPropagation();
};


$.extend({
	addMessage: function(title, body){
		$('<div class="message">'+
			'<header>'+
				'<h3 class="title">'+
					'<span>'+title+'</span>'+
					'<a class="close">×</a>'+
				'</h3>'+
			'</header>'+
			'<div class="body">'+body+'</div>'+
		'</div>').appendTo('#messages')
		.hide().fadeIn('slow')
		.find('header .close').bind('click', function () {
			$(this).parentsUntil('#messages').fadeOut('slow', function () {
				$(this).remove();
			});
		});
	}
});