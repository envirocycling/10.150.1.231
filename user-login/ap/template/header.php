<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
$url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//
$parseURL = preg_split("[/]", $url);
//
$url = $parseURL[3];
if ($url != 'initial_settings.php') {
    if (!isset($_SESSION['ap_id'])) {
        echo "<script>location.replace('../../');</script>";
    }
    if (!isset($_SESSION['trade_verifier']) || !isset($_SESSION['nontrade_verifier'])) {
        echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
    location.replace('initial_settings.php');
    </script>";
    }
    if (!isset($_SESSION['trade_signatory']) || !isset($_SESSION['nontrade_signatory'])) {
        echo "<script>
    alert('Signatory is not set Please go to settings and update the setup.');
    history.back();
    </script>";
    }
}
//
//if ($url != 'payment_next.php' || $url != 'payment_paid_edit_next.php') {
//    mysql_query("UPDATE `temp_payment` SET `bank_code`='',`cheque_no`='',`old_cheque_no`='',`voucher_no`='',`cheque_name`='',`supplier_id`='',`sub_total`='',`ts_fee`='',`adjustments`='',`grand_total`='',`account_name`='',`account_number`='',`ap`='',`verifier`='',`remarks`='',`charge_to`='',`signatory`='' WHERE user_id='" . $_SESSION['user_id'] . "'");
//    mysql_query("UPDATE `temp_payment_adjustment` SET `adj_type`='',`desc`='',`amount`='' WHERE user_id='" . $_SESSION['user_id'] . "'");
//    mysql_query("UPDATE `temp_payment_others` SET `particulars`='',`quantity`='',`unit_price`='',`amount`='' WHERE user_id='" . $_SESSION['user_id'] . "'");
//}
?>

<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
<link href='notifCss/sNotify.css' rel='stylesheet' type='text/css' />
<script src="notifJS/sNotify.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: 'src/loading.gif',
            closeImage: 'src/closelabel.png'
        })
    })
</script>
<link href='css/sNotify.css' rel='stylesheet' type='text/css' />
<script src="js/sNotify.js" type="text/javascript"></script>
<?php
include '../config.php';

$sql_date = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_date = mysql_fetch_array($sql_date);
$current_date = strtotime($row_date['current_date']);
$date2 = date('Y/m/d');
$date = strtotime($date2);

if($date != $current_date){
   mysql_query("UPDATE `system_settings` SET `current_date` = '$date2', `ea_pcv_no` = '01'") or die(mysql_error());
}

$sql_branch = mysql_query("SELECT * FROM branches");
while ($rs_branch = mysql_fetch_array($sql_branch)) {
    if ($rs_branch['branch_id'] == '7') {
        $sql_count = mysql_query("SELECT * FROM adv WHERE status='approved' and branch_id='" . $rs_branch['branch_id'] . " and class=''");
    } else {
        $sql_count = mysql_query("SELECT * FROM adv WHERE acpty_id='2' and status='approved' and branch_id='" . $rs_branch['branch_id'] . " and class='' '");
    }
    @$rs_count = mysql_num_rows($sql_count);
    ?>
    <script>
        var pathname = window.location.pathname;
        var data = pathname.split("/");
        if (data[4] !== 'adv_list.php' && data[4] !== 'adv_view.php') {
            var count = '<?php echo $rs_count; ?>';
            var branch = '<?php echo $rs_branch['branch_name']; ?>';
            var branch_id = '<?php echo $rs_branch['branch_id']; ?>';
            if (count > 0) {
                sNotify.addToQueue("You have " + count + "  advances request to process from " + branch + ", <a href='adv_list.php?branch_id=" + branch_id + "'>Click here</a> to view.");
            }
        }

    </script>
 <?php
    
}

        $sql_eadv = mysql_query("SELECT * from employee_advances WHERE status = 'approved' and branch_id='7'");
        $num_row = mysql_num_rows($sql_eadv);
    ?>
    <script>
        var pathname = window.location.pathname;
        var data = pathname.split("/");
        if (data[4] !== 'employee_advances_list.php' && data[4] !== 'employee_advances_view.php') {
            var count = '<?php echo $num_row; ?>';
            if (count > 0) {
                sNotify.addToQueue("You have " + count + "  employee advances request to process <a href='employee_advances_list.php?for_process=1'>Click here</a> to view.");
            }
        }

    </script>
<div class="_headermiddle">
    <div class="header_container">
        <main class="header_content">
            <div style="margin-left: -40px;">Envirocycling Fiber Inc.</div>
            <div align='right' style="margin-top: -10px; font-size: 12px;">
                <!--                <div id = "latestData">
                                </div>-->
                <a rel='facebox' href="notification.php"><img src='images/notification.png' height="30" title="Click to view your notifications."></a>&nbsp;<a href="initial_settings.php"><img src='images/settings.png' height="30" title="Click to view your settings."></a>&nbsp;<a href="logout.php"><img src='images/logout.png' height="30" title="Click to logout."></a></div>
        </main><!-- .content -->
    </div><!-- .container-->

    <aside class="header_left-sidebar">
        <img src="images/pay_logo.png" height="100">
    </aside><!-- .left-sidebar -->

</div><!-- .middle-->
