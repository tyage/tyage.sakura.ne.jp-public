$start.entry = function () {
	setInterval($entry.reload, 30*1000);
	
	$('#reloadEntry').click(function () {
		$('#reloadingEntry').show();
		$.ajax({
			url : '/entries/',
			success : function(data){
				$('#reloadEntry').hide();
				$('#entry').html(data.body);
			}
		});
		
		return false;
	});
};