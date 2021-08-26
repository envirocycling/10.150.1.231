<?php session_start();?>
<style>
    h1{
        color:white;
    }
    a{
        color:blue;
    }
</style>
<?php
if (!isset($_SESSION["username"])) {
    header('Location: index.php');
}
$usertype=$_SESSION['usertype'];
$branch=$_SESSION['user_branch'];
$_SESSION['current_month']=date('m');
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>EFI INVENTORY MANAGEMENT SYSTEM</title>
        <link href='notifCss/sNotify.css' rel='stylesheet' type='text/css' />
        <script src="notifJS/sNotify.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/reset.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/text.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/grid.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/nav.css" media="screen" />
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery-ui/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setupLeftMenu();
                $('.datatable').dataTable();
                setSidebarHeight();
            });
        </script>
        <link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
        <script src="src/facebox.js" type="text/javascript"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('a[rel*=facebox]').facebox({
                    loadingImage : 'src/loading.gif',
                    closeImage   : 'src/closelabel.png'
                })
            })
        </script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/
        libs/jquery/1.3.0/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setInterval(function() {
                    $.get("noti.php", function (result) {
                        $('#latestData').html(result);
                    });
                }, 1000);
            });
            $(document).ready(function () {
                setInterval(function() {
                    $.get("noti2.php", function (result) {
                        $('#latestData2').html(result);
                    });
                }, 1000);
            });
        </script>
        <script>
            function openMessage(str){
                var x = screen.width/2 - 700/2;
                var y = screen.height/2 - 450/2;
                window.open("message.php",'mywindow','width=380,height=480');
            }
        </script>

    </head>
    <body>

        <div class="container_12">
            <div class="grid_12 header-repeat">
                <div id="branding">
                    <div class="floatleft">
                        <img style="position: absolute; margin-top: -15px;" src="images/efi_ico.png" height="90" width="90"/><h1 style="font-size: 35px; position: absolute; margin-left: 90px;">&nbsp;EFI INVENTORY MANAGEMENT SYSTEM</h1>
                    </div>
                    <div style="margin-top: 10px;">
                        <div class="floatright">
                            <div class="floatleft">
                                <div style="margin-top: -10px;">
                                    <?php
                                    include 'config.php';
                                    $user_id = $_SESSION['username'];
                                    $profpic = mysql_query("SELECT image FROM users WHERE user_id='$user_id'");
                                    $rs_profpic = mysql_fetch_array($profpic);
                                    $profpic=$rs_profpic["image"];
                                    if (empty($profpic)) {
                                        echo'<img src="images/no_avatar.png" height="50" width="50" alt="view profile" />';
                                    } else {
                                        echo '<img src="prof_pic.php?user_id='.$user_id.'" height="50" width="50" alt=""/>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <table>
                                <tr>
                                    <td style="background-color: transparent;">&nbsp;&nbsp;&nbsp;<font style="font-size: 14px; font-weight: bold; margin-top: -10px; color: white;">Welcome <i><u><b><?php echo $_SESSION['username'];?></b></u></i></font>
                                        <br />
                                        &nbsp;&nbsp;&nbsp;<span class="small grey"><?php echo date('D M d, Y'); ?></span>

                                    </td>
                                    <td style="background-color: transparent;">
                                        <a rel="facebox" href="notifications.php" title="Click to view all Notifications"><img src="images/noti.png" width="30" height="30" /></a>
                                        <div id = "latestData">
                                        </div>

                                    </td>

<!--                                    <td style="background-color: transparent;">
                                    <?php
//                                        if ($_SESSION['username'] == 'lonlon') {
                                    ?>
    <a rel="facebox" href="view_message.php" title="Click to view your Message"><img src="images/msg2.png" width="30" height="30" /></a>
                                    <?php
//                                        } else {
                                    ?>
    <a href="#" title="Click to view your Message" onclick='openMessage(this.id);'><img src="images/msg2.png" width="30" height="30" /></a>
                                    <?php
//                                        }
                                    ?>
    <div id = "latestData2">

    </div>
</td>
                                    -->
                                    <td style="background-color: transparent;">
                                        <a href="viewAccountDetails.php" title="Click to edit Account Settings"><img src="images/settings.png" width="30" height="30" /></a>
                                    </td>
                                    <td style="background-color: transparent;">
                                        <a href="logout.php" title="Log-out"><img src="images/logout.png" width="30" height="30" /></a>
                                    </td>

                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="clear">
                    </div>
                </div>
            </div>



            <div class="grid_12">
                <ul class="nav main">
                    <li class="ic-dashboard"><a href=""><span>Dashboard</span></a>
                        <ul>
                            <li><a href="frm_daily_sales_analysis.php">Daily Sales Analysis</a></li>
                            <li><a href="dashboard_outgoing.php">Outgoing</a></li>
                            <li><a href="dashboard_receiving.php">Receiving</a></li>
                            <li><a href="frm_receiving_per_grade.php">Per Grade Receiving Monthly Perf</a></li>
                            <li><a href="frm_receiving_daily_per_grade.php">Per Grade Daily Receiving Perf</a></li>
                            <li><a href="dashboard_overall.php">EFI Overall</a></li>
                            <li><a href="dashboard_pricing_against_competitors.php">Price Against Competitors</a></li>

                        </ul>
                    </li>

                    <li class="ic-form-style"><a href=""><span>Template Uploading</span></a>
                        <ul>
                            <?php
                            if($branch=='Pampanga' || $branch=='Pasay' || $branch=='Urdaneta' || $branch=='Makati' || $branch=='Calamba') {

                                echo '<li><a href="encodeDelivery.php">Receiving</a></li>';
                                echo '<li><a href="frmUploadPickUp.php">Pick-Up</a></li>';
                                echo '<li><a href="frmUploadSortProd.php">Sorting Production</a></li>';
                                echo '<li><a href="frmUploadOutgoing.php">Outgoing</a></li>';
                                echo '<li><a href="frmImportPaperBuying.php">Paper Buying</a></li>';
                            }
                            ?>
                            <li><a href="frmUploadBales.php">Bales</a></li>
                            <li><a href="frmUploadBMProd.php">BM Production</a></li>
                        </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-dashboard"><a href="javascript:"><span>Incentive Monitoring</span></a>';
                        echo '<ul>';
                        echo '<li><a href="inc_deliveries.php">Process Incentive</a></li>';
                        echo '<li><a href="frm_inc_summary.php">Incentive Summary</a></li>';
                        echo '</ul>';
                        echo '</li>';
                    }
                    ?>
                    <li class="ic-grid-tables"><a href="javascript:"><span>Suppliers Performance Reports</span></a>
                        <ul>
                            <?php
                            echo '<li><a href="frm_supplier_analysis.php">Per Classification</a></li>';
                            echo '<li><a href="frm_deliveries_to_competitor.php">Deliveries to Competitors</a></li>';
                            echo '<li><a href="frm_delivery_performance.php">Delvery Performance Per Province</a></li>';
                            echo '<li><a href="frm_remarks_summary.php">Remarks Summary</a></li>';
                            echo '<li><a href="form_weekly_breakdown.php">Weekly Breakdown Per Month</a></li>';
                            echo '<li><a href="frm_monthly_average.php">Weekly Average Per Month</a></li>';
                            echo '<li><a href="frm_monthly_breakdown.php">Monthly Breakdown</a></li>';
                            echo '<li><a href="daily_brkdwn.php">Daily Breakdown</a></li>';
                            echo '<li><a href="frm_start_vol_vs_current_perf.php">Starting Volume</a></li>';
                            echo '<li><a href="frm_new_sup_per_month.php">New Suppliers</a></li>';
                            echo '<li><a href="summary_of_transfer_suppliers.php">Summary of Transfer Suppliers</a></li>';
                            echo '<li><a href="start_vs_target_vs_current_volume.php">Starting Volume Vs Target</a></li>';
                            echo '<li><a href="frm_customize_report.php">Cutomize Report</a></li>';
                            echo '<li><a href="frm_quota_monitoring.php">Quota Monitoring Report</a></li>';
                            echo '<li><a href="frm_phasing.php">Phasing</a></li>';
                            ?>
                        </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>WP Receiving Report</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changeReceivingBranch.php?branch='.$branch.'"><span>WP Receiving Report</span></a>';
                    }

                    ?>
                    <ul>
                        <?php

                        if($usertype=='Super User') {
                            echo ' <li><a href="changeReceivingBranch.php?branch=all">All Branches</a></li>';
                            echo '<li><a href="branch_performance.php">Branch Per Grade</a></li>';
                            echo '<li><a href="branch_weekly.php">Branch Weekly</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';

                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a href="changeReceivingBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }

                        }

                        ?>
                    </ul>
                    </li>

                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>WP Pick-Up Reports</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changePickUpBranch.php?branch='.$branch.'"><span>WP Pick-Up Reports</span></a>';
                    }

                    ?>
                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="all_pick_up_report.php">All Branches</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';
                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a href="changePickUpBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>Paper Buying</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changePaperBuyingBranch.php?branch='.$branch.'"><span>Paper Buying</span></a>';
                    }


                    ?>

                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="form_paper_buying_summary.php">Summary</a></li>';
                            echo ' <li><a href="paper_buying_all.php">All Data</a></li>';
                            echo "<hr>";
                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a href="changePaperBuyingBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>Sorting Production Report</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changeSortingBranch.php?branch='.$branch.'"><span>Sorting Production Report</span></a>';
                    }
                    ?>
                    <ul>
                        <?php

                        if($usertype=='Super User') {
                            echo ' <li><a href="all_sorting_production.php">All Branches</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';
                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a href="changeSortingBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>WP Outgoing Reports</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changeBranch.php?branch='.$branch.'"><span>WP Outgoing Reports</span></a>';
                    }

                    ?>
                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="frmOutgoing_monthly_breakdown.php">Monthly Breakdown</a></li>';
                            echo ' <li><a href="frm_outgoing_report.php">All Branches</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';
                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a href="changeBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>
                    <li class="ic-grid-tables"><a href="inter-branch_report.php"><span>Inter-Branch</span></a>
                        <ul>
                        </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>Suppliers TAT</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changeTATBranch.php?branch='.$branch.'"><span>Suppliers TAT</span></a>';
                    }
                    ?>
                    <ul>
                        <?php
                        foreach($_SESSION['branches'] as $value) {
                            echo '<li><a href="changeTATBranch.php?branch='.$value.'">'.$value.'</a></li>';
                        }
                        ?>
                    </ul>
                    </li>
                    <?php
                    if($usertype=='Super User') {
                        echo'  <li class="ic-grid-tables"><a href="javascript:"><span>Inventory Analysis</span></a>';
                    }else {
                        echo'  <li class="ic-grid-tables"><a  href="frmInventoryAnalysis.php?branch='.$branch.'"><span>Inventory Analysis</span></a>';
                    }
                    ?>
                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="all_inventory_analysis.php">All Branches</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';
                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a  href="frmInventoryAnalysisSuperUsers.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>
                    <?php

                    echo '<li class="ic-grid-tables"><a href="javascript:"><span>BM Production</span></a>';

                    ?>
                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="all_bmprod.php">All Branches</a></li>';
                            echo ' <li><a href="form_bale_wire_inventory.php?branch=">Form Bale Wire Inventory</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';

                            foreach($_SESSION['branches'] as $value) {

                                echo ' <li><a href="changeBMProdBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }else {

                            echo ' <li><a href="form_bale_wire_inventory.php?branch='.$branch.'">Form Bale Wire Inventory</a></li>';

                            echo ' <li><a href="changeBMProdBranch.php?branch='.$branch.'">Bm Prod Report</a></li>';


                        }
                        ?>
                    </ul>
                    </li>

                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>Daily TEXT Report</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changeTextBranch.php?branch='.$branch.'"><span>Daily Text Report</span></a>';

                    }
                    ?>
                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="changeTextBranch.php">All Branches</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';

                            foreach($_SESSION['branches'] as $value) {

                                echo ' <li><a href="changeTextBranch.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>

                    <?php
                    if($usertype=='Super User') {
                        echo '<li class="ic-grid-tables"><a href="javascript:"><span>TS-FS analysis</span></a>';
                    }else {
                        echo '<li class="ic-grid-tables"><a href="changeTSFSanalysis.php?branch='.$branch.'"><span>TS-FS analysis</span></a>';

                    }
                    ?>
                    <ul>
                        <?php
                        if($usertype=='Super User') {
                            echo ' <li><a href="all_tsfs_analysis.php">All Branches</a></li>';
                            echo ' <li><a href="#">_________________</a></li>';

                            foreach($_SESSION['branches'] as $value) {
                                echo ' <li><a href="changeTSFSanalysis.php?branch='.$value.'">'.$value.'</a></li>';
                            }
                        }
                        ?>
                    </ul>
                    </li>

                    <li class="ic-grid-tables"><a href=""><span>Truck Reports</span></a>
                        <ul>
                            <li><a href="frm_add_new_truck.php">New Truck</a></li>
                            <li><a href="existing_truck.php">Existing Truck</a></li>
                            <li><a href="frm_volume_monitoring.php">Volume Monitoring</a></li>


                        </ul>
                    </li>

                    <?php
                    if ($_SESSION['username']=='lonlon') {
                        ?>
                    <li class="ic-grid-tables"><a href="ic_monitoring.php"><span>IC Monitoring</span></a></li>
                        <?php
                    }
                    ?>
                    <!--
                                                     <li class="ic-grid-tables"><a href="javascript:"><span>Pricing Against Competitors</span></a>
                                                         <ul>
                    <?php
                    /*
                    foreach($_SESSION['wp_grades'] as $value) {

                        echo ' <li><a href="pricing_competitors.php?grade='.$value.'">'.$value.'</a></li>';
                    }
                    */
                    ?>

                                                         </ul>
                                                     </li>
                    -->



                </ul>
            </div>
            <div class="clear">
            </div>

            <?php
            $url= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
            $parseURL=preg_split("[/]",$url);
            $url=$parseURL[1];
            if($url!='summary_of_transfer_suppliers.php' && $url!='volume_monitoring.php' && $url!='branch_weekly.php' && $url!='monthly_brkdwn.php' && $url!='current_month_weekly_brkdwn.php' && $url!='customize_report.php' && $url!='quota_monitoring.php' && $url!='phasing.php' && $url!='monthly_average.php' && $url!='supplier_analysis.php' && $url!='monthly_breakdown.php' && $url!='weekly_breakdown_per_month.php'  && $url!='dashboard_receiving_per_grade.php') {

                ?>
            <div class="grid_2">
                <div class="box sidemenu">
                    <div class="block" id="section-menu">
                        <ul class="section menu">
                            <li><a class="menuitem">Supplier Management</a>
                                <ul class="submenu">
                                        <?php
                                        echo ' <li><a href="supplierlist.php">Supplier List</a></li>';

                                        if($_SESSION['usertype']=='Super User') {
                                            echo ' <li><a href="formAddNewSupplier.php">Add A New Supplier</a></li>';
                                            echo ' <li><a href="edit_supplier.php">Edit Suppliers</a></li>';
                                            echo ' <li><a href="pending_suppliers.php">Pending Suppliers</a></li>';
                                            echo ' <li><a href="inactive_suppliers.php">Inactive Suppliers</a></li>';
//                                            echo ' <li><a href="unknown_branch_suppliers.php">Unknown Suppliers</a></li>';
                                            echo ' <li><a href="transfer_suppliers.php">Transfer Suppliers</a></li>';
//                                            echo ' <li><a href="formUploadSuppliers.php">Bulk Adding</a></li>';

                                        }

                                        ?>
                                </ul>
                            </li>


                            <li><a class="menuitem">Price Management</a>
                                <ul class="submenu">
                                        <?php
                                        echo ' <li><a href="overall_price_list.php">Overall Price List</a></li>';
                                        ?>
                                </ul>
                            </li>

                            <li><a class="menuitem">Site Management</a>
                                <ul class="submenu">
                                        <?php
                                        if($_SESSION['usertype']=='Super User') {
                                            echo '<li><a href="admin_grade_management.php">WP Grades </a></li>
                    <li><a href="admin_branch_management.php">Branches </a></li>
                    <li><a href="admin_outgoing_management.php">Manage Outgoing </a></li>
                    <li><a href="admin_form_target_quarterly.php">Target Quarterly </a></li>
                    <li><a href="admin_form_target_monthly.php">Target Monthly </a></li>
                    ';
                                        }
                                        ?>
                                </ul>
                            </li>
                            <li><a class="menuitem">Packing List</a>
                                <ul class="submenu">
                                        <?php
                                        echo ' <li><a href="frmPackingList.php">Generate</a></li>';
                                        echo ' <li><a href="viewPackingList.php">View Packing List</a></li
                    >';
                                        ?>
                                </ul>
                            </li>




                        </ul>
                    </div>
                </div>
            </div>

                <?php }else {
                echo '<div class="clear">

                    </div>';

            }
            ?>


            <?php
            include ('config.php');
            $result = mysql_query("SELECT count(log_id) FROM pricing_against_competitors where verified_by='".$_SESSION['username']."'  and verified_status='' and verified_status!='disapproved' ");
            while($row = mysql_fetch_array($result)) {
                $dbCount=$row['count(log_id)'];
                if ($dbCount>0) {
                    echo "<script>
		         sNotify.addToQueue('You currently have  ".$dbCount."  <b><i>price update(s)  </i></b>&nbsp;
                    to <u><a href=dashboard_pricing_against_competitors.php> verify</a></u>.');
			 </script>";

                }
            }

            $result2 = mysql_query("SELECT count(log_id) FROM pricing_against_competitors where approved_by='".$_SESSION['username']."'  and verified_status='verified' and approved_status='' ");
            while($row2 = mysql_fetch_array($result2)) {
                $dbCount2=$row2['count(log_id)'];
                if ($dbCount2>0) {
                    echo "<script>
		         sNotify.addToQueue('You currently have  ".$dbCount2."  <b><i>price update(s)  </i></b>&nbsp;
                    to<u> <a href=dashboard_pricing_against_competitors.php> approve</a></u>.');
                    </script>";

                }
            }

            ?>

