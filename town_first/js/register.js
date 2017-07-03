var $register = {
	hasError : false,
	isNameValid : false,
	isIdValid : false
};

$.fn.addError = function(data){
	$(this).parent().prev().find("div.error").append("<p>"+data+"</p>");
	$register.hasError = true;
	$(".justify").reJustify();
};

$.fn.isBlank = function(){
	return $(this).val() == "" || $(this).val() == null;
};
$.fn.isEnglish = function(){
	return $(this).isBlank() || $(this).val().match(/^[a-z\d]+/i);
};
$.fn.isMail = function(){
	return $(this).isBlank() || $(this).val().match(/^[A-Za-z0-9]+[\w-]+@[\w\.-]+\.\w{2,}/);
};

$.fn.checkRequired = function(){
	return $(this).each(function(){
		if($(this).isBlank()) $(this).addError("入力必須です。");
	});
};
$.fn.checkEnglish = function(){
	return $(this).each(function(){
		if(!$(this).isEnglish()) $(this).addError("半角英数字で書いてください。");
	});
};
$.fn.checkMail = function(){
	return $(this).each(function(){
		if(!$(this).isMail()) $(this).addError("メールアドレスが正しくないです。");
	});
};

$register.checkId = function(){
	var id = $(":input[name='id']");
	$.ajax({
		type : "POST",
		url : "./?mode=Ajax&command=checkId",
		data : {
			id : id.val()
		},
		success : function(data){
			$register.isIdValid = (data == "");
			if(!$register.isIdValid) id.addError(data);
			$register.submit();
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};
$register.checkName = function(){
	var name = $(":input[name='name']");
	$.ajax({
		type : "POST",
		url : "./?mode=Ajax&command=checkName",
		data : {
			name : name.val()
		},
		success : function(data){
			$register.isNameValid = (data == "");
			if(!$register.isNameValid) name.addError(data);
			$register.submit();
		},
		error : function(XMLHttpRequest, textStatus, errorThrown){
			this;
		},
		complete : function(XMLHttpRequest, textStatus){
			this;
		}
	});
};

$register.set = function(){
	$("#registerForm").bind("submit",function(e){
		return_submit(e);
		$register.isIdValid = $register.isNameValid = $register.hasError = false;
		
		$("div.error").empty();
		
		$(":input.required",this).checkRequired();
		$(":input.english",this).checkEnglish();
		$(":input.mail",this).checkMail();
		
		$register.checkId();
		$register.checkName();
	});
	
	$("#charaImageSelect").bind("change",function(){
		$("#charaImage").attr("src","./img/chara/" + $(this).val());
	});
	
	$("#registerForm > label").append("<div class='error'></div>");
	$("#charaImageSelect").change();
};

$register.submit = function(){
	if($register.isIdValid && $register.isNameValid && !$register.hasError){
		$("#registerForm").unbind("submit").submit();
	}
};