<?php
include '../config.php';
$sql_paid = mysql_query("SELECT * FROM scale_receiving WHERE date>='".$_GET['from']."' and date<='".$_GET['to']."' and status!='void'");

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
header("Content-Disposition: attachment;filename=paper_buying.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0,0,"date");
xlsWriteLabel(0,1,"priority_no");
xlsWriteLabel(0,2,"supplier_id");
xlsWriteLabel(0,3,"supplier_name");
xlsWriteLabel(0,4,"plate_no");
xlsWriteLabel(0,5,"wp_grade");
xlsWriteLabel(0,6,"weight");
xlsWriteLabel(0,7,"unit_cost");
xlsWriteLabel(0,8,"paper_buying");
xlsWriteLabel(0,9,"ts_fee");
xlsWriteLabel(0,10,"net_paper_buying");
$xlsRow = 1;
while ($rs_paid = mysql_fetch_array($sql_paid)) {

    $sql_ts_fee = mysql_query("SELECT price FROM ts_fee WHERE fee_id='".$rs_paid['ts_fee']."'");
    $rs_ts_fee = mysql_fetch_array($sql_ts_fee);
    $sql_total = mysql_query("SELECT sum(corrected_weight),count(trans_id) FROM scale_receiving_details WHERE trans_id='".$rs_paid['trans_id']."'");
    $rs_total = mysql_fetch_array($sql_total);
    $ts_fee = $rs_ts_fee['price']*$rs_total['count(trans_id)'];
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_paid['trans_id']."'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs_paid['supplier_id']."'");
        $rs_sup = mysql_fetch_array($sql_sup);
        $supplier_name = $rs_sup['supplier_id']."_".$rs_sup['supplier_name'];
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$rs_details['material_id']."'");

        $mul = $ts_fee*$rs_details['corrected_weight'];
        if ($rs_total['sum(corrected_weight)']=='' || $rs_total['sum(corrected_weight)'] == '0') {
            $total_ts_fee = 0;
        } else {
            $total_ts_fee = $mul/$rs_total['sum(corrected_weight)'];
        }
        $rs_mat = mysql_fetch_array($sql_mat);
        xlsWriteLabel($xlsRow,0,$rs_paid['date']);
        xlsWriteLabel($xlsRow,1,$rs_paid['priority_no']);
        xlsWriteLabel($xlsRow,2,$rs_sup['supplier_id']);
        xlsWriteLabel($xlsRow,3,$supplier_name);
        xlsWriteLabel($xlsRow,4,$rs_paid['plate_number']);
        xlsWriteLabel($xlsRow,5,$rs_mat['code']);
        xlsWriteLabel($xlsRow,6,$rs_details['corrected_weight']);
        xlsWriteLabel($xlsRow,7,$rs_details['price']);
        xlsWriteLabel($xlsRow,8,$rs_details['amount']);
        xlsWriteLabel($xlsRow,9,number_format($total_ts_fee,2));
        $net_paper_buying = $rs_details['amount']-$total_ts_fee;
        xlsWriteLabel($xlsRow,10,number_format($net_paper_buying,2));
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
