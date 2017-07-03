$ad = {

};

$ad.set = function(){
	$.ajax({
		type : "GET",
		url : "./?mode=Game&command=viewAd",
		success : function(data){
			$("#ads").append("<ul></ul>");
    	$(data).find("div.title > a").each(function(href){
    		$("#ads > ul").append("<li><a href="+$(this).attr("href")+" target='_blank'>"+$(this).html()+"</a></li>");
    	});
    	
    	$("#ads > ul > li > a").bind("click",function(){
    		$(this).adClick();
    	});
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};

$.fn.adClick = function(){
	var self = $(this);
	$.ajax({
		type : "GET",
		url : "./?mode=Game&command=clickAd",
		success : function(data){
			$message.add(data);
			self.parent().remove();
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};