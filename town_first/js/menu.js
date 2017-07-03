var $menu = {
	timer : {}
};

$menu.set = function(){
  setHeader();
	
	$("#topMenu > li").each(function(){
		$(" > ul",this).find(" > li:even").addClass("even").parent().find(" > li:odd").addClass("odd");
		
		//メニューオーバー
		$(" > ul > li",this).bind("mouseover",function(){
			$(this).removeClass("even").removeClass("odd").addClass("on");
		});
		$(" > ul > li:even",this).bind("mouseout",function(){
			$(this).removeClass("on").addClass("even");
		});
		$(" > ul > li:odd",this).bind("mouseout",function(){
			$(this).removeClass("on").addClass("odd");
		});
	});
	
	//ウィンドウ表示
	$("#windowMenu > ul > li").bind("click",function(){
		title = $(this).attr("title");
		$win.open(title);
	});
};
