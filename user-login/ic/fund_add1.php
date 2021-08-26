<?php
session_start();
date_default_timezone_set("Asia/Singapore");
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
			.at_time{
		color:#666666;
		font-style:italic;
		font-size:11px;	
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
                    <h2> ADDITIONAL FUND REQUEST</h2>
                    <br>
                    <form action="" method="POST">
                        <?php
                        if (isset($_POST['submit'])) {
						 @$myDate = date('Y/m/d' ,strtotime('+ 1 day', strtotime($_POST['date'])));
							
                            echo 'To: <input class="tcal" type="text" name="date" value="' . $_POST['date'] . '" size="10" readonly>';
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Branch: <select name="branch">';
                            $sql2 = mysql_query("SELECT * FROM branches WHERE branch_id != '7' and  branch_id != '10'");
							$sql = mysql_query("SELECT * FROM branches WHERE branch_id='".$_POST['branch']."'");
							$myrow = mysql_fetch_array($sql);
							//if(mysql_num_rows($sql2) > 0){echo '<option value="' . $myrow['branch_id'] . '">' . $myrow['branch_name'] . '</option>';}
							echo '<option value="'.$_POST['branch'].'">'.$myrow['branch_name'].'</option>';
							echo '<option value="All">All</option>';
                           while( $rs = mysql_fetch_array($sql2)){
                            echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
							}
                            echo '</select>';
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class = "small-submit" type = "submit" name = "submit" value = "Submit">';
                        } else {
                            echo 'To : <input class="tcal" type="text" name="date" value="' . date('Y/m/d'). '" size="10" readonly>';
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Branch: <select name="branch">';
                            //$sql = mysql_query("SELECT * FROM branches WHERE connected='1'");
							$sql = mysql_query("SELECT * FROM branches WHERE branch_id != '7' and  branch_id != '10'");
							echo '<option value="All">All</option>';
                             while( $rs = mysql_fetch_array($sql)){
                            echo '<option value="' . $rs['branch_id'] . '">' . $rs['branch_name'] . '</option>';
							}
                            echo '</select>';
							echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class = "small-submit" type = "submit" name = "submit" value = "Submit">';
							 @$myDate = date('Y/m/d' ,strtotime('+ 1 day'));
                        }

					 @$myBranch = $_POST['branch'];
						if($myBranch == 'All' || !isset($_POST['submit'])){
							$sql_data =  mysql_query("SELECT * from fund_adtl_request WHERE date <= '$myDate' ") or die(mysql_error());
						}else{
						$sql_data =  mysql_query("SELECT * from fund_adtl_request WHERE  branch_id='$myBranch' and date <= '$myDate' ") or die(mysql_error());
						}
                        ?>
                    </form>
                    <br>
                    <div class="table">
                      
                            <table class="data display datatable" id="example">
                                <thead>
                                    <tr class="data">
                                        <th class="data">Date</th>
                                        <th class="data">Branch</th>
                                        <th class="data">Amount</th>
                                        <th class="data">Remarks</th>
                                        <th class="data">Prepared By</th>
                                        <th class="data">Verified By</th>
                                    </tr>
                                </thead>
                                <?php
                                while (@$rs_data = mysql_fetch_array($sql_data)) {
								
									$sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='".$rs_data['branch_id']."' ");
									$row_branch = mysql_fetch_array($sql_branch);
									
									
                              
                                    echo "<tr>";
                                    echo "<td>" . date("Y/m/d", strtotime($rs_data['date'])) . "</td>";
                                    echo "<td>" . $row_branch['branch_name'] . "</td>";
                                    echo "<td>" . number_format($rs_data ['amount'],2) . "</td>";
                                    echo "<td>" . $rs_data['remarks'] . "</td>";
                                    echo "<td>" . $rs_data['fullname'] . "</td>";
                                    echo "<td>" . $rs_data['verified_name'] . "</td>";
                               
								}
                                ?>
                            </table>
                        
                    </div>
                </div>

                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>