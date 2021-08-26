$.ajaxSetup ({
	cache: false	//use for i.e browser to clean cache
});
$(setInterval(function(){
	$('.refresh2').load('template/pending_outgoing.php'); //this means that the items loaded by display.php will be prompted into the class refresh
	$('.refresh2').attr({ scrollTop: $('.refresh2').attr('scrollHeight') }) //if the messages overflowed this line tells the textarea to focus the latest message
}, 1000));