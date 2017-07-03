$.starts = [];
$.starts.push(function(){
	$('header nav > ul').lavaLamp();
});
$(function(){
	for (i in $.starts) $.starts[i]();
});