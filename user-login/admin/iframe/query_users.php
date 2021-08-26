
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });
</script>
<script>
    function openWindow(str) {
//        window.open("../request_adjustment.php?trans_id=" + str, 'mywindow', 'width=700,height=300,left=150,top=50');
        window.open("../edit_out_transaction.php?trans_id=" + str, 'mywindow', 'width=700,height=500,left=150,top=50');

    }
</script>
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>
<base target="_parent" />
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .submit{
        height: 20px;
        width: 60px;
        font-size: 12px;
    }
    .submit2{
        height: 20px;
        width: 120px;
        font-size: 12px;
    }
</style>
<?php
include '../config.php';

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Username</th>
            <th class="data" width="80">Full Name</th>
            <th class="data" width="80">Initial</th>
            <th class="data" width="80">Position</th>
            <th class="data">Action</th>
        </tr>
        </thead>';
$sql_users = mysql_query("SELECT * FROM users WHERE status!='deleted'");
while ($rs_users = mysql_fetch_array($sql_users)) {
    echo "<tr>";
    echo "<td>" . $rs_users['username'] . "</td>";
    echo "<td>" . ucfirst($rs_users['firstname']) . " " . ucfirst($rs_users['lastname']) . "</td>";
    echo "<td>" . $rs_users['initial'] . "</td>";
    echo "<td>" . $rs_users['position'] . "</td>";
    echo "<td></td>";
    echo "</tr>";
}
echo "</table>";
?>