<?php
include('config.php');
$page = $_SERVER['PHP_SELF'];
$sec = "10";
header("Refresh: $sec; url=$page");
?>

<center>
    <table width="90%" height="200">
        <tr height="15px">
            <td align="center"><h1>Updating Data EFI Pampanga System.</h1><br />
                <img src="images/update.gif">
                <br />
                <br />
            </td>
        </tr>
        <tr height="15px">
            <td align="center"><font color='red'><h1>Please don't close this window.</h1></font><br />
            </td>
        </tr>
    </table>
</center>
<?php

$dir = "ftp_data/";
$dh = opendir($dir);
while (false !== ($filename = readdir($dh))) {
    $files[] = $filename;
} 
rsort($files);

$count = count($files);

$c = 0;

$duplicate = 0;

$data_array = array();

while ($c < 1) {
    if ($files[$c] != '..' && $files[$c] != '.') {

   		$file = fopen("ftp_data/$files[$c]", "r");

        $ctr = 0;
        while (!feof($file)) {
       	 $data_array[$ctr] = fgets($file);
            $ctr++;
        }
		
      	$sup_data = preg_split("/[-]/", $data_array[2]);
        $dr_number = substr($data_array[1], 0, -2);
        $date = substr($data_array[0], 0, -2);
        $supplier_name = $sup_data[0];

        $sql_sup = mysql_query("SELECT * FROM supplier WHERE supplier_id='$supplier_name'");
        $rs_sup = mysql_fetch_array($sql_sup);


        if (!empty($sup_data[2])) {
            $branch = substr($sup_data[2], 0, -2);
        } else {
            $branch = 'PAMPANGA';
        }

        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_name like '%$branch%'");
        $rs_branch = mysql_fetch_array($sql_branch);

       $plate_number = substr($data_array[3], 0, -2);
        $delivered_to = substr($data_array[4], 0, -2);
        $str_no = substr($data_array[1], 0, -2);

        $query_dlvrdto = mysql_query("SELECT * from delivered_to WHERE name='$delivered_to'") or die(mysql_error());
        $query_dlvrdto_row = mysql_fetch_array($query_dlvrdto);
        $delivered_to_id = $query_dlvrdto_row['dt_id'];

        $str_exist = mysql_query("SELECT * FROM scale_outgoing WHERE str_no ='$str_no'") or die(mysql_error());
        $str_exist_rs = mysql_fetch_array($str_exist);

        if ($str_no == '') {
            $str_no_new = $str_no;
        } else if (mysql_num_rows($str_exist) > 0 && $str_exist_rs['dt_id'] == $delivered_to_id) {
            $duplicate++;
        } else if (mysql_num_rows($str_exist) > 0 && $str_exist_rs['dt_id'] != $delivered_to_id) {
            $str_no_new = $str_no . 'B';
        } else {
            $str_no_new = $str_no;
        }

        if ($duplicate > 0) {
            fclose($file);
            $move_from = "ftp_data/" . $files[$c];
            $move_to = "ftp_duplicate/" . $files[$c];

            rename($move_from, $move_to);

/*            echo "<script>";
            echo "alert('Duplicated');";
            echo "</script>";*/
        } else {
            if ($branch == 'PAMPANGA') {
                $scale_rec = mysql_query("INSERT INTO `scale_receiving`(`str_no`, `date`, `supplier_id`, `dt_id`, `plate_number`)
        VALUES ('$str_no_new','$date','" . $rs_sup['id'] . "','$delivered_to_id','$plate_number')")or die(mysql_error());

                $get_trans_id2 = mysql_query("SELECT max(trans_id) FROM scale_receiving") or die(mysql_error());
                $get_trans_id_row2 = mysql_fetch_array($get_trans_id2);
                $trans_id_rec = $get_trans_id_row2['max(trans_id)'];

                $scale_out = mysql_query("INSERT INTO `scale_outgoing`(`str_no`, `date`, `supplier_id`, `branch_id`, `dt_id`, `plate_number`,`rec_trans_id`)
        VALUES ('$str_no_new','$date','" . $rs_sup['id'] . "','" . $rs_branch['branch_id'] . "','$delivered_to_id','$plate_number','$trans_id_rec')")or die(mysql_error());
		
            } else {
                $scale_rec = mysql_query("INSERT INTO `scale_outgoing`(`str_no`, `date`, `supplier_id`, `branch_id`, `dt_id`, `plate_number`)
        VALUES ('$str_no_new','$date','" . $rs_sup['id'] . "','" . $rs_branch['branch_id'] . "','$delivered_to_id','$plate_number')")or die(mysql_error());
            }


            $c2 = 5;

            while ($c2 < $ctr) {
                if (!empty($data_array[$c2]) && !empty($data_array[$c2 + 7]) && !empty($data_array[$c2 + 8])) {
                    $wp_grade = substr($data_array[$c2], 0, -2);
                    $wp_grade = str_replace('-', '', $wp_grade);
                    $c2++;
                    $date_in = substr($data_array[$c2], 0, -2);
                    $c2 = $c2 + 4;
                    $gross = substr($data_array[$c2], 0, -2);
                    $c2++;
                    $tare = substr($data_array[$c2], 0, -2);
                    $c2++;
                    $weight = substr($data_array[$c2], 0, -2);
                    $c2++;
                    $bales = substr($data_array[$c2], 0, -2);
                    $c2++;
                    $remarks = substr($data_array[$c2], 0, -2);
                    $c2++;
                    if ($wp_grade == 'LCCB') {
                        $wp_grade = 'CHIPBOARD';
                    }

                    $get_trans_id = mysql_query("SELECT max(trans_id) FROM scale_outgoing") or die(mysql_error());
                    $get_trans_id_row = mysql_fetch_array($get_trans_id);
                    $trans_id_out = $get_trans_id_row['max(trans_id)'];

                    $get_trans_id2 = mysql_query("SELECT max(trans_id) FROM scale_receiving") or die(mysql_error());
                    $get_trans_id_row2 = mysql_fetch_array($get_trans_id2);
                    $trans_id_rec = $get_trans_id_row2['max(trans_id)'];

                    $get_mtrl_id = mysql_query("SELECT * FROM material WHERE code='$wp_grade'") or die(mysql_error());
                    $get_mtrl_id_row = mysql_fetch_array($get_mtrl_id);
                    $mtrl_id = $get_mtrl_id_row['material_id'];

                    if ($branch == 'PAMPANGA') {
                        $scale_rec_dtls = mysql_query("INSERT INTO `scale_receiving_details`(`trans_id`,`material_id`, `net_weight`,`corrected_weight`,`gross`,`tare`, `remarks`)
            VALUES ('$trans_id_rec','$mtrl_id','$weight','$weight', '$gross','$tare','$remarks')")or die(mysql_error());

                        $get_detail_id = mysql_query("SELECT max(detail_id) FROM scale_receiving_details WHERE trans_id='$trans_id_rec'");
                        $get_detail_id_row = mysql_fetch_array($get_detail_id);
                        $detail_id = $get_detail_id_row['max(detail_id)'];

                        $scale_out_dtls = mysql_query("INSERT INTO `scale_outgoing_details`(`trans_id`,`material_id`, `date_in` ,`net_weight`,`corrected_weight`,`gross`,`tare`, `bales`, `remarks`, `rec_detail_id`)
            VALUES ('$trans_id_out','$mtrl_id','$date_in','$weight','$weight','$gross','$tare','$bales','$remarks','$detail_id')")or die(mysql_error());
                    } else {

                        $scale_out_dtls = mysql_query("INSERT INTO `scale_outgoing_details`(`trans_id`,`material_id`, `date_in` ,`net_weight`,`corrected_weight`,`gross`,`tare`, `bales`, `remarks`)
            VALUES ('$trans_id_out','$mtrl_id','$date_in','$weight','$weight','$gross','$tare','$bales','$remarks')") or die(mysql_error());
                    }

                    if ($c2 < $ctr) {
                        $cc2 = $ctr - 8;
                        $date_out = substr($data_array[$cc2], 0, -2);
                        $cc2++;
                        $time_out = substr($data_array[$cc2], 0, -2);

                        $update_out = mysql_query("UPDATE scale_outgoing SET date_out='$date_out' WHERE trans_id='$trans_id_out'") or die(mysql_error());
                        $update_out_dtls = mysql_query("UPDATE scale_outgoing_details SET date_out='$date_out' WHERE trans_id='$trans_id_out'") or die(mysql_error());
                    }
                } else {
                    $c2++;
                }
            }

            fclose($file);
            $move_from = "ftp_data/" . $files[$c];
            $move_to = "ftp_uploaded/" . $files[$c];

            rename($move_from, $move_to);
/*            echo "<script>";
            echo "alert('Ok');";
            echo "</script>";*/
        }
    }
   $c++;
}
?>
