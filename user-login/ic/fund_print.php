<script>
	print();
</script>
<?php
include("config.php");

$date= $_GET['date'];
$branch = $_GET['branch'];

if($_GET['b_req'] == '1'){
    $sql_urg2 = mysql_query("SELECT * from fund_adtl_request WHERE afr_id= '".$_GET['fund_id']."'") or die(mysql_error());
}else{
    $sql_urg = mysql_query("SELECT * from fund_transfer WHERE date='$date' and branch_id='$branch'") or die(mysql_error());
    $row_urg = mysql_fetch_array($sql_urg);
}

$sql_branch = mysql_query("SELECT * from branches WHERE branch_id='$branch'") or die(mysql_error());
$row_branch= mysql_fetch_array($sql_branch);
?>
<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
}
$sql_user = mysql_query("SELECT * from users WHERE user_id='".$_SESSION['ic_id']."'") or die(mysql_error());
$row_user = mysql_fetch_array($sql_user);
?>

        <script type="text/javascript">
            $(document).ready(function () {
                setupLeftMenu();
                $('.datatable').dataTable();
                setSidebarHeight();
            });
			
        </script>
        <style>
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 1180px;
            }
            button{
                width: 70px;
            }
			.txt{
				font-weight:bold;
				font-size:20px;
			}
        </style>

<center>
<link rel="stylesheet" type="text/css" href="css/pay_table.css">			
<br /><br />
	<div style="width:60%;">
            <?php
               if(mysql_num_rows($sql_urg2) > 0){
                    $row_urg = mysql_fetch_array($sql_urg2);  ?>
            <table align="center" class="payTable">
		<tr>
			<td colspan="2"><span class="txt">Additional Fund Request</span></td>
		</tr>
		<tr>
			<td class="label"><span class="txt">Date:</span></td>
			<td><span class="txt"><?php echo $row_urg['date'];?></span></td>
		</tr>
		<tr>
			<td class="label"><span class="txt">Branch:</span></td>
			<td><span class="txt"><?php echo strtoupper($row_branch['branch_name']);?></span></td>
		</tr>
		<tr>
			<td class="label"><span class="txt">Amount:</span></td>
			<td><span class="txt"><?php echo number_format($row_urg['amount'],2);?><span></td>
		</tr>
		<tr>
			<td class="label">Remarks:</td>
			<td><?php echo $row_urg['remarks'];?></td>
		</tr>
		<tr>
			<td><br /><br />Prepared By: <?php echo strtoupper($row_urg['fullname']);?></td>
			<td><br /><br />Approved By: <?php echo strtoupper($row_urg['verified_name']);?></td>
		</tr>
		<!--<tr>
			<td colspan="2"><br /><br />Approved By: <?php echo strtoupper($row_user['firstname'].', '.$row_user['lastname']);?></td>
		</tr>-->
	</table>
               <?php } else{
            ?>
	<table align="center" class="payTable">
		<tr>
			<td colspan="2"><span class="txt">Additional Fund Request</span></td>
		</tr>
		<tr>
			<td class="label"><span class="txt">Date:</span></td>
			<td><span class="txt"><?php echo $row_urg['date'];?></span></td>
		</tr>
		<tr>
			<td class="label"><span class="txt">Branch:</span></td>
			<td><span class="txt"><?php echo strtoupper($row_branch['branch_name']);?></span></td>
		</tr>
		<tr>
			<td class="label"><span class="txt">Amount:</span></td>
			<td><span class="txt"><?php echo number_format($row_urg['urgent_additional'],2);?><span></td>
		</tr>
		<tr>
			<td colspan="2"><br /><br />Prepared By: <?php echo strtoupper($row_user['firstname'].', '.$row_user['lastname']);?></td>
		</tr>
	</table>
               <?php }?>
	</div>
<br /><br />
</div>
<br /><br />
</center>

