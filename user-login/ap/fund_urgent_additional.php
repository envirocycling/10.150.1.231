<?php
session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}


if(isset($_POST['submit'])){
	if(empty($_POST['date'])){
		echo '<script>
				alert("Date is required.");
			</script>';
	}else{
		$sql_chk = mysql_query("SELECT * from fund_transfer WHERE date='".$_POST['date']."' and branch_id='".$_POST['branch']."'") or die(mysql_error());
		if(mysql_num_rows($sql_chk) > 0){
			mysql_query("UPDATE fund_transfer SET urgent_additional='".$_POST['amount']."' WHERE date='".$_POST['date']."' and branch_id='".$_POST['branch']."'") or die(mysql_error());			
		}else{
			mysql_query("INSERT INTO fund_transfer (branch_id, urgent_additional, date) VALUES ('".$_POST['branch']."', '".$_POST['amount']."', '".$_POST['date']."')") or die(mysql_error());
		}	
		
		echo '<script>
				var date = "'.$_POST['date'].'";
				var branch = "'.$_POST['branch'].'";
				window.open("fund_print.php?date=" + date + "&branch=" + branch, "", "height=500,width=900,top=100,left=200");
			</script>';
	}
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <title>Envirocycling Fiber Inc.</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
        <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
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
        </style>
    </head>
    <body>

        <div class="wrapper">

            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle">
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <br>
                <div style="margin-left: 10px;"> 
<script>

	function textAreaAdjust(o) {
    	o.style.height = "1px";
    	o.style.height = (20+o.scrollHeight)+"px";
	}
	
	function isNum(evt) {
    	evt = (evt) ? evt : window.event;
    	var charCode = (evt.which) ? evt.which : evt.keyCode;
   			
			 if (charCode > 44 && charCode < 58) {
       			 	return true;
   				 }
			 return false;
	}
</script>


<link rel="stylesheet" type="text/css" href="css/fund_requestForm.css">
<center>				
<div class="containers">
<br /><br />
                  <h1>ADDITIONAL FUND REQUEST FORM</h1>
<br />
		<div class="hr"><hr></div>
<br /><br />	
<form method="post" onSubmit="btn();">
	<table align="center">
		<tr>
			<td class="label">Date:</td>
			<td align="left"><input class="tcal" type="text" name="date" size="10" id="txt" readonly required></td>
		</tr>
		<tr>
			<td class="label">Branch:</td>
			<td align="lefy">
				<select name="branch" id="txt" required>
					<option value="" selected disabled>Select</option>
					<?php
						$sql_branch = mysql_query("SELECT * from branches") or die(mysql_error());
						while($row_branch = mysql_fetch_array($sql_branch)){
							echo '<option value="'.$row_branch['branch_id'].'">'.strtoupper($row_branch['branch_name']).'</option>';
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<td class="label">Amount:</td>
			<td align="right"><input type="text" id="txt" name="amount" onKeyPress="return isNum(event);" value="<?php echo @$row_add['amount'];?>"  autocomplete="off" required></td>
		</tr>
		<tr>
			<td><?php if(mysql_num_rows($sql_add) > 0){ echo '<br /><br /><a href="fund_addlist.php"><input id="cancel" type="button" value="Back"></a>';}?></td>
			<td align="right"><br /><br /><input id="submit" type="submit" value="Submit" name="submit"></td>
		</tr>
	</table>
</form>	
<br /><br />
</div>
<br /><br />
</center>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>