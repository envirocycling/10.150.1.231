
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
            $(document).ready(function () {
    setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
    });</script>
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
    .total{
        background-color: yellow;
        font-weight: bold;
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
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
<?php
include '../config.php';

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
<th class="data">Bank ID</th>
<th class="data">CV #</th>
<th class="data">CV Date</th>
<th class="data">Payee</th>
<th class="data">Purpose</th>
<th class="data"></th>
<th class="data"></th>
<th class="data">Amount</th>
<th class="data"></th>
<th class="data">Status</th>
</tr>
        </thead>';
$total = 0;
$sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $_GET['bank'] . "%' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "') and status!='deleted'");
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    echo "<tr>";
    echo "<td>" . $rs_paid['bank_code'] . "</td>";
    echo "<td>" . $rs_paid['cheque_no'] . "</td>";
    echo "<td>" . $rs_paid['date'] . "</td>";
    echo "<td>" . $rs_paid['cheque_name'] . "</td>";
    echo "<td>Payment for " . $rs_paid['type'] . "</td>";
    echo "<td></td>";
    echo "<td></td>";
    if ($rs_paid['status'] == 'cancelled') {
        echo "<td></td>";
        echo "<td></td>";
        echo "<td>Cancelled</td>";
    } else {
        $total+=$rs_paid['grand_total'];
        echo "<td>" . $rs_paid['grand_total'] . "</td>";
        echo "<td></td>";
        echo "<td></td>";
    }
    echo "</tr>";
}
echo "<tr class='total'>";
echo "<td>z____</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>TOTAL FOR BDO</td>";
echo "<td>" . number_format($total, 2) . "</td>";
echo "<td></td>";
echo "<td></td>";
echo "</tr>";
echo "</table>";
?>
<br>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">