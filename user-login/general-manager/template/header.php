
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
include 'config.php';

$sql_branch = mysql_query("SELECT * FROM branches");
while ($rs_branch = mysql_fetch_array($sql_branch)) {
    $sql_count = mysql_query("SELECT * FROM adv WHERE status='verified' and branch_id='" . $rs_branch['branch_id'] . " '");
    $rs_count = mysql_num_rows($sql_count);
    ?>
    <script>
    var pathname = window.location.pathname;
    var data = pathname.split("/");
    if (data[4] !== 'adv_list.php' && data[4] !== 'adv_view.php') {
        var count = '<?php echo $rs_count; ?>';
        var branch = '<?php echo $rs_branch['branch_name']; ?>';
        var branch_id = '<?php echo $rs_branch['branch_id']; ?>';
        if (count > 0) {
            sNotify.addToQueue("You have " + count + "  advances request to approve from " + branch + ", <a href='adv_list.php?branch_id=" + branch_id + "'>Click here</a> to view.");
        }
    }

    </script>
    <?php
}
$sql_ea = mysql_query("SELECT * from employee_advances WHERE status = 'pending' and approver LIKE '".$_SESSION['gm_id']."-%'") or die(mysql_error());
?>
    <script>
    var pathname = window.location.pathname;
    var data = pathname.split("/");
    if (data[4] !== 'employee_advancelist.php' && data[4] !== 'employee_advances_view.php') {
        var count = '<?php echo mysql_num_rows($sql_ea); ?>';
        if (count > 0) {
            sNotify.addToQueue("You have " + count + "  employee advances request to approve, <a href='employee_advancelist.php'>Click here</a> to view.");
        }
    }
    </script>
     <?php

$sql_ea = mysql_query("SELECT * from client_price WHERE status = '0' group by client_id") or die(mysql_error());
?>
    <script>
    var pathname = window.location.pathname;
    var data = pathname.split("/");
    if (data[4] !== 'price_client.php') {
        var count = '<?php echo mysql_num_rows($sql_ea); ?>';
        if (count > 0) {
            sNotify.addToQueue("You have " + count + "  client prices request to approve, <a href='price_client.php'>Click here</a> to view.");
        }
    }
    </script>
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