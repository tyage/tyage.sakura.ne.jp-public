$chara_data = {
	now : ""
};


function set(){
	$.ajaxSetup({
		timeout : 3*1000
	});
	
	setHeader();
	
	$(".justify").labelJustify();
	
	$("form").submit(function(){
		$(":image,:submit").attr("disabled",true);
	});
	
	$("table.list").tableList();
	
	$("#chara_data > div > a").live("click",function(){
		var $parent = $(this).parent();
		$(" > div.datum",$parent).chara_get($parent.attr("title"));
		
		return false;
	});
	$("#chara_data > div").live("click",function(){
		$(this).parent().find(" > div[title='"+$chara_data.now+"']").css("z-index","10");
		$chara_data.now = $(this).attr("title");
		$(this).parent().find(" > div[title='"+$chara_data.now+"']").css("z-index","11");
	});
	$("#chara_data > div > span.close").live("click",function(){
		$(this).parent().hide();
	});
	
	$("#message_box > div").live("click",function(){
		$(this).fadeOut("slow",function(){$(this).remove();});
	});
	
	$("form.ajax").live("submit",function(e){
		$(this).formAjax(e,$(this).hasClass("reset"));
	});	
}

function setHeader(){
	$("#content").css("margin-top",$("#header").outerHeight());
}

//----- サブミットを停止 -----//
function return_submit(e){
	$(":image,:submit").attr("disabled",false);
	if(e){
		e.preventDefault();
		e.stopPropagation();
	}
}

$.fn.extend({
	reset : function(){
		return this.each(function(){
			$(this).is('form') && this.reset();
		});
	}
});

$.fn.tableList = function(){
	$("tbody > tr:even",this).addClass("even");
	$("tbody > tr:odd",this).addClass("odd");
};

//----- キャラ情報画面表示 -----//
$.fn.chara_view = function(e){
	var $name = $(this).attr("title");
	
	$("#chara_data:not(:has(div[title='"+$name+"']))").prepend("<div title='" + $name + "' class='info'><span class='close'>×</span><span>" + $name + "さんの情報</span><br><br><a href='#'>最新情報を得る</a><br><br><div class='datum'></div></div>").find(" > div[title='"+$name+"']");
	
	$("#chara_data > div[title='"+$name+"']").css({top:e.clientY + 10,left:e.clientX + 10}).show();
	
	return this;
};

//----- キャラ情報画面取得 -----//
$.fn.chara_get = function(name){
	tmp = $(this);
	tmp.html("<span><img src='./img/loading.gif'>　取得中です・・・</span>");
	$.ajax({
		type : "POST",
		url : "./?mode=Ajax&command=chara_data",
		data : {
			name : name
		},
		success : function(data){
			tmp.html(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			tmp.html("参加者情報取得に失敗しました。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};

//----- カウントダウン -----//
$counters = [];
$counterTimer = null;
var _countDown = function(no){
	var self = $counters[no];
	var data = $.data($(self).get(0),"counter");
	if(data === undefined){
		$counters.splice(no,1);
		return false;
	}
	
	var past = ((new Date()).getTime() - data.start)*data.level;
	if(data.down){
		var now = data.from - past;
		var end = now <= data.to;
	}else{
		var now = data.from + past;
		var end = now >= data.to;
	}
	
	$(self).html(Math.ceil(now));
	if(end){
		$counters.splice(no,1);
		if(data.end) data.end();
	}
};
$.fn.countDown = function(options){
	if(options.level === undefined) options.level = 1;
	if(options.down === undefined) options.down = true;
	if(options.start === undefined) options.start = (new Date()).getTime();
	
	if($counterTimer == null){
		$counterTimer = setInterval(function(){
			for(var i=0;i<$counters.length;i++) _countDown(i);
			if($counters.length == 0) clearInterval($counterTimer);
		},10);
	}
	
	return this.each(function(){
		$counters.push(this);
		$.data($(this).get(0),"counter",options);
	});
};

//----- フォームの幅をそろえる -----//
$.fn.labelJustify = function(){
	return this.each(function(){
		$(" > label",this).justify();
	});
};
//----- 再度そろえる -----//
$.fn.reJustify = function(){
	$(" > label",this).css("width","").justify();
};

//----- フォームの幅をそろえる -----//
$.fn.justify = function(){
	var max = 0;
	$(this).each(function(){
		width = $(this).width();
		if(width > max) max = width;
	});
	
	$(this).css("width",max);
	
	return this;
};
$.fn.formAjax = function(e,reset){
	return_submit(e);
	var form = $(this);
	$.ajax({
		type : form.attr("method"),
		url : form.attr("action"),
		data : form.serializeArray(),
		success : function(data){
			$("#message_box").append("<div>"+data+"</div>");
			if(reset) form.reset();
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			this;
		}
	});
	
	return this;
};