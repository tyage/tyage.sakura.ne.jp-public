$builder = {
	town : null,
	apartTown : null,
	apart : {
		elem : null,
		img : null
	},
	house : {
		elem : null,
		img : null
	}
};

$builder.set = function(){
	$builder.town = $("#builder_form > :select[name='town']").val();
	$("#apart").hide();
	$("#houseImg").hide();
	
	function resetVector(){
		$("#builder_form > :hidden[name='x']").val("").find(" ~ :hidden[name='y']").val("");	
		$("#town_vector").empty();
	}
	function changeVector(x,y){
		$("#town_vector").html(y + "-" + x);
		$("#builder_form > :hidden[name='x']").val(x).find(" ~ :hidden[name='y']").val(y);
	}
	function changeType(type){
		$("#builder_form > :hidden[name='type']").val(type);
	}
	
	//----- 街変更 -----//
	$("#builder_form > :select[name='town']").change(function(){
		var townNow = $(this).val();
		if(townNow == $builder.town) return false;
		$builder.town = townNow;
		
		$("#map").html("<span><img src='./img/loading.gif'>　更新中です・・・</span>");
		$.ajax({
			type : "POST",
			url : "./?mode=Map&command=simple_view&town=" + townNow,
			success : function(data){
				$("#map").html(data);
				
				var townData = $("#town_data");
				var name = townData.attr("name");
				var price = townData.attr("price");
			},
			error : function(XMLHttpRequest, textStatus, errorThrown){
				$("#map").html("マップ情報取得に失敗しました。");
				this;
			},
			complete : function(XMLHttpRequest, textStatus){
				resetVector();
				$("#apart").hide();
				$("#houseImg").hide();
				
				this;
			}
		});
	});
	
	//----- 空地 -----//
	$("#map > div.map > div > img.sell").live("click",function(){
		$(this).selectLand();
		changeType("House");
		
		changeVector($builder.house.elem.attr("vx"),$builder.house.elem.attr("vy"));
		
		$builder.apartTown = null;
		
		$("#houseImg").show();
		$("#apart").hide();
	});
	
	//----- アパート -----//
	$("#map > div.map > div > img.apart").live("click",function(){
		if($builder.apartTown != null && $builder.apartTown == $builder.town) return false;
		$builder.apartTown = $builder.town;
		
		$(this).selectLand();
		changeType("Apart");
		
		$("#houseImg").hide();
		$("#apart").show();
		
		$.ajax({
			type : "POST",
			url : "./?mode=House&command=apart_simple&town=" + $builder.apartTown,
			success : function(data){
				$("#apart").html(data);
			},
			error : function(XMLHttpRequest, textStatus, errorThrown){
				$("#apart").html("マップ情報取得に失敗しました。");
				this;
			},
			complete : function(XMLHttpRequest, textStatus){
				resetVector();
				this;
			}
		});
	});
	$("#apart img.sell").live("click",function(){
		changeVector($(this).attr("vx"),$(this).attr("vy"));
		$(this).selectLand(true);
	});
	
	//----- データ送信 -----//
	$("#builder_form").bind("submit",function(e){
		return_submit(e);
		
		if(!$(" > :select[name='town']",this).val()){
			alert("街を決めてください。");
			return;
		}
		if(!$(" > :hidden[name='x']",this).val() || !$(" > :hidden[name='y']",this).val()){
			alert("座標を決めてください。");
			return;
		}
		if($builder.type != "apart" && !$("#houseImg :raido[name='img']").val()){
			alert("家画像を決めてください。");
			return;
		}
		
		$(this).formAjax();
	});
	
};

$.fn.selectLand = function(apart){
	var type = apart == undefined ? "house" : "apart";
	if($builder[type].elem != null) $builder[type].elem.attr("src",$builder[type].img);
	$builder[type].elem = $(this);
	$builder[type].img = $builder[type].elem.attr("src");
	$builder[type].elem.attr("src","img/star.gif");
};