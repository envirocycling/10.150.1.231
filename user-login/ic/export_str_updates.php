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

$result = mysql_query("SELECT * from scale_outgoing WHERE date between '$from' and '$to'") or die(mysql_error());
?>
<table class="CSSTableGenerator" id="example">
    <tr>
        <td>DATE</td>
        <td>STR NO</td>
        <td>DELIVERED TO</td>
        <td>PLATE</td>
        <td>GRADE</td>
        <td>WEIGHT</td>

    </tr>
    <?php
    while ($row = mysql_fetch_array($result)) {

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "' And branch LIKE '%$branch%'") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);
        $branch_ = $select_supplier_row['branch'];

        $supplier_name = $select_supplier_row['supplier_name'];
        $plate_number = $row['plate_number'];
        $str_no = $row['str_no'];

        if ($delivered_to == 'ALL') {
            $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "' ") or die(mysql_error());
        } else {
            $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "' and name='$delivered_to'") or die(mysql_error());
        }
        $select_dt_row = mysql_fetch_array($select_dt);

        $dt_id = $select_dt_row['name'];


        $select = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());

        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];

            if (mysql_num_rows($select_supplier) > 0 && mysql_num_rows($select_dt) > 0) {


                $date = date("m/d/Y", strtotime($row['date']));
                ?>
                <tr>
                    <td><?php echo $date; ?></td>
                    <td><?php echo $str_no; ?></td>
                    <td><?php echo $dt_id; ?></td>
                    <td><?php echo $plate_number; ?></td>
                    <td><?php echo $material_ids; ?></td>
                    <td><?php echo $select_row['net_weight']; ?></td>
                </tr>

                <?php
            }
        }
    }
    ?>
</table>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
