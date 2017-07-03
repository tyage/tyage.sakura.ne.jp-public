$.starts.push(function(){
	$('dd.comment').hide();
	$('#addOpinion').hide();
	
	$('.log').click(function(e){
		$(this).parents('dt.comment:first').nextAll('dd.comment').slideToggle('slow');
		e.preventDefault();
	});
	$('.add').click(function(e){
		$('#addOpinion').slideToggle('slow');
		e.preventDefault();
	});
});