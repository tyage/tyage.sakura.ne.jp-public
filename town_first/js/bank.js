$bank = {

};

$bank.set = function(){
	$("#bank_form > fieldset :checkbox[name='all']").click(function(){
		$(this).parent().find(" > :text[name='money']").attr("disabled",$(this).attr("checked"));
	});
};
