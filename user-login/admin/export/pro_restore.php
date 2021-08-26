<?php
include 'con1.php';
$id = $_GET['id'];
$select = mysql_query("SELECT * from tbl_deleted_data WHERE id = '$id'") or die (mysql_error());
$row = mysql_fetch_array($select);

$table = $row['table_name'];
$str = $row['str_no'];
$date = $row['date'];
$plate = $row['plate_number'];
$grade = $row['wp_grade'];
$weight = $row['weight'];
$branch = $row['branch'];
$mc = $row['mc'];
$dirt = $row['dirt'];
$trucking = $row['trucking'];
$suppid = $row['supplier_id'];
$suppname = $row['supplier_name'];
$cost = $row['unit_cost'];
$paper = $row['paper_buying'];
$number = $row['priority_number'];
$transid = $row['trans_id'];
$supptype = $row['supp_type'];
$bh = $row['bh_incharge'];
$month = $row['month'];
$day = $row['day'];
$year = $row['year'];
$num = 0;

if(mysql_num_rows($select) > 0){
	
	if($table == 'outgoing'){	
		if(mysql_query("INSERT INTO outgoing (trans_id,str,date,trucking,plate_number,wp_grade,weight,branch) VALUES('$transid','$str','$date','$trucking','$plate','$grade','$weight','$branch')") or die (mysql_error())){
		$num=1;
		?>
			<script>
				alert("Outgoing Data Restore Successfully.");
				window.history.back();
			</script>
		<?php
		}
	}else if($table == 'paper_buying'){	
		if(mysql_query("INSERT INTO paper_buying (trans_id,date_received,priority_number,supplier_id,supplier_name,plate_number,wp_grade,corrected_weight,unit_cost,paper_buying,branch) VALUES('$transid','$date','$number','$suppid','$suppname','$plate','$grade','$weight','$cost','$paper','$branch')") or die (mysql_error())){
		$num=1;
		?>
			<script>
				alert("Paper Buying Data Restore Successfully.");
				window.history.back();
			</script>
		<?php
		}
	}else if($table == 'supp_deliveries'){	
		if(mysql_query("INSERT INTO supp_deliveries (trans_id,supplier_id,supplier_name,supplier_type,bh_in_charge,wp_grade,weight,branch_delivered,date_delivered,month_delivered,day_delivered,year_delivered,priority_number,plate_number) VALUES('$transid','$suppid','$suppname','$supptype','$bh','$grade','$weight','$branch','$date','$month','$day','$year','$number','$plate')") or die (mysql_error())){
		$num=1;
		?>
			<script>
				alert("Supplier Deliveries Data Restore Successfully.");
				window.history.back();
			</script>
		<?php
		}
	}
}
if($num == 0){
?>
	<script>
		alert("System Error.");
		window.history.back();
	</script>
<?php
}
?>