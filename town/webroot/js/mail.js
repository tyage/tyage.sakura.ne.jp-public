$mail = {
	lastId: 0
};

$start.mail = function () {
	$('#MailAddForm').submit(function (e) {
		$stopEvent(e);
		$(this).submitAjax({
			success: function () {
				$('#reloadMail').click();
				$('#MailAddForm :input:not(:submit)').val('');
			}
		});
	});
	
	$('.mailHeader').live('click', function () {
		$(this).next().toggle();
	});
	$('.mailBody').hide();
	
	$('.unread').live('click', function () {
		$(this).removeClass('unread');
		var id = $(this).attr('mail');
		
		$.ajax({
			url: '/mails/read/'.id
		});
	});
	
	$('#reloadMail').click(function (e) {
		$stopEvent(e);
		$.ajax({
			url: '/town/mails/last/'+$mail.lastId,
			success: function (data) {
				var lastId = parseInt(data.lastId);
				if ($mail.lastId < lastId) {
					$mail.lastId = lastId;
				}
				
				for (key in data.receives) {
					$('#receives').prepend(data.receives[key]);
				}
				for (key in data.sends) {
					$('#sends').prepend(data.sends[key]);
				}
			},
			dataType: 'json'
		});
	});
};