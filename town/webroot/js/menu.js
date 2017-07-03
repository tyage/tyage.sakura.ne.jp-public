$start.menu = function(){
	$('#side > nav > ul > li').hover(
		function(){
			$(this).stop().animate({'marginLeft':'-2px'},200);
		},
		function(){
			$(this).stop().animate({'marginLeft':'-10px'},200);
		}
	);
	
	$('#side-content-menu li').click(function (e) {
		var url = $('a',this).attr('href');
		var content = $(this).data('content');
		
		if (!content) {
			content = $('<div />').addClass('content')
				.appendTo('#side-content').load(url,null,function(response, status, xhr){
					$(this).setup();
					$startup();
				}).get(0);
			$(this).data('content', content);
		}
		$('#side-content > .content').hide();
		$(content).show();
		$('#new-window').attr('href', url);
		$('#side').stop().animate({left:0},1000);
		
		$stopEvent(e);
	});
	
	$('#close-side').click(function () {
		$('#side').stop().animate({left:-600},1000);
	})
};