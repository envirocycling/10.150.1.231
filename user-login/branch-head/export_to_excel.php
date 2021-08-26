<?php

include('config.php');
$delivered_to = $_POST['delivered_to'];
$from = $_POST['from'];
$to = $_POST['to'];
$branch = $_POST['branch'];

$result = mysql_query("SELECT * from scale_outgoing WHERE date between '$from' and '$to'") or die(mysql_error());

function xlsBOF() {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
}

function xlsEOF() {
    echo pack("ss", 0x0A, 0x00);
    return;
}

function xlsWriteNumber($Row, $Col, $Value) {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
}

function xlsWriteLabel($Row, $Col, $Value) {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
;
header("Content-Disposition: attachment;filename=delivery_performance.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0, 0, "Date");
xlsWriteLabel(0, 1, "SUPPLIER");
xlsWriteLabel(0, 2, "BRANCH");
xlsWriteLabel(0, 3, "PLATE #");
xlsWriteLabel(0, 4, "STR #");
xlsWriteLabel(0, 5, "Delivered TO");
xlsWriteLabel(0, 6, "ITEMS");
xlsWriteLabel(0, 7, "QTY");
xlsWriteLabel(0, 8, "REMARKS");



$xlsRow = 1;

while ($row = mysql_fetch_array($result)) {

    $select_supplier = mysql_query("SELECT * from supplier WHERE supplier_id='" . $row['supplier_id'] . "' And branch LIKE '%$branch%'") or die(mysql_error());
    $select_supplier_row = mysql_fetch_array($select_supplier);
    $branch_ = $select_supplier_row['branch'];

    $supplier_name = $select_supplier_row['supplier_name'];
    $plate_number = $row['plate_number'];
    $str_no = $row['str_no'];

    if ($delivered_to == 'TIPCO-BOTH') {
        $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "' and (name='TIPCO' or name='MULTIPLY')") or die(mysql_error());
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

            xlsWriteLabel($xlsRow, 0, $date);
            xlsWriteLabel($xlsRow, 1, $supplier_name);
            xlsWriteLabel($xlsRow, 2, $branch_);
            xlsWriteLabel($xlsRow, 3, $plate_number);


            xlsWriteNumber($xlsRow, 4, $str_no);
            xlsWriteLabel($xlsRow, 5, $dt_id);

            xlsWriteLabel($xlsRow, 6, $material_ids);
            xlsWriteLabel($xlsRow, 7, $select_row['net_weight']);
            xlsWriteLabel($xlsRow, 8, $select_row['remarks']);

            $num = 1;
        } else {
            $num = 0;
        }

        if ($num == 1) {
            $xlsRow++;
        }
    }
}
xlsEOF();
echo "<script>
    alert('Exported Successfully...');
window.history.back();
</script>
";
?>