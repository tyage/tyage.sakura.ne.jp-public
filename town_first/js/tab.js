var $tab = {
	old : [],
	now : []
};

$tab.set = function(){
	$("div.tab").tab();
};

$.fn.tab = function(){
	return this.each(function(){
		var name = $(this).attr("title");
		$tab.old[name] = $(" > div:first",this).attr("title");
		$(" > div:not(div.first)",this).hide();
		
		//セレクト
		$(" > ul > li",this).bind("click",{name:name},function(e){
			var name = e.data.name;
			$tab.now[name] = $(this).attr("title");
			if($tab.old[name] == $tab.now[name]) return false;
			
			//タブ
			$(this).removeClass("on").addClass("select").parent().parent().find(" > ul > li[title="+$tab.old[name]+"]").removeClass("select");
			
			//内容
			$(this).parent().parent().find(" > div[title="+$tab.old[name]+"]").slideUp("slow").parent().find("div[title="+$tab.now[name]+"]").slideDown("normal");
			
			$tab.old[name] = $tab.now[name];
		});
		
		//オンマウス
		$(" > ul > li",this).bind("mouseover",{name:name},function(e){
			var name = e.data.name;
			if($tab.old[name] == $(this).attr("title")) return false;
			$(this).removeClass("select").addClass("on");
		});
		//マウス離す
		$(" > ul > li",this).bind("mouseout",{name:name},function(e){
			var name = e.data.name;
			if($tab.old[name] == $(this).attr("title")) return false;
			$(this).removeClass("on");
		});
	});
};