<?php

die("Maintenance Mode");

ini_set('display_errors', 1);
error_reporting(~0);


ini_set('memory_limit', '8192M');
session_start();
include('config.php');
$page = $_SERVER['PHP_SELF'];
$sec = "5";
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

        echo $files[$c] . "<br>";

        $file = fopen("/var/www/html/paymentsystem/ftp_data/$files[$c]", "r");

        $ctr = 0;

        while (!feof($file)) {
            $data_array[$ctr] = fgets($file);
            $ctr++;
        }

	    print_r($data_array);

        $date = trim(substr($data_array[0], 0, -1));
        $date_out = trim(substr($data_array[0], 0, -1));
        $dr_number = trim(substr($data_array[1], 0, -1));
        $str_no = trim(substr($data_array[1], 0, -1));
        $tr_no = substr($data_array[2], 0, -1);
        $sup_data = preg_split("/[-]/", trim($data_array[3]));

        $supplier_name = $sup_data[0];

        $sql_sup = mysql_query("SELECT * FROM supplier WHERE supplier_id='$supplier_name'");
        $rs_sup = mysql_fetch_array($sql_sup);


        //die(var_dump(strtoupper(substr($sup_data[2]))));


        if (!empty($sup_data[2])) {

            $branch = strtoupper(substr($sup_data[2], 0, -1));

	    //die(var_dump($branch));


            if($branch == 'MARCO RIVER') {
                $branch = 'PAMPANGA';
            }
		
    	    //added 02/08/2019 - jayson
    	    if($branch == 'SANFERNANDO') {
    		  $branch = 'SAN FERNANDO';
    	    }

	    if($branch == 'MARIBEL C. LALA') {
         	$branch = 'PAMPANGA';
            }

	    if($branch == 'ROAN CAPULON') {
		$branch = 'PAMPANGA';
	    }

        } else {
           $branch = 'PAMPANGA';
        }

	//die($branch);

        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_name like '%$branch%'");
        $rs_branch = mysql_fetch_array($sql_branch);

        $plate_number = trim(substr($data_array[4], 0, -1));
        $delivered_to = trim(substr($data_array[5], 0, -1));

        $query_dlvrdto = mysql_query("SELECT * from delivered_to WHERE name='$delivered_to'") or die(mysql_error());
        $query_dlvrdto_row = mysql_fetch_array($query_dlvrdto);
        $delivered_to_id = $query_dlvrdto_row['dt_id'];

        if($branch == 'PAMPANGA') {
         $str_exist = mysql_query("SELECT * FROM scale_receiving WHERE str_no ='$str_no'") or die(mysql_error());
         $str_exist_rs = mysql_fetch_array($str_exist);
        } else {
         $str_exist = mysql_query("SELECT * FROM scale_outgoing WHERE str_no ='$str_no'") or die(mysql_error());
         $str_exist_rs = mysql_fetch_array($str_exist);
        }    

        

        if ($str_no == '') {
            $str_no_new = $str_no;
        } else if (mysql_num_rows($str_exist) > 0 && strtoupper($str_exist_rs['dt_id']) == strtoupper($delivered_to_id)) {
           $duplicate++;
        } else if (mysql_num_rows($str_exist) > 0 && strtoupper($str_exist_rs['dt_id']) != strtoupper($delivered_to_id)) {
            $str_no_new = $str_no . 'B';
        } else {
            $str_no_new = $str_no;
        }

        if ($duplicate > 0) {

            fclose($file);

            $move_from = "ftp_data/" . $files[$c];
            $move_to = "ftp_duplicate/" . $files[$c];

            if (rename($move_from, $move_to)) {
                echo "successed";
            } else {
                print_r(error_get_last());
                echo "<br>";
                echo "failed";
            }
        } else {
            if ($branch == 'PAMPANGA') {

                if (mysql_query("INSERT INTO `scale_receiving`(`str_no`,`tr_no`,`date`,`date_out`,`supplier_id`, `dt_id`, `plate_number`) VALUES ('$str_no_new','$tr_no','$date','$date_out','" . $rs_sup['id'] . "','$delivered_to_id','$plate_number')") or die(mysql_error())) {
			echo "<br>";
			echo "Successfully Save to Receiving.";
		} else {
			echo "<br>";
			echo "Failed Save to Receiving.";
			echo "<br>";
			echo "INSERT INTO `scale_receiving`(`str_no`,`tr_no`,`date`,`date_out`,`supplier_id`, `dt_id`, `plate_number`)
                VALUES ('$str_no_new','$tr_no','$date','$date_out','" . $rs_sup['id'] . "','$delivered_to_id','$plate_number')";
		}	

                $get_trans_id2 = mysql_query("SELECT max(trans_id) FROM scale_receiving") or die(mysql_error());
                $get_trans_id_row2 = mysql_fetch_array($get_trans_id2);
                $trans_id_rec = $get_trans_id_row2['max(trans_id)'];

                if (mysql_query("INSERT INTO `scale_outgoing`(`str_no`,`tr_no`,`date`,`date_out`,`supplier_id`, `branch_id`, `dt_id`, `plate_number`,`rec_trans_id`)
                VALUES ('$str_no_new','$tr_no','$date','$date_out','" . $rs_sup['id'] . "','" . $rs_branch['branch_id'] . "','$delivered_to_id','$plate_number','$trans_id_rec')")) {
			echo "<br>";
			echo "Successfully Save to Outgoing.";
		} else {
			echo "<br>";
			echo "Failed Save to Outgoing.";
			echo "<br>";
			echo "INSERT INTO `scale_outgoing`(`str_no`,`tr_no`,`date`,`date_out`,`supplier_id`, `branch_id`, `dt_id`, `plate_number`,`rec_trans_id`)
                VALUES ('$str_no_new','$tr_no','$date','$date_out','" . $rs_sup['id'] . "','" . $rs_branch['branch_id'] . "','$delivered_to_id','$plate_number','$trans_id_rec')";
		}
            } else {
                if(mysql_query("INSERT INTO `scale_outgoing`(`str_no`, `tr_no`,`date`,`date_out`, `supplier_id`, `branch_id`, `dt_id`, `plate_number`) VALUES ('$str_no_new','$tr_no','$date','$date_out','" . $rs_sup['id'] . "','" . $rs_branch['branch_id'] . "','$delivered_to_id','$plate_number')") or die(mysql_error())) {
			echo "<br>";
			echo "Successfully Save to Outgoing.";
		} else {
			echo "<br>";
			echo "Failed Save to Outgoing.";
			echo "<br>";
			echo "INSERT INTO `scale_outgoing`(`str_no`, `tr_no`,`date`,`date_out`, `supplier_id`, `branch_id`, `dt_id`, `plate_number`)
                VALUES ('$str_no_new','$tr_no','$date','$date_out','" . $rs_sup['id'] . "','" . $rs_branch['branch_id'] . "','$delivered_to_id','$plate_number')";
		}
            }

            $c2 = 6;

	    //die(var_dump($data_array));

            while ($c2 < $ctr) {


                if (!empty($data_array[$c2]) && !empty($data_array[$c2 + 7])) {
                    
                    $wp_grade = trim(substr($data_array[$c2], 0, -1));
                    $wp_grade = str_replace('-', '', $wp_grade);
                    $c2++;
                    $date_in = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $weigh_in = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $date_out = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $weigh_out = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $gross = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $tare = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $weight = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $mc_perct = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $bales = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    //fsi addtl
                    $mc_bales = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $dirt_perct = trim(substr($data_array[$c2], 0, -1));
                    $c2++;
                    $dirt_kg = trim(substr($data_array[$c2], 0, -1));
                    $c2++;

                    $remarks = substr($data_array[$c2], 0, -1);
                    
                    $c2++;
                    
                    if ($wp_grade == 'LCCB') {
                        $wp_grade = 'CHIPBOARD';
                    }

                    $total_net_wt = 0;
                    $mc = 0;
                    $rej_perct = 0;
                    $rej_kg = 0;
                    $total_rej = 0;
                    $total_less = 0;

                    if ($mc_perct > 0) {
                        if ($mc_bales > 0 && ($bales > 0 && $bales != 'L')) {
                            $to_be_mc = ($weight / $bales) * $mc_bales;
                            $mc = $to_be_mc * (($mc_perct - 12) / 100);
                        } else {
                            $mc = $weight * (($mc_perct - 12) / 100);
                        }
                    }

                    $total_net_wt = $weight - $mc;

                    if ($dirt_perct > 0) {
                        $rej_perct = $total_net_wt * ($dirt_perct / 100);
                    }

                    if ($dirt_kg > 0) {
                        $rej_kg = $dirt_kg;
                    }

                    $total_rej = $rej_perct + $rej_kg;

                    $total_net_wt -= $total_rej;


                    $get_trans_id = mysql_query("SELECT max(trans_id) FROM scale_outgoing") or die(mysql_error());
                    $get_trans_id_row = mysql_fetch_array($get_trans_id);
                    $trans_id_out = $get_trans_id_row['max(trans_id)'];

                    $get_trans_id2 = mysql_query("SELECT max(trans_id) FROM scale_receiving") or die(mysql_error());
                    $get_trans_id_row2 = mysql_fetch_array($get_trans_id2);
                    $trans_id_rec = $get_trans_id_row2['max(trans_id)'];

                    $get_mtrl_id = mysql_query("SELECT * FROM material WHERE code='$wp_grade'") or die(mysql_error());
                    $get_mtrl_id_row = mysql_fetch_array($get_mtrl_id);
                    $mtrl_id = $get_mtrl_id_row['material_id'];

                    //die($branch);

                    if ($branch == 'PAMPANGA') {

                        mysql_query("INSERT INTO `scale_receiving_details`(`trans_id`, `material_id`, `date_in`, `weigh_in`, `date_out`, `weigh_out`, `gross`, `tare`, `net_weight`, `mc_perct`, `mc_bales`, `mc`, `dirt_perct`, `dirt_kg`, `dirt`, `corrected_weight`, `bales`, `remarks`)
                        VALUES ('$trans_id_rec', '$mtrl_id', '$date_in', '$weigh_in', '$date_out', '$weigh_out', '$gross', '$tare', '$weight', '$mc_perct', '$mc_bales', '$mc', '$dirt_perct', '$dirt_kg', '$total_rej', '$total_net_wt', '$bales', '$remarks')");

                        $get_detail_id = mysql_query("SELECT max(detail_id) FROM scale_receiving_details WHERE trans_id='$trans_id_rec'");
                        $get_detail_id_row = mysql_fetch_array($get_detail_id);
                        $detail_id = $get_detail_id_row['max(detail_id)'];

                        mysql_query("INSERT INTO `scale_outgoing_details`(`trans_id`,`material_id`, `date_in`,`weigh_in`,`date_out`,`weigh_out`,`gross`,`tare`,`net_weight`,`mc_perct`,`mc_bales`,`mc`,`dirt_perct`,`dirt_kg`,`dirt`,`corrected_weight`,`bales`,`remarks`,`rec_detail_id`)
                        VALUES ('$trans_id_out','$mtrl_id','$date_in','$weigh_in','$date_out','$weigh_out','$gross','$tare','$weight','$mc_perct','$mc_bales','$mc','$dirt_perct','$dirt_kg','$total_rej','$total_net_wt','$bales','$remarks','$detail_id')");
                    } else {
                        mysql_query("INSERT INTO `scale_outgoing_details`(`trans_id`,`material_id`, `date_in`,`weigh_in`,`date_out`,`weigh_out`,`gross`,`tare`,`net_weight`,`mc_perct`,`mc_bales`,`mc`,`dirt_perct`,`dirt_kg`,`dirt`,`corrected_weight`,`bales`,`remarks`)
                        VALUES ('$trans_id_out','$mtrl_id','$date_in','$weigh_in','$date_out','$weigh_out','$gross','$tare','$weight','$mc_perct','$mc_bales','$mc','$dirt_perct','$dirt_kg','$total_rej','$total_net_wt','$bales','$remarks')");
                    }
                } else {
                    $c2++;
                }
            }

            fclose($file);

            $sql_check = mysql_query("SELECT * FROM scale_outgoing WHERE str_no='$str_no_new'");
            $rs_check = mysql_num_rows($sql_check);

            $sql_check2 = mysql_query("SELECT * FROM scale_receiving WHERE str_no='$str_no_new'");
            $rs_check2 = mysql_num_rows($sql_check2);

            $check = $rs_check + $rs_check2;

            if ($check > 0) {
                $move_from = "ftp_data/" . $files[$c];
                $move_to = "ftp_uploaded/" . $files[$c];

                rename($move_from, $move_to);
            }
        }
    }
    $c++;
}
?>
