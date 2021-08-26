
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
<base target="_parent" />
<?php
include 'config.php';

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data">Date</th>
            <th class="data">Priority #</th>
            <th class="data">Supplier Name</th>
            <th class="data">Plate #</th>
            <th class="data">WP Grade</th>
            <th class="data">Weight</th>
            <th class="data">Unit Cost</th>
            <th class="data">Paper Buying</th>
</tr>
        </thead>';
$sql_paid = mysql_query("SELECT * FROM scale_receiving WHERE date>='".$_GET['from']."' and date<='".$_GET['to']."' and status='paid'");
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_paid['trans_id']."'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        echo "<tr>";
        echo "<td>" . $rs_paid['date'] . "</td>";
        echo "<td>".$rs_paid['priority_no']."</td>";
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs_paid['supplier_id']."'");
        $rs_sup = mysql_fetch_array($sql_sup);
        echo "<td>".$rs_sup['supplier_id']."_".$rs_sup['supplier_name']."</td>";
        echo "<td>".$rs_paid['plate_number']."</td>";
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$rs_details['material_id']."'");
        $rs_mat = mysql_fetch_array($sql_mat);
        echo "<td>".$rs_mat['code']."</td>";
        echo "<td>".$rs_details['corrected_weight']."</td>";
        echo "<td>".$rs_details['price']."</td>";
        echo "<td>".$rs_details['amount']."</td>";
        echo "</tr>";
    }
}
echo "</table>";
?>