$start.shop = function(){
	$("table.shopItem > thead").click(function(){
		$(this).next("tbody:first").toggle();
	});
};