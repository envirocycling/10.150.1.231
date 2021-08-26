<script>
//    $(document).ready(function () {
//        setInterval(function () {
//            $.get("bg_process.php", function (result) {
//                $('#latestData').html(result);
//            });
//        }, 15000);
//    });

</script>
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
<script src="js/backup.js" type="text/javascript"></script>
<?php
include 'config.php';

$sql_date = mysql_query("SELECT * from system_settings") or die(mysql_error());
$row_date = mysql_fetch_array($sql_date);
$current_date = strtotime($row_date['current_date']);
$date2 = date('Y/m/d');
$date = strtotime($date2);

if($date != $current_date){
   mysql_query("UPDATE `system_settings` SET `current_date` = '$date2', `ea_pcv_no` = '01'") or die(mysql_error());
}
$sql_eadv = mysql_query("SELECT * from employee_advances WHERE status = 'approved' and branch_id ='7'");
        $num_row = mysql_num_rows($sql_eadv);
        
 if ($_SESSION['ic_id'] == 53) {
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
<?php
 }

?>
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