$start.bank = function(){
	$('#BankAll').click(function(){
		$('#BankMoney').attr('disabled',$(this).attr('checked'));
	});
};