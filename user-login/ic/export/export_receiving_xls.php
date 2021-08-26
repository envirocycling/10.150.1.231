<?php
include '../config.php';
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
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
header("Content-Disposition: attachment;filename=receiving.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0,0,"date");
xlsWriteLabel(0,1,"priority_no");
xlsWriteLabel(0,2,"supplier_id");
xlsWriteLabel(0,3,"supplier_name");
xlsWriteLabel(0,4,"plate_no");
xlsWriteLabel(0,5,"wp_grade");
xlsWriteLabel(0,6,"net_weight");
xlsWriteLabel(0,7,"unit_cost");
xlsWriteLabel(0,8,"mc_percentage");
xlsWriteLabel(0,9,"mc_weight");
xlsWriteLabel(0,10,"corrected_weight");
$xlsRow = 1;
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup= mysql_fetch_array($sql_sup);
    $sql_trans = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_rec['trans_id']."'");
    while($rs_trans = mysql_fetch_array($sql_trans)) {
        xlsWriteLabel($xlsRow,0,$rs_rec['date']);
        xlsWriteLabel($xlsRow,1,$rs_rec['priority_no']);
        xlsWriteLabel($xlsRow,2,$rs_sup['supplier_id']);
        $supplier_name = $rs_sup['supplier_id']."_".$rs_sup['supplier_name'];
        xlsWriteLabel($xlsRow,3,$supplier_name);
        xlsWriteLabel($xlsRow,4,$rs_rec['plate_number']);
        $sql_material = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_trans['material_id'] . "'");
        $rs_material = mysql_fetch_array($sql_material);
        xlsWriteLabel($xlsRow,5,$rs_material['code']);
        xlsWriteLabel($xlsRow,6,$rs_trans['net_weight']);
        xlsWriteLabel($xlsRow,7,$rs_trans['price']);
        if ($rs_trans['weight_adj'] == 'moisture') {
            xlsWriteLabel($xlsRow,8,$rs_trans['mc_percentage']);
            xlsWriteLabel($xlsRow,9,$rs_trans['less_weight']);
        } else {
            xlsWriteLabel($xlsRow,8,'');
            xlsWriteLabel($xlsRow,9,'');
        }
        xlsWriteLabel($xlsRow,10,$rs_trans['corrected_weight']);
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