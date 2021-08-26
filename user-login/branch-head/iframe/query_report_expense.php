
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

if ($_GET['type'] == 'all') {
    $type = "";
} else {
    $type = $_GET['type'];
}

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data">Date</th>
            <th class="data">Cheque #</th>
            <th class="data">Voucher #</th>
            <th class="data">Payee</th>
            <th class="data">Total Expense</th>
            <th class="data">Purpose</th>
            <th class="data">Supplier</th>
            <th class="data">Bank</th>
            <th class="data">Remarks</th>

</tr>
        </thead>';
$sql_paid = mysql_query("SELECT * FROM payment WHERE type like '%$type%' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    echo "<tr>";
    echo "<td>" . $rs_paid['date'] . "</td>";
    echo "<td>" . $rs_paid['cheque_no'] . "</td>";
    echo "<td>" . $rs_paid['voucher_no'] . "</td>";
    echo "<td>" . $rs_paid['cheque_name'] . "</td>";
    echo "<td>" . $rs_paid['grand_total'] . "</td>";
    echo "<td>Payment for " . $rs_paid['type'] . "</td>";
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_paid['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    if ($rs_sup['supplier_id']=='') {
        echo "<td>" . $rs_paid['cheque_name'] . "</td>";
    } else {
        echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
    }
    echo "<td>" . $rs_paid['bank_code'] . "</td>";
    if ($rs_paid['status'] == 'cancelled') {
        echo "<td>Cancelled</td>";
    } else {
        echo "<td></td>";
    }
    echo "</tr>";
}
echo "</table>";
?>