<?php
include('config.php');
$id=$_GET['id'];
$del = $_GET['del'];
$select = mysql_query("SELECT *	from actual WHERE log_id='$id'") or die(mysql_error());
$my_row = mysql_fetch_array($select);
$outgoing= mysql_query("SELECT * from outgoing WHERE log_id='$id'") or die(mysql_error());
$outgoing_row = mysql_fetch_array($outgoing);
$paper= mysql_query("SELECT * from paper_buying WHERE log_id='$id'") or die(mysql_error());
$paper_row = mysql_fetch_array($paper);
$supp= mysql_query("SELECT * from sup_deliveries WHERE del_id='$id'") or die(mysql_error());
$supp_row = mysql_fetch_array($supp);

if($del == 'actual'){
	if(mysql_query("INSERT INTO tbl_deleted_data (str_no,date,delivered_to,plate_number,wp_grade,weight,branch,mc,dirt,net_weight,comments,trans_id,detail_id,table_name) VALUES('".$my_row['str_no']."','".$my_row['date']."','".$my_row['delivered_to']."','".$my_row['plate_number']."','".$my_row['wp_grade']."','".$my_row['weight']."','".$my_row['branch']."','".$my_row['mc']."','".$my_row['dirt']."','".$my_row['net_weight']."','".$my_row['comments']."','".$my_row['trans_id']."','".$my_row['detail_id']."','actual')") or die (mysql_error())){
		
		mysql_query("DELETE from actual WHERE log_id='$id'") or die (mysql_error());
	?>
	<script>
	alert("Successful.");
	window.history.back();
</script>
	<?php
	}
}else if($del == 'outgoing'){
	if(mysql_query("INSERT INTO tbl_deleted_data (str,date,trucking,plate_number,wp_grade,weight,mc_perct,mc_weight,branch,trans_id,detail_id,table_name) VALUES('".$outgoing_row['str']."','".$outgoing_row['date']."','".$outgoing_row['trucking']."','".$outgoing_row['plate_number']."','".$outgoing_row['wp_grade']."','".$outgoing_row['weight']."','".$outgoing_row['mc_perct']."','".$outgoing_row['mc_weight']."','".$outgoing_row['branch']."','".$outgoing_row['trans_id']."','".$outgoing_row['detail_id']."','outgoing')") or die (mysql_error())){
	mysql_query("DELETE from outgoing WHERE log_id='$id'") or die (mysql_error());
	?>
	<script>
	alert("Successful.");
	window.history.back();
</script>
	<?php
	}
}else if($del == 'paper_buying'){
	if(mysql_query("INSERT INTO tbl_deleted_data (date,supplier_id,supplier_name,plate_number,wp_grade,weight,unit_cost,paper_buying,branch,trans_id,detail_id,table_name) VALUES('".$paper_row['date_received']."','".$paper_row['supplier_id']."','".$paper_row['supplier_name']."','".$paper_row['wp_grade']."','".$paper_row['corrected_weight']."','".$paper_row['unit_cost']."','".$paper_row['paper_buying']."','".$paper_row['branch']."','".$paper_row['trans_id']."','".$paper_row['detail_id']."','paper_buying')") or die (mysql_error())){
	mysql_query("DELETE from paper_buying WHERE log_id='$id'") or die (mysql_error());
	?>
	<script>
	alert("Successful.");
	window.history.back();
</script>
	<?php
	}
}else if($del == 'sup_deliveries'){
	if(mysql_query("INSERT INTO tbl_deleted_data (supplier_id,supplier_name,supp_type,bh_incharge,wp_grade,weight, branch,date,month,day,year,trans_id,detail_id,table_name) VALUES('".$supp_row['supplier_id']."','".$supp_row['supplier_name']."','".$supp_row['supplier_type']."','".$supp_row['bh_in_charge']."','".$supp_row['wp_grade']."','".$supp_row['weight']."','".$supp_row['branch_delivered']."','".$supp_row['date_delivered']."','".$supp_row['month_delivered']."','".$supp_row['day_delivered']."','".$supp_row['year_delivered']."','".$supp_row['trans_id']."','".$supp_row['detail_id']."','sup_deliveries')") or die (mysql_error())){
	mysql_query("DELETE from sup_deliveries WHERE log_id='$id'") or die (mysql_error());
	?>
	<script>
	alert("Successful.");
	window.history.back();
</script>
	<?php
	}
}else{
?>
	<script>
		alert("System Error.");
		window.history.back();
	</script>
<?php
}
?>