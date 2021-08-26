<!doctype html>
<html lang="en">
	
	<?php include './includes/header.php'; ?>

  <body>

  	<?php include './includes/navbar.php'; ?>

    <main role="main" class="container">

      <div class="starter-template mt-4">

        <h3>Receiving Report</h3>

        <hr>

        <div class="card p-4 mb-4">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
        </div>

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
