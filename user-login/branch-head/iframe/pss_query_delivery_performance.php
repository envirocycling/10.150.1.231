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

<script type="text/javascript">
    var tableToExcel = (function () {

        var uri = 'data:application/vnd.ms-excel;base64,';

        var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';

        var base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
        }
        
        var format = function (s, c) {
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
<input type="button" onclick="tableToExcel('example', 'Delivery Performance')" value="Export XLS">
<?php
// include '../config.php';
// $delivered_to = $_GET['delivered_to'];
// $from = $_GET['from'];
// $to = $_GET['to'];
// $branch = $_GET['branch'];


//echo "SELECT * from scale_outgoing WHERE date between '$from' and '$to' and branch_id like '%$branch%' and dt_id like '%$delivered_to%'";

include '../config.php';

$total_weight = 0;
$total_less_weight = 0;
$corrected_weight = 0;

?>
<table class="data display datatable" id="example">
    <thead>
        <tr class="data">
            <th class="data" width="60">Date</th>
            <th class="data" width="40">Plate #</th>
            <th class="data" width="40">Container</th>
            <th class="data" width="40">ID #</th>
            <th class="data" width="20">Rider</th>
            <th class="data" width="40">Invoice</th>
            <th class="data" width="80">WS #</th>
            <th class="data" width="80">STR #</th>
            <th class="data" width="80">RM Type</th>
            <th class="data">Weight MT</th>            
            <th class="data">Bales</th>
            <th class="data">Location</th>
            <th class="data" width="40">Ave.</th>
            <th class="data" width="40">Delivery Code</th>
        </tr>
    </thead>
    <tbody>

        <?php

        // $sql_rec = mysql_query("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') and scale.status='COMPLETED' and scale.date>='" . $_GET['from'] . "' and scale.date<='" . $_GET['to'] . "' and supplier.owner='EFI'");

        $from = $_GET['from'];
        $to = $_GET['to'];

        $sql_rec = mysql_query("SELECT * FROM pss_delivery WHERE date >= '$from' and date <= '$to';");

        ?>

        <?php while ($rs_rec = mysql_fetch_array($sql_rec)): ?> 
            <tr class='data'>
                <td class='data'><?php echo $rs_rec['date']; ?></td>
                <td class='data'><?php echo $rs_rec['plate_no']; ?></td>
                <td class='data'><?php echo $rs_rec['container']; ?></td>
                <td class='data'><?php echo $rs_rec['id_no']; ?></td>
                <td class='data'><?php echo $rs_rec['rider']; ?></td>
                <td class='data'><?php echo $rs_rec['invoice']; ?></td>
                <td class='data'><?php echo $rs_rec['ws_no']; ?></td>
                <td class='data'><?php echo $rs_rec['str_no']; ?></td>
                <td class='data'><?php echo $rs_rec['rm_type']; ?></td>
                <td class='data'><?php echo $rs_rec['weight']; ?></td>
                <td class='data'><?php echo $rs_rec['bales']; ?></td>
                <td class='data'><?php echo $rs_rec['location']; ?></td>
                <td class='data'><?php echo $rs_rec['ave_weight_bale']; ?></td>
                <td class='data'><?php echo $rs_rec['code']; ?></td>
            </tr>
        <?php endwhile;?>

    </tbody>
</table>
<input type="button" onclick="tableToExcel('example', 'Delivery Performance')" value="Export XLS">

