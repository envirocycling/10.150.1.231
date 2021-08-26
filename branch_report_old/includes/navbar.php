<?php $base_url = 'http://10.151.5.172/branch_report'; ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

    	<div class="container">
	        <a class="navbar-brand" href="<?php echo $base_url ?>">Truckscale Reports</a>
	        
	        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
	            <span class="navbar-toggler-icon"></span>
	        </button>

	        <div class="collapse navbar-collapse" id="navbarColor01">
	            <ul class="navbar-nav mr-auto">
	                <li class="nav-item active">
	                    <a class="nav-link" href="<?php echo $base_url ?>">
	                    	Dashboard 
	                    	<span class="sr-only">(current)</span>
	                    </a>
	                </li>

	                <li class="nav-item">
	                    <a class="nav-link" href='<?php echo "{$base_url}/receiving.php" ?>'>Receiving</a>
	                </li>

	                <li class="nav-item">
	                    <a class="nav-link" href='<?php echo "{$base_url}/outgoing.php" ?>'>Outgoing</a>
	                </li>

	                <li class="nav-item">
	                    <a class="nav-link" href='<?php echo "{$base_url}/expense.php" ?>'>Expense</a>
	                </li>
	            </ul>
	        </div>

    	</div>
    </nav>