$(document).ready(function(){
	$('li').hover(function(){
		$(this).find('ul>li').stop().fadeToggle(1000);
	});
});
