$blog = {

};

$blog.set = function(){
	$("#blog > div.comment").hide();
	$("#blog > p.comment").click(function(){
		$("+ div",this).toggle();
	});
};
