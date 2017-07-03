var $mail = {
	reload_time : 30
};

$mail.set = function(){
	
	$("tr.mes_detail").live("click",function(){
		$(this).next("tr.mes:first").find(" > td > pre").slideToggle("normal");
	});
	
	$("#mail_reload").click(function(){
		$mail.reload();
	});
	
	$("#mail_form").submit(function(e){
		$.ajax({
			type : "POST",
			url : "./?mode=Mail&command=reload",
			data : $(this).serializeArray(),
			success : function(data){
				$mail.redraw(data);
				
				//エラーが起きていなければフォームの内容を消す
				if($("#mail_content").is(":not(:has('span.error_mes'))")){
					$("#mail_form > :text,#mail_form > textarea").removeAttr("value").empty();
				}
			},
			error : function(XMLHttpRequest, textStatus, errorThrown){
				$mail.redraw("メール情報取得に失敗しました。");
				this;
			},
			complete : function(XMLHttpRequest, textStatus){
				this;
			}
		});
		
		return_submit(e);
	});
	
};

$mail.reload = function(){
	$mail.redraw("<span><img src='./img/loading.gif'>　更新中です・・・</span>");
	
	$.ajax({
		type : "POST",
		url : "./?mode=Mail&command=reload",
		success : function(data){
			$mail.redraw(data);
			$("#mail_rept").parent().remove();
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			$mail.redraw("メール情報取得に失敗しました。");
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};

$mail.redraw = function(data){
	$("#mail_content").html(data);
	$("#mailbox").tab();
};

$mail.check = function(){
	$.ajax({
		type : "POST",
		url : "./?mode=Mail&command=check",
		success : function(data){
			$("#mail_rept").parent().remove();
			if(data != "") $message.add("<div id='mail_rept'>"+data+"</div>");
		}
	});
};
$mail.autoCheck = function(){
	setInterval($mail.check,1000 * $mail.reload_time);
};