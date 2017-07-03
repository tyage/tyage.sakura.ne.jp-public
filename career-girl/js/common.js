$(function() {
	var autoSlide = function() {
		var len = $('#slide-nav li').length,
			index = $('#slide-content li').index($('#slide-content li:visible'));
		$('#slide-nav li').eq(++index%len).click();
	};
	
	$('#slide-nav li').click(function() {
		var index = $('#slide-nav li').index(this);
		$('#slide-content li').fadeOut()
			.eq(index).fadeIn();
	});
	
	$('#slide-content li').hide().filter(':first').show();
	setInterval(function() {
		autoSlide();
	}, 7*1000);
	
	$('#time-table').jScrollPane();
	$('#time-table .movie').ceebox({
		videoGallery: false
	});
});