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
        <link rel="stylesheet" type="text/css" href="css/frm_fundtransfer2.css" />
		<script type="text/javascript" src="js/fund_process2.js"></script>
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
                width: 98%;
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
                    <h2> Branch Weekly Expense</h2>
                    <br>
                    <form action="" method="POST">
                        <?php
                        if (isset($_POST['submit'])) {
						 @$myDate = date('Y/m/d' ,strtotime('+ 1 day', strtotime($_POST['date'])));
							
                            echo 'From: <input class="tcal" type="text" name="from" value="' .$_POST['from'] . '" size="10" readonly>';
                            echo 'To: <input class="tcal" type="text" name="to" value="' .$_POST['to'] . '" size="10" readonly>';
                            
                            
                            
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class = "small-submit" type = "submit" name = "submit" value = "Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' .date('Y/m/d') . '" size="10" readonly>';
                            echo 'To : <input class="tcal" type="text" name="to" value="' . date('Y/m/d'). '" size="10" readonly>';
                            
                            
							echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class = "small-submit" type = "submit" name = "submit" value = "Submit">';
							 @$myDate = date('Y/m/d' ,strtotime('+ 1 day'));
                        }

					 @$myBranch = $_POST['branch'];
						if($myBranch == 'All' || !isset($_POST['submit'])){
							$sql_data =  mysql_query("SELECT * from fund_adtl_request WHERE date <= '$myDate' ") or die(mysql_error());
							$sql_urg =  mysql_query("SELECT * from fund_transfer WHERE date <= '$myDate' and urgent_additional > 0") or die(mysql_error());
						}else{
						$sql_data =  mysql_query("SELECT * from fund_adtl_request WHERE  branch_id='$myBranch' and date <= '$myDate' ") or die(mysql_error());
						$sql_urg =  mysql_query("SELECT * from fund_transfer WHERE branch_id='$myBranch' and date <= '$myDate'  and urgent_additional > 0") or die(mysql_error());
						}
                        ?>
                    </form>
                    <br>
                    
                    <?php
                        $from = $_POST['from'];
                        $to = $_POST['to'];
                        $arr_branch = array();
                        $arr_weekly = array();
                        $arr_datechk = array();
                        $arr_date = array();
                        $arr_total = array();
                        
                        $sql_branch = mysql_query("SELECT * from branches WHERE branch_id!='7'") or die(mysql_error());
                        while($row_branch = mysql_fetch_array($sql_branch)){
                            array_push($arr_branch,$row_branch['branch_id']);
                        }
                        
                        $sql_weekly = mysql_query("SELECT * from fund_transfer WHERE date >= '$from' and date <= '$to' order by date Asc") or die(mysql_error());
                        while($row_weekly = mysql_fetch_array($sql_weekly)){
                            $arr_weekly[$row_weekly['branch_id']][$row_weekly['date']] = $row_weekly['transferred'];
                            array_push($arr_datechk,$row_weekly['date']);
                        }
                        $arr_date = array_unique($arr_datechk);
                    if(isset($_POST['submit'])){
                    ?>
                    <br><br>
                    <style>
                        #encode:hover{
                            font-size: 15px;
                            text-decoration: underline;
                            color: blue;
                            font-weight: 800;
                            cursor: pointer;
                        }
                        #encode{
                            font-size: 15px;
                            font-weight: 700;
                        }
                    </style>
                    <script>
                        function f_encode(){
                            var from  = "<?php echo $from;?>";
                            var to  = "<?php echo $to;?>";
                            window.open('fund_weekly_budget.php?from='+ from + '&to=' + to , 'mywindow', 'width=400,height=500,left=500,top=20');
                        }
                    </script>
                    <center><span id="encode" onclick="f_encode();">Encode Weekly Budget</span></center>
                    <div class="table">
                      
                            <table class="frm_fundtransfer">
                                <tr>
                                    <td style="height:15px;">Branch</td>
                                    <td style="height:15px;">Weekly Budget</td>
                                    <?php
                                        foreach($arr_date as $slctd_date){
                                            echo '<td>';echo date('M d (D)', strtotime($slctd_date)).'<font size="1"><br>'.$slctd_date.'</font>'; echo '</td>';
                                        }
                                    ?>
                                    <td style="height:15px;">Total Allocated</td>
                                    <td style="height:15px;">Variance</td>
				</tr>
                                <?php
                                    foreach($arr_branch as $slctd_branch){
                                        $sql_branch = mysql_query("SELECT * from branches WHERE branch_id ='$slctd_branch'") or die(mysql_error());
                                        $row_branch = mysql_fetch_array($sql_branch);
                                        
                                        $sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='".$row_branch['branch_id']."' and `from`='$from' and `to`='$to'") or die(mysql_error());
                                        $row_chk = mysql_fetch_array($sql_chk);
                                        
                                        echo '<tr>';
                                               echo '<td>'.$row_branch['branch_name'].'</td>';
                                               echo '<td>'.number_format($row_chk['budget'],2).'</td>';
                                               foreach($arr_date as $slctd_date){
                                                   echo '<td>'.number_format($arr_weekly[$slctd_branch][$slctd_date], 2).'</td>';
                                                   $arr_total[$slctd_branch] += $arr_weekly[$slctd_branch][$slctd_date];
                                               }
                                        $variance = number_format(($row_chk['budget'] - $arr_total[$slctd_branch]),2);
                                        if($variance < 0){
                                            $variance = str_replace("-","",$variance);
                                            $variance = '<font color="red">('.$variance.')</font>';
                                        }
                                               echo '<td style="background-color:yellow;">'.number_format($arr_total[$slctd_branch],2).'</td>';
                                               echo '<td style="background-color:yellow;">'.$variance.'</td>';
                                        echo '</tr>';
                                    }
                                ?>
                            </table>
                        
                    </div>
                    <?php }?>
                </div>

                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>