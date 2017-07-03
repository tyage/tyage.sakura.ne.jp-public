var $config = {
};

$config.set = function(){
	//CSS
	$("#config_css > fieldset > div > select").configCssChange().change(function(){
		$(this).configCssChange();
	});
	
	//キャラ画像変更
	$("#config_chara > fieldset > div > select[name='img']").configImageChange().change(function(){
		$(this).configImageChange();
	});
	
	$("#changeHouseImage").click(function(){
		$("#houseImages").toggle();
	});
};

$.fn.configCssChange = function(){
	var theme = $(this).nextAll("div.theme:first");
	var original = $(this).nextAll("div.original:first");
	var val = $(this).val();
	
	val == "theme" ? theme.show() : theme.hide();
	val == "original" ? original.show() : original.hide();
	
	return this;
};
$.fn.configImageChange = function(){
	$("#img_view").attr("src","./img/chara/" + $(this).val());
	
	return this;
};
