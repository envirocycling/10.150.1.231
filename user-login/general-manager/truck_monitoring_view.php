<?php
session_start();
date_default_timezone_set("Asia/Singapore");

include 'config.php';

if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}

$sql_ac = mysql_query("SELECT * FROM truck_penalty_reqremove WHERE tpr_id='" . $_GET['tpr_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);
?>
<!DOCTYPE html>
<html>
    <head>
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
        <link href="css/adv_form.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/comment.css" />
        <script type="text/javascript" src="js/comment.js"></script>
        <style>
            .table{
                font-size: 18px;
            }
            .table td{
                padding: 3px;
            }
            #button2{
                width: 115px;
            }
			#supplier_id{
				width: 400px;		
			}
        </style>
        <script>

            $(document).ready(function () {
                $('#supplier_id').select2();
            });
        </script>
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

                <div align="center">
                    <h2>TRUCKMONITORING PENALTY</h2>
                    <br>
                    <table class="table">
                        <tr>
                            <td>Date: </td>
                            <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                        </tr>
                        <tr>
                            <td>Supplier Name:</td>
                            <td><select id="supplier_id" class="medium-select-2" name=""  readonly>
                                    <?php
                                    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_ac['supplier_id'] . "'");
                                    $rs_sup = mysql_fetch_array($sql_sup);
                                    echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Amount: </td>
                            <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>"  readonly></td>
                        </tr>
                        <tr>
                            <td>Remarks: </td>
                            <td colspan="3"><textarea id="justification" class="medium-textarea-2" readonly><?php echo $rs_ac['remarks']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="4" align="right"><br />
							<?php if(empty($rs_ac['status'])){?>	
								<a href="exec/truckmonitoring_exec.php?approve_id=<?php echo $_GET['tpr_id']; ?>"><button class="large-submit" onClick="return confirm('You cannot undo this proccess. Are you sure you want to do this action?')">Approve</button></a> 
                                    <a href="exec/truckmonitoring_exec.php?disapprove_id=<?php echo $_GET['tpr_id']; ?>"><button id="button2" class="large-submit" onClick="return confirm('You cannot undo this proccess. Are you sure you want to do this action?')">Disapprove</button></a>
							<?php }?>
                                <a href="truck_monitoring.php"><button class="large-submit">Back</button></a>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                </div>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>