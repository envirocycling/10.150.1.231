<?php
include 'config.php';
mysql_query("DELETE FROM temp_sup_id");
?>
<script>
   //  $(document).ready(function () {
   //     setInterval(function () {
   //         $.get("template/notification.php", function (result) {
   //             $('#latestData').html(result);
   //         });
   //     }, 1000);
   // });
</script>


<link href="src/facebox.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
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
if($_SESSION['bh_id'] == 1){
$sql_count = mysql_query("SELECT * FROM adv WHERE status=''");
$rs_count = mysql_num_rows($sql_count);

$sql_eadv = mysql_query("SELECT * FROM employee_advances WHERE status='pending' and approver='".$_SESSION['user_id']."' and branch_id='7'");
$rs_eadv = mysql_num_rows($sql_eadv);
?>
<script>
    var pathname = window.location.pathname;
    var data = pathname.split("/");
    if (data[4] !== 'adv_list.php' && data[4] !== 'adv_view.php') {
        var count = '<?php echo $rs_count; ?>';
        if (count > 0) {
            sNotify.addToQueue("You have " + count + "  advances request to verify, <a href='adv_list.php'>Click here</a> to view.");
        }
    }
</script>

<script>
    var pathname = window.location.pathname;
    var data = pathname.split("/");
    if (data[4] !== 'employee_advances_list.php' && data[4] !== 'employee_advances_view.php') {
        var rs_eadv = '<?php echo $rs_eadv; ?>';
        if (rs_eadv > 0) {
            sNotify.addToQueue("You have " + rs_eadv + "  employee advances request to approve, <a href='employee_advances_list.php?view=1'>Click here</a> to view.");
        }
    }
</script>
<?php }?>
<div class="_headermiddle">
    <div class="header_container">
        <main class="header_content">
            <div style="margin-left: -40px;">Envirocycling Fiber Inc.</div>
            <div align='right' style="margin-top: -10px; font-size: 12px;">
                <div id = "latestData">
                </div>
                <a rel='facebox' href="notification.php"><img src='images/notification.png' height="30" title="Click to view your notifications."></a>&nbsp;<a href="initial_settings.php"><img src='images/settings.png' height="30" title="Click to view your settings."></a>&nbsp;<a href="logout.php"><img src='images/logout.png' height="30" title="Click to logout."></a></div>
        </main><!-- .content -->
    </div><!-- .container-->

    <aside class="header_left-sidebar">
        <img src="images/ts_logo.png" height="100">
    </aside><!-- .left-sidebar -->

</div><!-- .middle-->