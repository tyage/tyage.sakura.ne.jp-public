var hatena = 'http://s.hatena.ne.jp/entries.json';

$(function () {
	$('#entries').getEntries(50);
	
	$('.get').click(function () {
		var count = $(this).data('count');
		$('#entries').getEntries(count);
	})
});

$.fn.extend({
	viewEntry: function (entry) {
		return this.each(function () {
			$(this).append(
				$('<li />').append(
					$('<a />', {
						href: entry.uri,
						text: entry.uri
					})
				).append(
					$('<span />', {
						html: '<span class="star">★</span>'+entry.stars.length
					})
				)
			);
		})
	},
	getEntries: function (count) {
		var uris = [];
		for (var i=1;i<=count;i++) {
			uris.push('http://regex.gkbr.me/'+i);
		}

		$('#onload').show();
		
		return this.each(function () {
			var self = this;
			$(this).empty();
			
			$.ajax({
				url: hatena+'?&uri='+uris.join('&uri='),
				data: {
					timestamp: 1
				},
				dataType: 'jsonp',
				success: function (data) {
					$('#onload').hide();
					
					data.entries.sort(function (a, b) {
						return a.stars.length - b.stars.length;
					}).reverse();
					
					$.each(data.entries, function (i, entry) {
						$(self).viewEntry(entry);
					});
				}
			});
		})
	}
})
