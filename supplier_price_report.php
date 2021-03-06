<?php
ini_set('max_execution_time', 0);


/** Include PHPExcel */
require_once './Classes/PHPExcel.php';

include("config.php");

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("EFI-IT")
							 ->setLastModifiedBy("EFI-IT")
							 ->setTitle("supplier price")
							 ->setSubject("Supplier price")
							 ->setDescription("Supplier Prices")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Suppier Price");

$headerStyle = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
);

$headerStyleCell = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'eccc68')
    )
);

$headerStyleCell2 = array(
    'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
    ),
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => 'FF000000'),
        ),
    ),
    'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => '63cdda')
    )
);

// Worksheet TIPCO/MULTIPLY
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'SUPPLIER NAME');
$sheet->setCellValue('D1', "TIPCOMULTIPLY");
$sheet->setCellValue('D2', 'GRADE');
$sheet->setCellValue('E2', 'PRICE');
$sheet->setCellValue('F2', 'UPDATED DATE');

$sheet->mergeCells('A1:C2');
$sheet->mergeCells('D1:F1');

$sheet->getStyle("A1:C2")->applyFromArray($headerStyleCell);
$sheet->getStyle("D1:F1")->applyFromArray($headerStyleCell);
$sheet->getStyle("D2")->applyFromArray($headerStyleCell);
$sheet->getStyle("E2")->applyFromArray($headerStyleCell);
$sheet->getStyle("F2")->applyFromArray($headerStyleCell);

// TIPCO/MULTIPLY worksheet
$objPHPExcel->getActiveSheet()->setTitle("TIPCO-MULTIPLY-Price");

$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);

foreach(range('A', 'C') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%Pampanga%'");

$supplier_row_count = 3;

while ($rs_sup = mysql_fetch_array($sql_sup)) {

    $supplier_id = $rs_sup['id'];

    $query = "SELECT *  FROM supplier where id='$supplier_id'  ";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);

    $grade_array = array();
    $sql_grade = mysql_query("SELECT * FROM suppliers_price where supplier_id='$supplier_id' GROUP BY material_id");

    $_grade_num_rows = mysql_num_rows($sql_grade);

    if($_grade_num_rows > 0) {
        $sheet->setCellValue("A".$supplier_row_count, $rs_sup['supplier_name'].' - '. $supplier_id);
    } else {
        continue;
    }

    while ($rs_grade = mysql_fetch_array($sql_grade)) {
        array_push($grade_array, $rs_grade['material_id']);
    }

    $log_id = array();

    foreach ($grade_array as $grade) {
        $sql_date = mysql_query("SELECT * FROM suppliers_price where dt_id='1' and supplier_id='$supplier_id' and material_id='$grade' ORDER by date DESC") or die(mysql_error());
        $rs_date = mysql_fetch_array($sql_date);

        array_push($log_id, $rs_date['id']);
    }

    $query2 = "SELECT * FROM suppliers_price where dt_id='1' and supplier_id='$supplier_id' order by date desc ";
    $result2 = mysql_query($query2);

    $row_count = $supplier_row_count;

        while ($row = mysql_fetch_array($result2)) {

            foreach ($log_id as $id) {
        
                if ($id == $row['id']) {
                
                    if ($row['price'] > 0) {
                
                        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $row['material_id'] . "'");
                        $rs_mat = mysql_fetch_array($sql_mat);
                
                        $sheet->setCellValue("D".$row_count, $rs_mat['code']);
                        $sheet->setCellValue("E".$row_count, $row['price']);
                        $sheet->setCellValue("F".$row_count, $row['date']);

                        $sheet->getStyle("D".$row_count)->applyFromArray($headerStyle);
                        $sheet->getStyle("E".$row_count)->applyFromArray($headerStyle);
                        $sheet->getStyle("F".$row_count)->applyFromArray($headerStyle);
                
                        $row_count++;
                    }
                }
            }
        }

    unset($log_id);


    if($row_count > 100) {
        break;
    }

    $last = $row_count - 1;

    $sheet->mergeCells("A{$supplier_row_count}:C{$last}");
    $sheet->getStyle("A{$supplier_row_count}:C{$last}")->applyFromArray($headerStyle);

    $supplier_row_count = $row_count;
}

// Worksheet FSI
$objPHPExcel->createSheet();

$sheet = $objPHPExcel->setActiveSheetIndex(1);

$sheet->setCellValue('A1', 'SUPPLIER NAME');
$sheet->setCellValue('D1', 'FSI');
$sheet->setCellValue('D2', 'GRADE');
$sheet->setCellValue('E2', 'PRICE');
$sheet->setCellValue('F2', 'UPDATED DATE');

$sheet->mergeCells('A1:C2');
$sheet->mergeCells('D1:F1');

$sheet->getStyle("A1:C2")->applyFromArray($headerStyleCell2);
$sheet->getStyle("D1:F1")->applyFromArray($headerStyleCell2);
$sheet->getStyle("D2")->applyFromArray($headerStyleCell2);
$sheet->getStyle("E2")->applyFromArray($headerStyleCell2);
$sheet->getStyle("F2")->applyFromArray($headerStyleCell2);

// TIPCO/MULTIPLY worksheet
$objPHPExcel->getActiveSheet()->setTitle('FSI Price');

$sheet->getColumnDimension('D')->setAutoSize(true);
$sheet->getColumnDimension('E')->setAutoSize(true);
$sheet->getColumnDimension('F')->setAutoSize(true);

foreach(range('A', 'C') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

$sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%Pampanga%'");

$supplier_row_count = 3;

while ($rs_sup = mysql_fetch_array($sql_sup)) {

    $supplier_id = $rs_sup['id'];

    $query = "SELECT *  FROM supplier where id='$supplier_id'  ";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);

    $grade_array = array();
    $sql_grade = mysql_query("SELECT * FROM suppliers_price where supplier_id='$supplier_id' GROUP BY material_id");

    $_grade_num_rows = mysql_num_rows($sql_grade);

    if($_grade_num_rows > 0) {
        $sheet->setCellValue("A".$supplier_row_count, $rs_sup['supplier_name'].' - '. $supplier_id);
    } else {
        continue;
    }

    while ($rs_grade = mysql_fetch_array($sql_grade)) {
        array_push($grade_array, $rs_grade['material_id']);
    }

    $log_id = array();

    foreach ($grade_array as $grade) {
        $sql_date = mysql_query("SELECT * FROM suppliers_price where dt_id='3' and supplier_id='$supplier_id' and material_id='$grade' ORDER by date DESC") or die(mysql_error());
        $rs_date = mysql_fetch_array($sql_date);

        array_push($log_id, $rs_date['id']);
    }

    $query2 = "SELECT * FROM suppliers_price where dt_id='3' and supplier_id='$supplier_id' order by date desc ";
    $result2 = mysql_query($query2);

    $row_count = $supplier_row_count;

        while ($row = mysql_fetch_array($result2)) {

            foreach ($log_id as $id) {
        
                if ($id == $row['id']) {
                
                    if ($row['price'] > 0) {
                
                        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $row['material_id'] . "'");
                        $rs_mat = mysql_fetch_array($sql_mat);
                
                        $sheet->setCellValue("D".$row_count, $rs_mat['code']);
                        $sheet->setCellValue("E".$row_count, $row['price']);
                        $sheet->setCellValue("F".$row_count, $row['date']);

                        $sheet->getStyle("D".$row_count)->applyFromArray($headerStyle);
                        $sheet->getStyle("E".$row_count)->applyFromArray($headerStyle);
                        $sheet->getStyle("F".$row_count)->applyFromArray($headerStyle);
                
                        $row_count++;
                    }
                }
            }
        }

    unset($log_id);

    if($row_count > 100) {
        break;
    }

    $last = $row_count - 1;

    $sheet->mergeCells("A{$supplier_row_count}:C{$last}");
    $sheet->getStyle("A{$supplier_row_count}:C{$last}")->applyFromArray($headerStyle);

    $supplier_row_count = $row_count;
}

$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

// Redirect output to a client????????s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="supplier_price_report.xlsx"');

//die("test");
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');

exit;

