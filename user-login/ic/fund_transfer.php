<?php
date_default_timezone_set("Asia/Singapore");
$myDate = date('Y/m/d');
@session_start();
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
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
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
		<link rel="stylesheet" type="text/css" href="css/frm_fundtransfer.css" />
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
					$sql_cutoff = mysql_query("SELECT * from system_settings") or die(mysql_error());
					$row_cutoff = mysql_fetch_array($sql_cutoff);
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle" align="center">

                <?php
                include 'template/menu.php';
				if(!isset($_POST['submit'])){
					$date2 = date('F d, Y');
					$myDate = date('Y/m/d');
				}else{
					$date2 = date('F d, Y' , strtotime($_POST['date']));
					$myDate = $_POST['date'];
				}
                ?>
                <br> <br>
				<form action="" method="post">
					<div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date: <input class="tcal" type="text" name="date" value="<?php echo $myDate;?>" size="10" required>&nbsp;&nbsp;&nbsp; <input type="submit" name="submit" style="width:100px; height:25px;"></div>
				   </form>
				<br>
              
                <br>
			<center>
			<?php
			if(isset($_POST['submit'])){
			?>
			<iframe frameborder="0" width="95%" height="850px" src="iframe/fund_transfer.php?date=<?php echo $_POST['date'];?>" name="fund_transfer"></iframe>
			<?php }else{?>
				<iframe frameborder="0" width="95%" height="850px" src="iframe/fund_transfer.php" name="fund_transfer"></iframe>
			<?php }?>
			 </center>
            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
	