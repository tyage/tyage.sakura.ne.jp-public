$game = {

};

$game.set = function(){
	$("form.coin_exchange :checkbox[name='all']").click(function(){
		$(this).parent().find(" > :text[name='coin']").attr("disabled",$(this).attr("checked"));
	});
};
