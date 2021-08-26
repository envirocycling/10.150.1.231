
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
                    <script type="text/javascript">             var tableToExcel = (function () {
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
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
<?php
include '../config.php';
$delivered_to = $_GET['delivered_to'];
$from = $_GET['from'];
$to = $_GET['to'];
$branch = $_GET['branch'];

$_total = 0;
//echo "SELECT * from scale_outgoing WHERE date between '$from' and '$to' and branch_id like '%$branch%' and dt_id like '%$delivered_to%'";
?>
<table class="data display datatable" id="example">
    <thead>
        <tr class="data">
            <th class="data">Date</th>
            <th class="data">Supplier Name</th>
            <th class="data">Branch</th>
            <th class="data">Plate No</th>
            <th class="data">Str No</th>
            <th class="data">TR No</th>
            <th class="data">OR No</th>
            <th class="data">FT</th>
            <th class="data">EXP No</th>
            <th class="data">Delivered To</th>
            <th class="data">Series No</th>
            <th class="data">Grade</th>
            <th class="data">Weight</th>
            <th class="data">Remarks</th>
        </tr>
    </thead>
    <?php
    if ($delivered_to == 'BOTH') {
        $result = mysql_query("SELECT * from scale_outgoing WHERE date between '$from' and '$to' and (dt_id ='1' or dt_id ='2')") or die(mysql_error());
    } else if ($branch == '') {
        $result = mysql_query("SELECT * from scale_outgoing WHERE date between '$from' and '$to' and dt_id like '%$delivered_to%'") or die(mysql_error());
    } else {
        $result = mysql_query("SELECT * from scale_outgoing WHERE date between '$from' and '$to' and branch_id like '%$branch%' and dt_id like '%$delivered_to%'") or die(mysql_error());
    }

    while ($row = mysql_fetch_array($result)) {

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "'") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);

        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $row['branch_id'] . "'");
        $rs_branch = mysql_fetch_array($sql_branch);

        $branch_ = $rs_branch['branch_name'];

        $supplier_name = $select_supplier_row['supplier_name'];
        $plate_number = $row['plate_number'];
        $str_no = $row['str_no'];
        $tr_no = $row['tr_no'];
        $series_no = $row['series_no'];

        $select = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());
        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];

            $date_to_ex = $select_row['date_in'];
            $text_ex = explode('/', $date_to_ex);
            $text_date_pre = $text_ex[1];
            //$tex_date = mysql_query("SELECT * from tbl_month Where number='$text_date_pre'") or die(mysql_error());
            //$tex_date_row = mysql_fetch_array($tex_date);
          //  $text_date = $tex_date_row['text'];
		  	$text_date = date('F', strtotime($date_to_ex));

            $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "'") or die(mysql_error());
            $select_dt_row = mysql_fetch_array($select_dt);

            $dt_id = $select_dt_row['name'];

            echo "<tr>";
            echo "<td>" . $row['date'] . "</td>";
            echo "<td>$supplier_name</td>";
            echo "<td>$branch_</td>";
            echo "<td>$plate_number</td>";
            echo "<td>$str_no</td>";
            echo "<td>$tr_no</td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td>$dt_id</td>";
            if ($series_no == '') {
                echo "<td>$str_no</td>";
            } else {
                echo "<td>$series_no</td>";
            }
            echo "<td>$material_ids</td>";
            echo "<td>" . $select_row['corrected_weight'] . "</td>";
            echo "<td>" . $select_row['remarks'] . "</td>";
            echo "</tr>";
	
		
           $_total += (int) $select_row['corrected_weight'];

        }
    }
    ?>

    <tr class="data" style="background: yellow;">
        <th class="data">Total</th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"></th>
        <th class="data"><?php echo $_total ?></th>
        <th class="data"></th>
    </tr>
</table>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
