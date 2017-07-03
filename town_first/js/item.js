var $item = {
};

$item.set = function(){
	$("#item_form").submit(function(e){
		$item.reload($(this).serializeArray());
		
		return_submit(e);
	});
};

$item.reload = function(data){
	$.ajax({
		type : "POST",
		url : "./?mode=Item&command=reload",
		data : data,
		success : function(data){
			$item.redraw(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$item.redraw("アイテム情報取得に失敗しました。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};

$item.redraw = function(data){
	$("#item_form").html(data).find("table.list").tableList();
};
