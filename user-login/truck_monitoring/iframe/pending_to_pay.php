
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
        window.open("../save_receiving.php?trans_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');
    }
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
<base target="_parent" />
<?php
include '../config.php';
if (isset ($_GET['cancel_id'])) {
    mysql_query("UPDATE scale_receiving SET status='' WHERE trans_id='".$_GET['cancel_id']."'");
    echo "<script>";
    echo "location.replace('../pending_to_pay.php');";
    echo "</script>";
}
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="40">P No.</th>
            <th class="data">Supplier Name</th>
            <th class="data">Plate No.</th>
             <th class="data">Action</th>
        </tr>
        </thead>';
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE status='pending'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_rec['date'] . "</td>";
    echo "<td class='data'>" . $rs_rec['priority_no'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_name'] . "</td>";
    echo "<td class='data'>" . $rs_rec['plate_number'] . "</td>";
    echo "<td class='data'><button id='".$rs_rec['trans_id']."' onclick='openWindow(this.id);'>Edit</button></td>";
    
//    echo "<td class='data'><button id='".$rs_rec['trans_id']."' onclick='openWindow(this.id);'>Edit</button><a href='pending_to_pay.php?cancel_id=".$rs_rec['trans_id']."'><button class='submitq'>Cancel</button></a></td>";
//             <a rel='facebox' href='../request_addMC.php?trans_id=" . $rs_rec['trans_id'] . "_".$rs_trans['detail_id']."_" . $_GET['from'] . "_" . $_GET['to'] . "'><button class='submitq'>AddMC</button></a>";
    echo "</tr>";
}
echo "</table>";
?>