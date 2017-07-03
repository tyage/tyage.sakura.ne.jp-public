var $chat = {
	timer : null,
	reload_time : 15
};

$chat.set = function(){
	//自動でチャット更新
	$chat.autoReload();
	
	//チャットをリロード
	$("#chat_reload").live("click",function(){
		$chat.reload();
		
		return false;
	});
	
	$("#chatAutoReload").live("change",function(){
		$chat.autoReload();
	});
	
	//チャット投稿
	$("#chat_form").bind("submit",function(e){
		//コメント、フラグ検査
		if($("#chat_comment").attr('value') == "" || $chat.flag) return_submit(e);
		$chat.flag = true;
		
		return_submit(e);
		
		$.ajax({
			type : "POST",
			url : "./?mode=Chat&command=submit",
			data : $(this).serializeArray(),
			success : function(data){
				$chat.redraw(data);
				
				//エラーが起きていなければフォームの内容を消す
				if($("#chat_content").is(":not(:has('span.error_mes'))")){
					$("#chat_form > :text[name='comment']").removeAttr("value");
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown){
				$chat.redraw("チャット情報取得に失敗しました。");
				this;
			},
			complete : function(XMLHttpRequest, textStatus){
				$chat.autoReload();
				this;
			}
		});
	});
};

$chat.reload = function(){
	$chat.redraw("<span><img src='./img/loading.gif'>　更新中です・・・</span>");
	
	$.ajax({
		type : "POST",
		url : "./?mode=Chat&command=view",
		success : function(data){
			$chat.redraw(data);
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$chat.redraw("チャット情報取得に失敗しました。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			$chat.autoReload();
			this;
		}
	});
};

$chat.redraw = function(data){
	$("#chat_content").html(data).each(function(){
		$("label",this).justify();
	});
};

$chat.autoReload = function(){
	if($chat.timer != null) clearInterval($chat.timer);
	if($("#chatAutoReload").is(":checked")) $chat.timer = setInterval($chat.reload,1000 * $chat.reload_time);
};
