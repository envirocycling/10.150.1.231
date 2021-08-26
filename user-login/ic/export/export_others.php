<?php

include '../config.php';
$sql_rec = mysql_query("SELECT * FROM scale_others WHERE status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");

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
header("Content-Disposition: attachment;filename=others.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0, 0, "date");
xlsWriteLabel(0, 1, "priority_no");
xlsWriteLabel(0, 2, "name");
xlsWriteLabel(0, 3, "plate_no");
xlsWriteLabel(0, 4, "grade");
xlsWriteLabel(0, 5, "ts_fee");
xlsWriteLabel(0, 6, "remarks");
$xlsRow = 1;
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    xlsWriteLabel($xlsRow, 0, $rs_rec['date']);
    xlsWriteLabel($xlsRow, 1, $rs_rec['priority_no']);
    xlsWriteLabel($xlsRow, 2, $rs_rec['name']);
    xlsWriteLabel($xlsRow, 3, $rs_rec['plate_number']);
    $sql_count = mysql_query("SELECT count(detail_id) FROM scale_others_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
    $rs_count = mysql_fetch_array($sql_count);
    xlsWriteLabel($xlsRow, 4, $rs_count['count(detail_id)']);
    $sql_fee = mysql_query("SELECT * FROM ts_fee WHERE fee_id='" . $rs_rec['ts_fee'] . "'");
    $rs_fee = mysql_fetch_array($sql_fee);
    $ts_fee = $rs_fee['price'] * $rs_count['count(detail_id)'];
    xlsWriteLabel($xlsRow, 5, $ts_fee);
    xlsWriteLabel($xlsRow, 6, $rs_rec['remarks']);
    $xlsRow++;
}
xlsEOF();

echo "<script>
    alert('Exported Successfully...');
window.history.back();
</script>
"
?>