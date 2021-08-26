<?php
include '../config.php';
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "' and status!='void'");
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
header("Content-Disposition: attachment;filename=receiving_tat.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0,0,"date");
xlsWriteLabel(0,1,"priority_no");
xlsWriteLabel(0,2,"supplier_name");
xlsWriteLabel(0,3,"plate_number");
xlsWriteLabel(0,4,"no_of_grades");
xlsWriteLabel(0,5,"actual_weight");
xlsWriteLabel(0,6,"arrival");
xlsWriteLabel(0,7,"start");
xlsWriteLabel(0,8,"finish");
xlsWriteLabel(0,9,"que_time");
xlsWriteLabel(0,10,"unload_time");
xlsWriteLabel(0,11,"total");
$xlsRow = 1;
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    xlsWriteLabel($xlsRow,0,$rs_rec['date']);
    xlsWriteLabel($xlsRow,1,$rs_rec['priority_no']);
    xlsWriteLabel($xlsRow,2,$rs_sup['supplier_id']."_".$rs_sup['supplier_name']);
    xlsWriteLabel($xlsRow,3,$rs_rec['plate_number']);
    $sql_count = mysql_query("SELECT count(detail_id),sum(corrected_weight) FROM scale_receiving_details WHERE trans_id='".$rs_rec['trans_id']."'");
    $rs_count = mysql_fetch_array($sql_count);
    xlsWriteLabel($xlsRow,4,$rs_count['count(detail_id)'] );
    xlsWriteLabel($xlsRow,5,$rs_count['sum(corrected_weight)']);
    xlsWriteLabel($xlsRow,6,$rs_rec['arrival_time']);
    xlsWriteLabel($xlsRow,7,$rs_rec['start_time']);
    xlsWriteLabel($xlsRow,8,$rs_rec['finish_time']);
    xlsWriteLabel($xlsRow,9,$rs_rec['queue_time']);
    xlsWriteLabel($xlsRow,10,$rs_rec['unload_time']);
    xlsWriteLabel($xlsRow,11,$rs_rec['total_time']);

//    xlsWriteLabel($xlsRow,0,1);
//    xlsWriteLabel($xlsRow,1,2);
//    xlsWriteLabel($xlsRow,2,3);
//    xlsWriteLabel($xlsRow,3,4);
//    xlsWriteLabel($xlsRow,4,5);
//    xlsWriteLabel($xlsRow,5,6);
//    xlsWriteLabel($xlsRow,6,7);
//    xlsWriteLabel($xlsRow,7,8);
//    xlsWriteLabel($xlsRow,8,9);
//    xlsWriteLabel($xlsRow,9,10);
//    xlsWriteLabel($xlsRow,10,11);
    $xlsRow++;

}
xlsEOF();

echo "<script>
    alert('Exported Successfully...');
window.history.back();
</script>
"




?>