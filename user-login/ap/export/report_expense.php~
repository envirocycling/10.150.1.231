<?php

include '../config.php';

$sql_pay = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $_GET['bank'] . "%' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')");

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
header("Content-Disposition: attachment;filename=expense_report.xls");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

xlsWriteLabel(0, 0, "BANK ID");
xlsWriteLabel(0, 1, "CV #");
xlsWriteLabel(0, 2, "CV DATE");
xlsWriteLabel(0, 3, "VENDOR NAME");
xlsWriteLabel(0, 4, "PARTICULARS");
xlsWriteLabel(0, 5, "");
xlsWriteLabel(0, 6, "");
xlsWriteLabel(0, 7, "AMOUNT");
xlsWriteLabel(0, 8, "");
xlsWriteLabel(0, 9, "STATUS");
$xlsRow = 1;
while ($rs_pay = mysql_fetch_array($sql_pay)) {
    xlsWriteLabel($xlsRow, 0, $rs_pay['bank_code']);
    xlsWriteLabel($xlsRow, 1, $rs_pay['cheque_no']);
    xlsWriteLabel($xlsRow, 2, $rs_pay['date']);
    xlsWriteLabel($xlsRow, 3, $rs_pay['cheque_name']);
    if ($rs_pay['type'] == 'supplier') {
        xlsWriteLabel($xlsRow, 4, 'PAPER BUYING');
    } else {
        xlsWriteLabel($xlsRow, 4, 'OTHER PAYMENTS');
    }

    xlsWriteLabel($xlsRow, 5, '');
    xlsWriteLabel($xlsRow, 6, '');
    xlsWriteLabel($xlsRow, 7, $rs_pay['grand_total']);
    xlsWriteLabel($xlsRow, 8, '');
    if ($rs_pay['status'] == 'cancelled') {
        $remarks = 'CANCELLED';
    } else {
        $remarks = '';
    }
    xlsWriteLabel($xlsRow, 9, $remarks);
    $xlsRow++;
}
xlsEOF();

echo "<script>
    alert('Exported Successfully...');
window.history.back();
</script>
"
?>
