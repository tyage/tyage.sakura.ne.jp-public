$enq = {};

$enq.set = function(){
	$("#add_choice").click(function(){
		$(this).parent().append("<div><input type='text' name='add_choice[]' size='30' /><span class='add_cancel'>消す</span><br></div>");
	});
	$("span.add_cancel").live("click",function(){
		$(this).parent().remove();
	});
	
	$("#remove_cancel").click(function(){
		$(this).parent().find(" > select > option:selected").attr("selected",false);
	});
}