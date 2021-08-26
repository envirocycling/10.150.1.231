
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
<script type="text/javascript">
                    var tableToExcel = (function () {
                    var uri = 'data:application/vnd.ms-excel;base64,'
                            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                                                        , base64 = function (s) {
    return window.btoa(unescape(encodeURIComponent(s)))
                }
        , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }
        return function (table, name) {
            if (!table.nodeType)
                table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            window.location.href = uri + base64(format(template, ctx))
        }
    })()
</script>

<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .total {
        font-weight: bold;
        background-color: yellow;
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
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
<?php
include '../config.php';
$total_weight = 0;
$total_amount = 0;
$total_tot_ts_fee = 0;
$total_net_amount = 0;
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data">Date</th>
            <th class="data">STR #</th>
            <th class="data">Supplier Name</th>
            <th class="data">Plate #</th>
            <th class="data">WP Grade</th>
            <th class="data">Weight</th>
            <th class="data">Unit Cost</th>
            <th class="data">Paper Buying</th>
</tr>
        </thead>';
$sql_paid = mysql_query("SELECT * FROM scale_receiving WHERE date>='".$_GET['from']."' and date<='".$_GET['to']."' and status!='void'");
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_paid['trans_id']."'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        echo "<tr>";
        echo "<td>" . $rs_paid['date'] . "</td>";
        echo "<td>".$rs_paid['str_no']."</td>";
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs_paid['supplier_id']."'");
        $rs_sup = mysql_fetch_array($sql_sup);
        echo "<td>".$rs_sup['supplier_id']."_".$rs_sup['supplier_name']."</td>";
        echo "<td>".$rs_paid['plate_number']."</td>";
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$rs_details['material_id']."'");
        $rs_mat = mysql_fetch_array($sql_mat);
        echo "<td>".$rs_mat['code']."</td>";
        echo "<td>".$rs_details['corrected_weight']."</td>";
        $total_weight+=$rs_details['corrected_weight'];
        echo "<td>".$rs_details['price']."</td>";

        echo "<td>".$rs_details['amount']."</td>";
        $total_amount+=$rs_details['amount'];
        echo "</tr>";
    }
}
echo "<tr class='total'>";
echo "<td>!TOTAL!</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>$total_weight</td>";
echo "<td></td>";
echo "<td>".number_format($total_amount,2)."</td>";
echo "</tr>";
echo "</table>";
?>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
