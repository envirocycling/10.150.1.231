<?php
include '../config.php';
$sql_out = mysql_query("SELECT * FROM scale_outgoing WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "' and status!='void'");
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
header("Content-Disposition: attachment;filename=outgoing_tat.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0,0,"date");
xlsWriteLabel(0,1,"priority_no");
xlsWriteLabel(0,2,"truckers_name");
xlsWriteLabel(0,3,"plate_number");
xlsWriteLabel(0,4,"no_of_grades");
xlsWriteLabel(0,5,"actual_weight");
xlsWriteLabel(0,6,"arrival");
xlsWriteLabel(0,7,"loading");
xlsWriteLabel(0,8,"finish");
xlsWriteLabel(0,9,"loading_tat");
xlsWriteLabel(0,10,"tat");
xlsWriteLabel(0,11,"departure");
xlsWriteLabel(0,12,"complete_tat");
xlsWriteLabel(0,13,"reason/remarks");
$xlsRow = 1;
while ($rs_out = mysql_fetch_array($sql_out)) {
    $sql_truck = mysql_query("SELECT * FROM trucking WHERE trucking_id='" . $rs_out['trucking_id'] . "'");
    $rs_truck = mysql_fetch_array($sql_truck);
    xlsWriteLabel($xlsRow,0,$rs_out['date']);
    xlsWriteLabel($xlsRow,1,$rs_out['priority_no']);
    xlsWriteLabel($xlsRow,2,$rs_truck['trucking_id']."_".$rs_truck['trucking_name']);
    xlsWriteLabel($xlsRow,3,$rs_out['plate_number']);
    $sql_count = mysql_query("SELECT count(detail_id),sum(net_weight) FROM scale_outgoing_details WHERE trans_id='".$rs_out['trans_id']."'");
    $rs_count = mysql_fetch_array($sql_count);
    xlsWriteLabel($xlsRow,4,$rs_count['count(detail_id)'] );
    xlsWriteLabel($xlsRow,5,$rs_count['sum(net_weight)']);
    xlsWriteLabel($xlsRow,6,$rs_out['arrival_time']);
    xlsWriteLabel($xlsRow,7,$rs_out['loading_time']);
    xlsWriteLabel($xlsRow,8,$rs_out['finish_time']);
    xlsWriteLabel($xlsRow,9,$rs_out['loading_tat']);
    xlsWriteLabel($xlsRow,10,$rs_out['tat']);
    xlsWriteLabel($xlsRow,11,$rs_out['departure_time']);
    xlsWriteLabel($xlsRow,12,$rs_out['complete_tat']);
    xlsWriteLabel($xlsRow,13,$rs_out['remarks']);

    $xlsRow++;

}
xlsEOF();

echo "<script>
    alert('Exported Successfully...');
window.history.back();
</script>
"




?>