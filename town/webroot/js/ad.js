$start.ad = function(){
	$('#ads').append('<ul></ul>');
	
	$.ajax({
		url: '/town/ads/view/',
		success: function(data){
			$(data.ad).find('div.title > a').each(function(href){
				$('#ads > ul').append(
					$('<li />').append(
						$('<a />', {
							href: '/town/ads/click/'
						})
						.data('url', $(this).attr('href'))
						.html($(this).html())
					)
				);
			});
		},
		dataType: 'json'
	});
	
	$('#ads a').live('click', function () {
		window.open($(this).data('url'));
	});
};