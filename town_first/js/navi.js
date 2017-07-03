$navi = {

};
$navi.set = function(){
	$("<div></div>").attr("id","navi").addClass("info").css({left:-999,top:-999}).appendTo("body");
	$(".navi").navi();
};
$.fn.navi = function(){
	return this.each(function(){
		//ブラウザ内蔵ツールチップが表示されると邪魔なので、title属性を消す
		var title = $(this).attr("title");
	  $(this).removeAttr("title");
		
		$(this).bind("mouseover",{title:title},function(e){
	    $("#navi").html("<h3><img src='"+$(this).attr("src")+"'> "+$(this).attr("name")+"</h3><p>"+e.data.title+"</p>").show();
		});
		$(this).bind("mousemove",function(e){
			$("#navi").css({
				left : e.pageX + 20,
				top : e.pageY + 20
			});
		});
		$(this).bind("mouseout",function(){
			$("#navi").hide();
		});
	});
};
