<?php require_once './../config/query_builder.php';

$branches = fetch("SELECT * FROM `branches` WHERE `status` != 'inactive';", null);

?>


<!doctype html>
<html lang="en">
	
	<?php include './includes/header.php'; ?>

  <body>

  	<?php include './includes/navbar.php'; ?>


    <main role="main" class="container">

      <div class="starter-template mt-4">

        <h3>Truckscale Report Dashboard</h3>

        <hr>

        <h4>Branch Server Status</h4>

        <table class="table table-sm table-bordered">
  <thead>
    <tr>
      <th scope="col">Branch</th>
      <th scope="col">Company Name</th>
      <th scope="col">IP Address</th>
      <th scope="col">Status</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach ($branches as $branch): ?>
  	<tr>
  		<td><?php echo $branch->branch_name ?></td>
  		<td><?php echo utf8_encode($branch->company_name) ?></td>
  		<td><?php echo $branch->ip_address ?></td>
  		<td id="<?php echo $branch->branch_name.'-'.$branch->branch_id ?>">
  			test
  		</td>
  	</tr>
  <?php endforeach ?>
  </tbody>
</table>
        
      </div>

    </main><!-- /.container -->

    <?php include './includes/footer.php'; ?>
  </body>
</html>
