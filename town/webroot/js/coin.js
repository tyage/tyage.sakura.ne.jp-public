$start.coin = function(){
	$('#coinAll').click(function(){
		$('#coinAmount').attr('disabled', $(this).attr('checked'));
	});
};