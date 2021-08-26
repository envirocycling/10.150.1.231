
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
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
</style>
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
<?php
include '../config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
 <th class="data">Date</th>
            <th class="data">Cheque #</th>
            <th class="data">Voucher #</th>
            <th class="data">Payee</th>
            <th class="data">Total Expense</th>
            <th class="data">Supplier</th>
            <th class="data">Remarks</th>
</tr>
        </thead>';
if (isset($_GET['from'])) {
    $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code!='SBC' and status='cancelled' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
} else {
    $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code!='SBC' and status='cancelled' and date>='" . date("Y/m/d") . "' and date<='" . date("Y/m/d") . "'");
}
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    echo "<tr>";
    echo "<td>" . $rs_paid['date'] . "</td>";
    echo "<td>" . $rs_paid['cheque_no'] . "</td>";
    echo "<td>" . $rs_paid['voucher_no'] . "</td>";
    echo "<td>" . $rs_paid['cheque_name'] . "</td>";
    echo "<td>" . $rs_paid['grand_total'] . "</td>";
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_paid['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
    echo "<td>" . $rs_paid['remarks'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>