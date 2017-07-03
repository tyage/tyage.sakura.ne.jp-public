$(function () {
	$('#viewSource').click(function (e) {
		e.preventDefault();
		
		$('#source').toggle();
	});
	
	$('.hide').click(function () {
		$(this).toggleClass('open');
	});
})