
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
            <th class="data" width="40">Date</th>
            <th class="data" width="80">STR #</th>
            <th class="data" width="80">Series #</th>
            <th class="data" width="80">Supplier Name</th>
            <th class="data">Plate #</th>            
            <th class="data">Delivered To</th>
            <th class="data">Branch</th>
            <th class="data">Action</th>
        </tr>
        </thead>';
if (!isset($_GET['from'])) {
    $sql_rec = mysql_query("SELECT * FROM scale_outgoing WHERE branch_id!='7' and status!='void' and date>='" . date("Y/m/d") . "' and date<='" . date("Y/m/d") . "'");
} else {
    $sql_rec = mysql_query("SELECT * FROM scale_outgoing WHERE branch_id!='7' and status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
}
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_rec['dt_id'] . "'");
    $rs_dt = mysql_fetch_array($sql_dt);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_rec['date'] . "</td>";
    echo "<td class='data'>" . $rs_rec['str_no'] . "</td>";
    echo "<td class='data'>" . $rs_rec['series_no'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "_" . strtoupper($rs_sup['supplier_name']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_rec['plate_number']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_dt['name']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_sup['branch']) . "</td>";
    echo "<td class='data'><button id='" . $rs_rec['trans_id'] . "' class='submit' onclick='openWindow(this.id);'>Update</button> | ";
    echo "<a href=\"../delete_outgoing.php?trans_id=" . $rs_rec['trans_id'] . "\"><button class='submit' onclick=\"return confirm('Do you want to Delete?');\">Delete</button></td>";
    echo "</tr>";
}
echo "</table>";
?>