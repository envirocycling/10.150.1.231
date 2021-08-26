<?php
date_default_timezone_set("Asia/Singapore");
include "../config.php";

$date = $_POST['date'];
$str_no = $_POST['str_no'];
$tr_no = $_POST['tr_no'];
$delivered_by = $_POST['delivered_by'];
$plate_no = $_POST['plate_no'];
$delivered_to = $_POST['delivered_to'];
$wpgrade = $_POST['wpgrade'];
$gross = $_POST['gross'];
$tare = $_POST['tare'];
$weight = $_POST['weight'];
$mc = $_POST['mc'];
$dirt = $_POST['dirt'];
$netweight = $_POS['netweight'];
$bales = $_POST['bales'];
$end = $_POST['end'];

mysql_query("INSERT INTO temp_manual_encode_details (wp_grade, bales, gross, tare, weight, mc, dirt, netweight) VALUES ('$wpgrade', '$bales', '$gross', '$tare', '$weight', '$mc', '$dirt', '$netweight')") or die(mysql_error());

if ($end == 1) {
    $file_date = date('YmdHis') . "-" . $delivered_by . "-" . strtoupper($plate_no) . "-" . $str_no."-manual_encode";
    $file_name = "../../../ftp_data/" . $file_date;
    $myfile = fopen($file_name . ".txt", "w") or die("Unable to open file!". fopen_error());

    $date_delivered = date("Y/m/d", strtotime($date));

    $first_txt = $date_delivered . "\r\n" . $str_no . "\r\n" . $tr_no . "\r\n" . $delivered_by . "\r\n" . $plate_no . "\r\n" . $delivered_to . "\r\n";
    
    $sql_details = mysql_query("SELECT * from temp_manual_encode_details") or die(mysql_error());
    while($row_details = mysql_fetch_array($sql_details)){
        $time = date('h:i:s A');
        $com_txt .= $row_details['wp_grade']."\r\n". $date. "\r\n". $time . "\r\n". $date. "\r\n". $time . "\r\n" . $row_details['gross']."\r\n". $row_details['tare']."\r\n". $row_details['weight']."\r\n"."0"."\r\n".$row_details['bales']."\r\n". $row_details['mc']."\r\n". $row_details['dirt']."\r\n". "0"."\r\n". ""."\r\n";
        
        mysql_query("DELETE from temp_manual_encode_details WHERE id = ' ".$row_details['id']."' ") or die(mysql_error());
    }
   // $com_txt = '';
    $txt = $first_txt . "" . $com_txt;
    fwrite($myfile, $txt);
    fclose($myfile);
}
?>
