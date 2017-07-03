$start.tree = function(){
	$('.folder').closeFolder().click(function(){
		$(this).toggleClass('open').toggleClass('close').nextAll().toggle('normal');
	});
	
	$('li.open').parents('ul').prevAll('.folder').openFolder();
};

$.fn.openFolder = function(){
	return $(this).each(function(){
		$(this).nextAll().show();
	}).removeClass('close').addClass('open');
};
$.fn.closeFolder = function(){
	return $(this).each(function(){
		$(this).nextAll().hide();
	}).removeClass('open').addClass('close');
};