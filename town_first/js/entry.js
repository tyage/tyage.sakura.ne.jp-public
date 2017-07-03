var $entry = {
	reload_time : 30,
	timer : null,
	entry : true
};

$entry.set = function(){
	$entry.autoReload();
	
	$("#entry_reload").live("click",function(){
		$entry.reload();
		
		return false;
	});
	
	$("#entry > span").live("click",function(e){
		$(this).chara_view(e);
	});
	
};

$entry.reload = function(){
	$("#entry").html("<img src='./img/loading.gif'>　更新中です・・・");
	$.ajax({
		type : "POST",
		url : "./?mode=Ajax&command=entry&entry=" + $entry.entry,
		success : function(data){
			$("#entry").html(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$("#entry").html("参加者情報取得に失敗しました。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			setHeader();
			$entry.autoReload();
			this;
		}
	});
	
};

$entry.autoReload = function(){
	if($entry.timer != null) clearInterval($entry.timer);
	$entry.timer = setInterval($entry.reload,1000 * $entry.reload_time);
};