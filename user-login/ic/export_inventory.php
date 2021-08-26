<link rel="stylesheet" type="text/css" href="css/table.css" media="screen" />
<script type="text/javascript">             var tableToExcel = (function () {
        var uri = 'data:application/vnd.ms-excel;base64,'
                , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head>[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]</head><body><table>{table}</table></body></html>'
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
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
<?php
include('config.php');
$delivered_to = $_POST['delivered_to'];
$from = $_POST['from'];
$to = $_POST['to'];
$branch = $_POST['branch'];


//echo "SELECT * from scale_outgoing WHERE date between '$from' and '$to' and branch_id like '%$branch%' and dt_id like '%$delivered_to%'";
?>
<table class="CSSTableGenerator" id="example">
    <tr>
        <td>Str No</td>
        <td>Supplier Id</td>
        <td>Supplier Name</td>
        <td>Plate No</td>
        <td>Grade</td>
        <td>Weight</td>
        <td>Branch Delivered</td>
        <td>Date Delivered</td>
        <td>Month Delivered</td>
        <td>Year Delivered</td>
        <td>Delivered To</td>
    </tr>
    <?php
    $result = mysql_query("SELECT * from scale_outgoing WHERE date between '$from' and '$to' and branch_id like '%$branch%' and dt_id like '%$delivered_to%'") or die(mysql_error());

    while ($row = mysql_fetch_array($result)) {

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "'") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);

//        $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $row['dt_id'] . "'");
//        $rs_dt = mysql_fetch_array($sql_dt);

        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $row['branch_id'] . "'");
        $rs_branch = mysql_fetch_array($sql_branch);

        $branch_ = $rs_branch['branch_name'];

        $supplier_name = $select_supplier_row['supplier_name'];
        $plate_number = $row['plate_number'];
        $str_no = $row['str_no'];

        $select = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());
        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];

            if ($material_ids == 'CHIPBOARD') {
                $material_ids = 'CB';
            }

            $date_to_ex = $select_row['date_in'];
            $text_ex = explode('/', $date_to_ex);
            $text_date_pre = $text_ex[1];
            $tex_date = mysql_query("SELECT * from tbl_month Where number='$text_date_pre'") or die(mysql_error());
            $tex_date_row = mysql_fetch_array($tex_date);
            $text_date = $tex_date_row['text'];

            $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "'") or die(mysql_error());
            $select_dt_row = mysql_fetch_array($select_dt);

            $dt_id = $select_dt_row['name'];
            ?>
            <tr>
                <td><?php echo $str_no; ?></td>
                <td><?php echo $select_supplier_row['supplier_id']; ?></td>
                <td><?php echo $supplier_name; ?></td>
                <td><?php echo $plate_number; ?></td>
                <td><?php echo $material_ids; ?></td>
                <td><?php echo $select_row['net_weight']; ?></td>
                <td><?php echo $branch_; ?></td>
                <td><?php echo $select_row['date_in']; ?></td>
                <td><?php echo $text_date; ?></td>
                <td><?php echo $text_ex[0]; ?></td>
                <td><?php echo $dt_id; ?></td>
            </tr>
            <?php
        }
    }
    ?>
</table>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">