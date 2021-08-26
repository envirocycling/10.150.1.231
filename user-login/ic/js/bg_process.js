$.ajaxSetup({
    cache: false	//use for i.e browser to clean cache
});
$(setInterval(function () {
    $('.bg_refresh').load('bg_proccess.php'); //this means that the items loaded by display.php will be prompted into the class refresh
}, 15000));