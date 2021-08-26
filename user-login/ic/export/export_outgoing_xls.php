<?php
include '../config.php';
$sql_out = mysql_query("SELECT * FROM scale_outgoing WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
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
header("Content-Disposition: attachment;filename=outgoing.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0,0,"date");
xlsWriteLabel(0,1,"priority_no");
xlsWriteLabel(0,2,"trucking_name");
xlsWriteLabel(0,3,"str");
xlsWriteLabel(0,4,"plate_no");
xlsWriteLabel(0,5,"material");
xlsWriteLabel(0,6,"net_weight");
$xlsRow = 1;
while ($rs_out = mysql_fetch_array($sql_out)) {
    $sql_trucking = mysql_query("SELECT * FROM trucking WHERE trucking_id='" . $rs_out['trucking_id'] . "'");
    $rs_trucking = mysql_fetch_array($sql_trucking);
    $sql_trans = mysql_query("SELECT * FROM scale_outgoing_details WHERE trans_id='".$rs_out['trans_id']."'");
    while($rs_trans = mysql_fetch_array($sql_trans)) {
        xlsWriteLabel($xlsRow,0,$rs_out['date']);
        xlsWriteLabel($xlsRow,1,$rs_out['priority_no']);
        xlsWriteLabel($xlsRow,2,$rs_trucking['trucking_name']);
        xlsWriteLabel($xlsRow,3,$rs_trans['str']);
        xlsWriteLabel($xlsRow,4,$rs_out['plate_number']);
        $sql_material = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_trans['material_id'] . "'");
        $rs_material = mysql_fetch_array($sql_material);
        xlsWriteLabel($xlsRow,5,$rs_material['code']);
        xlsWriteLabel($xlsRow,6,$rs_trans['net_weight']);
        $xlsRow++;
    }
}
xlsEOF();

echo "<script>
    alert('Exported Successfully...');
window.history.back();
</script>
"




?>