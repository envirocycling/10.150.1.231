$.ajaxSetup({
    cache: false	//use for i.e browser to clean cache
});
$(setInterval(function () {
    $('.refresh').load('backup.php'); //this means that the items loaded by display.php will be prompted into the class refresh
}, 20000));