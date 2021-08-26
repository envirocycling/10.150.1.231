<script>
	print();
</script>
<style>
	.container{
		border:groove;
		border-radius:4px;
		font-family:Verdana, Arial, Helvetica, sans-serif;
		width:600px;
		margin:0 auto;
	}
	.title{
		font-size:18px;
		text-align:center;
		font-weight:bold;
		padding-top:10px;
		padding-bottom:5px;
	}
	.table{
		display:table;
		width:95%;
		margin:0 auto;
	}
	.td{
		display:table-cell;
		padding-bottom:3px;
		font-weight:bold;
		font-size:13px;
	}
	.tr{
		display:table-row;
	}
	.data{
		text-decoration:underline;
		font-weight:bold;
		font-size:13px;
	}
	.purpose{
		text-decoration:underline;
		font-weight:bold;
		font-size:13px;
		text-indent:60px;
	}
	.label2{
		padding-top:20px;
		font-size:13px;
		padding-left:11px;
	}
	.note{
		font-size:12px;
		color:#FF0000;
		font-style:italic;
	}			

</style>

<?php
	include("config.php");
	
	$ea_id = $_GET['ea_id'];
	
	$sql_ea = mysql_query("SELECT * from employee_advances WHERE ea_id='$ea_id'") or die(mysql_error());
	$row_ea = mysql_fetch_array($sql_ea);
	
	$sql_approver = mysql_query("SELECT * from users WHERE user_id='".$row_ea['approver']."'") or die(mysql_error());
	$row_approver = mysql_fetch_array($sql_approver);
        
        $sql_prepare = mysql_query("SELECT * from users WHERE user_id='".$row_ea['prepared_by']."'") or die(mysql_error());
	$row_prepare = mysql_fetch_array($sql_prepare);
	
	$sql_emp = mysql_query("SELECT * from employee WHERE emp_id ='".$row_ea['emp_id']."'") or die(mysql_error());
	$row_emp = mysql_fetch_array($sql_emp);
?>

<div class="container">

	<div class="title">EMPLOYEE ADVANCES</div>
	<hr></hr>
<?php if($row_ea['status'] == 'issued'){?>	
	<div class="note">Note: Cash advance already issued.</div>
	<hr></hr>
<?php }?>	
		<div class="table">
			<div class="tr">
				<div align="left" class="td">Date: <label class="data"><?php echo date('F d, Y', strtotime($row_ea['date']));?></label></div>
				<div align="right" class="td">Ref No. <label class="data"><?php echo strtoupper($row_ea['ref_no']);?></label></div>
			</div>
		</div>
		
	<hr></hr>
	
	<div class="label2">Employee Name:  <label class="data"><?php echo strtoupper($row_emp['name']);?></label></div>
	<div class="label2">Amount Advance:  <label class="data"><?php echo 'Php '.number_format($row_ea['amount'], 2);?></label></div>
	<div class="label2">Purpose:  <label class="purpose"><p><?php echo strtoupper($row_ea['purpose']);?></p></label></div>
	
	<br />
	<hr></hr>
	
	<div class="table">
			<div class="tr">
				<div class="td">Approved By:</div>
				<div class="td" align="center" style="border-right:groove;"><label class="data"><img src="../../signatures/EAA.png" width="120" height="60px"><br></label><?php echo strtoupper($row_approver['firstname'].', '.$row_approver['lastname']);?><div style="font-size:9px;"> <?php echo date('Y-m-d h:i A', strtotime($row_ea['date_time_approved']));?></div></div>
				<div align="right" class="td">Received By:</div>
				<div align="center" class="td"><label class="data"><div style="width:100%; height:60px;"></label><?php echo '<br>'.strtoupper($row_emp['name']);?></div>
			</div>
	</div>
</div>
        <center>
            <table border="1px" width="100%" style="border-collapse: collapse;">
            <tr>
                <td class="td" colspan="4"><center><br />Prepared By: <?php echo strtoupper($row_prepare['firstname'].', '.$row_prepare['lastname']);?></center></td>
            </tr>
        </table>
        </center>