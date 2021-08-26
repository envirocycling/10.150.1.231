    <!-- Bootstrap core JavaScript -->
    <script src="bootstrap-4.0.0/js/jquery.min.js"></script>
    <script src="bootstrap-4.0.0/js/bootstrap.bundle.min.js"></script>
    <script src="bootstrap-4.0.0/js/axios.min.js"></script>
    <script type="text/javascript">

    	$(document).ready(function() {
    		console.log("App running...");
    	});


    	axios.get('http://192.168.254.201/ts_api/api/scalereceiving/receipts?start=2020/07/25&end=2020/07/25', {
    		headers: {
    			'Accept': 'application/json'
    		}
    	}).then(function(res) {
    			console.log(res);
    		})
    		.catch(function(error) {
    			console.log(error);
    		});


    </script>