<?php 
include('config.php');
echo $rec_trans_id = $_POST['rec_trans_id'];
echo $out_trans_id = $_POST['out_trans_id'];

/*$outgoing  = mysql_query("SELECT * from outgoing WHERE trans_id='$out_trans_id'") or die (mysql_error());
$paper_buying = mysql_query("SELECT * from paper_buying WHERE trans_id='$rec_trans_id'") or die (mysql_error());
$sup_deliveries = mysql_query("SELECT * from sup_deliveries WHERE trans_id='$rec_trans_id'") or die (mysql_error());
$actual = mysql_query("SELECT * from actual WHERE trans_id='$out_trans_id'") or die (mysql_error());

if(mysql_num_rows($sup_deliveries) > 0 && mysql_num_rows($paper_buying) > 0 && mysql_num_rows($outgoing) > 0 && mysql_num_rows($actual) > 0){

	$del_out=mysql_query("DELETE from outgoing WHERE trans_id='$out_trans_id'");
	$del_paper_buying=mysql_query("DELETE from paper_buying WHERE trans_id='$trans_id'");
	$del_sup_deliveries=mysql_query("DELETE from sup_deliveries WHERE trans_id='$trans_id'");
	$del_actual=mysql_query("DELETE from actual WHERE trans_id='$out_trans_id'");
	?>	
		<script>
			alert("Delete Successfull.");
			location.replace("http://192.168.10.200/paymentsystem/user-login/admin/index.php");
		</script>
	<?php
}else{
	?>
	<script>
		alert("No Data Found!");
		location.replace("http://192.168.10.200/paymentsystem/user-login/admin/index.php");
	</script>
	<?php
}*/
?>

<table>
	<tr><h1>Paper Buring</h1></tr>
	<tr>
		<td>Trans Id</td>
		<td>Date</td>
		<td>Supplier ID</td>
		<td>Supplier Name</td>
		<td>Plate Number</td>
		<td>Grade</td>
		<td>Correct Weight</td>
		<td>Unitcost</td>
		<td>Paper Buying</td>
		<td>Branch</td>
		<td>Action</td>
	</tr>
<?php
$actual = mysql_query("SELECT * from paper_buying WHERE trans_id='$out_trans_id' and branc='Pampanga'") or die (mysql_error());
while($row = mysql_fetch_array($actual)){
	?>
	<tr>
		<td><?php echo $row['trans_id'];?></td>
		<td><?php echo $row['date'];?></td>
		<td><?php echo $row['supplier_id'];?></td>
		<td><?php echo $row['supplier_name'];?></td>
		<td><?php echo $row['plate_number'];?></td>
		<td><?php echo $row['wp_grade'];?></td>
		<td><?php echo $row['corrected_weight'];?></td>
		<td><?php echo $row['unit_cost'];?></td>
		<td><?php echo $row['paper_buying'];?></td>
		<td><?php echo $row['branch'];?></td>
		<td><a href="delete_me.php?id=<?php echo $row['log_id'].'&del=paper_buying';?>"><button>Delete</button></a></td>
	</tr>
	<?php
}
?>
<h1>Supp Deliveries</h1>
<table>
	<tr>
		<td>Trans Id</td>
		<td>Supplier ID</td>
		<td>Supplier Name</td>
		<td>Supplier Type</td>
		<td>Grade</td>
		<td>Weight</td>
		<td>Branch</td>
		<td>Date</td>
		<td>Action</td>
	</tr>
<?php
$actual = mysql_query("SELECT * from sup_deliveries WHERE trans_id='$out_trans_id' and branch_delivered='Pampanga'") or die (mysql_error());
while($row = mysql_fetch_array($select)){
	?>
	<tr>
		<td><?php echo $row['trans_id'];?></td>
		<td><?php echo $row['supplier_id'];?></td>
		<td><?php echo $row['supplier_name'];?></td>
		<td><?php echo $row['supplier_type'];?></td>
		<td><?php echo $row['wp_grade'];?></td>
		<td><?php echo $row['weight'];?></td>
		<td><?php echo $row['branch_delivered'];?></td>
		<td><?php echo $row['date_delivered'];?></td>
		<td><a href="delete_me.php?id=<?php echo $row['del_id'].'&del=sup_deliveries';?>"><button>Delete</button></a></td>
	</tr>
	<?php
}
?>
