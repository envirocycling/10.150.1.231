
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
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
</style>
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
<?php
include 'config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="50">Adj Id</th>
            <th class="data">Details</th>
            <th class="data">Action</th>
        </tr>
        </thead>';
$sql_adj = mysql_query("SELECT * FROM adjustments WHERE bh_approval=''");
while ($rs_adj = mysql_fetch_array($sql_adj)) {
    $sql_users = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_adj['user_id'] . "'");
    $rs_users = mysql_fetch_array($sql_users);
    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $rs_adj['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<tr class='data' style='vertical-align: top;'>";
    echo "<td class='data'>" . $rs_adj['date'] . "</td>";
    echo "<td class='data'>" . $rs_adj['adj_id'] . "</td>";
    echo "<td class='data'>" . ucfirst($rs_users['firstname']) . " " . ucfirst($rs_users['lastname']) . " wants to adjust the transaction of " . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "
          <br>Reason: " . $rs_adj['reason'] . "
          <br>Priority No.: " . $rs_trans['priority_no'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date:" . $rs_trans['date'] . "</td>";
    echo "<td class='data'><a rel='facebox' href='../view_adj.php?adj_id=" . $rs_adj['adj_id'] . "'>View</a></td>";
    echo "</tr>";
}

echo "</table>";
?>