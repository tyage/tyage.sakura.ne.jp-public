var $chat = {
	lastId: 0
};

$start.chat = function() {
	$('#ChatAddForm').submit(function(e) {
		$stopEvent(e);
		
		$(this).submitAjax({
			success: function(data) {
				$('#reloadChat').click();
				$('#ChatBody').val('');
				$('<span />').text(data.bonus+'円ゲット！').css('color','red')
					.appendTo('#ChatAddForm').delay(3000).fadeOut(1000);
			},
			dataType: "json"
		});
		
	});
	
	$('#reloadChat').click(function(e) {
		$stopEvent(e);
		
		$.ajax({
			url : '/town/chats/last/'+$chat.lastId,
			success: function(data) {
				for (key in data.messages) {
					$('#chatMessage').prepend(data.messages[key]);
				}
				
				var lastId = parseInt(data.lastId);
				if ($chat.lastId < lastId) {
					$chat.lastId = lastId;
				}
			},
			dataType: "json"
		});
	});
};
