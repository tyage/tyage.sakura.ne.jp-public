$start.builder = function () {
	$('#townMap a').click(function (e) {
		var townId = $('img', this).attr('town-id');
		
		$stopEvent(e);
		
		$('#townMap img.present').removeClass('present');
		$(this).parent().find('img').addClass('present');

		$('#towns').load('/town/towns/view/'+townId, function () {
			$(this).setup();
		});

		if ($('#HouseTownId').val() != townId) {
			$('#HouseTownId').val(townId);
		}
	});
	$('#townMap img.present').click();
	
	$('#HouseTownId').change(function () {
		$("#townMap img[town-id='"+$(this).val()+"']:first").click();
	});
	
	var changeVector = function () {
		$('.sell.selected').removeClass('selected');
		var x = $('#HouseX').val();
		var y = $('#HouseY').val();
		$('.town tr').eq(y).find('td').eq(x).find('.sell').addClass('selected');
	};
	$('#HouseX').change(changeVector);
	$('#HouseY').change(changeVector);
	
	$('.sell').live('click', function () {
		$('.sell.selected').removeClass('selected');
		$(this).addClass('selected');
		$('#HouseX').val($(this).parentsUntil('tr').prevAll().length);
		$('#HouseY').val($(this).parentsUntil('tr').parent().prevAll().length);
	});
	
	$('.town a').live('click', function (e) {
		$stopEvent(e);
	});
};