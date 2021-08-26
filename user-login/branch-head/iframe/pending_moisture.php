
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });
</script>
<script>
    function openWindow(str) {
        window.open("../view_pending_moisture.php?add_mc_id=" + str, 'mywindow', 'width=800,height=300,left=150,top=50');
    }
</script>
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
</style>
<?php
include 'config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="50">Req Id</th>
            <th class="data">Details</th>
            <th class="data">Action</th>
        </tr>
        </thead>';
$sql_mc = mysql_query("SELECT * FROM add_mc WHERE bh_approval=''");
while ($rs_mc = mysql_fetch_array($sql_mc)) {
    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_mc['user_id'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $rs_mc['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);
    $sql_det = mysql_query("SELECT * FROM scale_receiving_details WHERE detail_id='" . $rs_mc['detail_id'] . "'");
    $rs_det = mysql_fetch_array($sql_det);
    $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_det['material_id'] . "'");
    $rs_mat = mysql_fetch_array($sql_mat);
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<tr class='data' style='vertical-align: top;'>";
    echo "<td class='data'>" . $rs_mc['date'] . "</td>";
    echo "<td class='data'>" . $rs_mc['add_mc_id'] . "</td>";
    echo "<td class='data'>" . ucfirst($rs_users['firstname']) . " " . ucfirst($rs_users['lastname']) . " add a moisture to the delivery " . $rs_mat['code'] . " of " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "
            <br>
        Priority No.:" . $rs_trans['priority_no'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: " . $rs_trans['date'] . "
        </td>";
    echo "<td class='data'><a rel='facebox' href='../view_mc.php?mc_id=" . $rs_mc['add_mc_id'] . "'>View</a></td>";
    echo "</tr>";
}

echo "</table>";
?>