<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
}
if(isset($_POST['s_time'])){
	$cutoff_time = date('H:i', strtotime($_POST['time']));
	if(mysql_query("UPDATE system_settings SET fund_cutofftime = '$cutoff_time', fund_cutoff_day='".$_POST['day']."'") or die(mysql_error())){
		echo '<script>
				alert("Successful.");
			</script>';
	}
}/*else if(isset($_POST['s_balance'])){
	$ctr = $_POST['ctr'];
	$num = '1';
	while($ctr > $num){
		$date = $_POST['date'];
		$m_balance = $_POST['m_balance'.$num];
		$branch = $_POST['branch'.$num];
		
		$chk_bal = mysql_query("SELECT * from fund_maitainingbalance WHERE branch_id='$branch' and date='$date'");
		
		if(mysql_num_rows($chk_bal) < 1){
				 mysql_query("INSERT INTO fund_maitainingbalance (branch_id, date, maintaining_balance)
								VALUES ('$branch', '$date', '$m_balance')") or die(mysql_error());
		}else{
				mysql_query("UPDATE fund_maitainingbalance SET date='$date', maintaining_balance='$m_balance' WHERE branch_id='$branch' and date='$date'") or die(mysql_error());
		}
		
		$num++;	
	}
		echo '<script>
				alert("Successful.");
			</script>';
}*/
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
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/notifications.js" type="text/javascript"></script>
        <link rel="stylesheet" href="cbFilter/cbCss.css" />
        <link rel="stylesheet" href="cbFilter/sup.css" />
        <link rel="stylesheet" href="cbFilter/mat.css" />
        <script src="cbFilter/jquery-1.8.3.js"></script>
        <script src="cbFilter/jquery-ui.js"></script>
        <script src="cbFilter/sup_combo.js"></script>
        <script src="cbFilter/mat_combo.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
		<script type="text/javascript" src="js/tcal.js"></script>
		<link rel="stylesheet" type="text/css" href="css/tcal.css" />
		<link rel="stylesheet" type="text/css" href="css/table_setting.css" />
        <style>

            select{
                font-size: 20px;
                width: 150px;
                height: 30px;
            }
            .input{
                font-size: 20px;
                width: 130px;
                height: 30px;
            }
            .submit {
                width: 80px;
                height: 30px;
            }
			.submit2 {
                width: 80px;
                height: 20px;
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

            <div class="middle" >
                <?php
                include 'template/menu.php';
				$sql_cutoff = mysql_query("SELECT * from system_settings") or die(mysql_error());
				$row_cutoff = mysql_fetch_array($sql_cutoff);
				
                ?>
                <div style="margin-left: 30px;">
                    <br>        
                    <br>
                    <h2>CHANGE CUT-OFF</h2>
					<br>
                    <form action="" method="POST" onSubmit="return confirm('Do you want to proceed?');">
                        <table>
                            <tr>
                                <td>Time: </td>
                                <td><input class="input" type="time" name="time" value="<?php if(isset($_POST['submit'])){ echo $_POST['time'];}else{ echo date('H:i', strtotime($row_cutoff['fund_cutofftime']));}?>" required></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;Day:<select name="day">
                                            <?php
                                                echo '<option value="' . $row_cutoff['fund_cutoff_day'] . '">' . $row_cutoff['fund_cutoff_day']. '</option>';
                                            ?>
                                            <option value="Sunday">Sunday</option>
                                            <option value="Monday">Monday</option>
                                            <option value="Tuesday">Tuesday</option>
                                            <option value="Wednesday">Wednesday</option>
                                            <option value="Thursday">Thursday</option>
                                            <option value="Friday">Friday</option>
                                            <option value="Saturday">Saturday</option>
                                        </select></td>
                                <td colspan="2" align="center"><input class="submit" type="submit" name="s_time" value="Submit"></td>
                            </tr>
                        </table>
                    </form>
                    <br>
					
					
					<br><br><br>
                </div>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>