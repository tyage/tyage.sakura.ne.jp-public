$shop = {

};

$shop.set = function(){
	$("table.shopItem > thead").click(function(){
		$(this).next("tbody:first").toggle();
	});
};