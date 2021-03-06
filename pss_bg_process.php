<?php

$page = $_SERVER['PHP_SELF'];
$sec = "120";
header("Refresh: $sec; url=$page");

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Updating PSS</title>
	</head>
	<body>
	<center>
	    <table width="90%" height="200">
		<tr height="15px">
		    <td align="center"><h1>Updating PSS</h1><br />
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
	</body>
</html>



<?php

date_default_timezone_set("Asia/Singapore");

include './user-login/branch-head/configTPTS.php';

$today = date('Y/m/d');
$prevDays = date('Y/m/d',strtotime("-5 days"));

//$sql_rec = mysql_query("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') and scale.status='COMPLETED' and scale.date>='$prevDays' and scale.date<='$today' and supplier.owner='EFI'");

$sql_rec = mysql_query("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE scale.company_id='1' and scale.status='COMPLETED' and scale.date>='$prevDays' and scale.date<='$today' and supplier.owner='EFI'");

$data = array();

while ($rs_rec = mysql_fetch_array($sql_rec)) {

    //Date
    //Plate #
    //Container
    //ID #
    //Rider
    //Invoice
    //WS #
    //STR #
    //RM Type
    //Weight MT
    //Bales
    //Location
    //Ave. WT/Bale

    $scale_id = '';
    $date = '';
    $plate_no = '';
    $container = '';
    $id_no = '';
    $rider = '';
    $invoice = '';
    $ws_no = '';
    $str_no = '';
    $rm_type = '';
    $weight = '';
    $bales = '';
    $location = '';
    $average_weight = '';

    $sql_details = mysql_query("SELECT * FROM scale_details WHERE scale_id='" . $rs_rec['scale_id'] . "'");

    $sql_comp = mysql_query("SELECT * FROM company WHERE company_id='" . $rs_rec['company_id'] . "'");
    $rs_comp = mysql_fetch_array($sql_comp);

    $sql_sup = mysql_query("SELECT * FROM supplier WHERE supplier_id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);

    //WS#
    $ws = '';

    if ($rs_rec['ws_no'] != '0') {
        $ws = "WS" . sprintf("%06s", $rs_rec['ws_no']);
    }
    //end ws#

    while ($rs_details = mysql_fetch_array($sql_details)) {

        $com_id = $rs_details['com_id'];

        $sql_comm = mysql_query("SELECT * FROM commodity WHERE com_id='$com_id'");
        $rs_comm = mysql_fetch_array($sql_comm);

        //ID# and Rider
        $supp_arr = explode('-', strtoupper($rs_sup['name']));

        $supp_id = $supp_arr[0];

        array_shift($supp_arr);

        $supp = join('-', $supp_arr);
        //End ID# and Rider

        //Ave. Weight per Bale
        $_weight = $rs_details['net_weight'];
        $_bales = $rs_details['bales'];

        if (is_numeric($rs_details['bales'])) {
            $ave = round($_weight / $_bales);
        } else {
            $ave = '';
        }
        //End Ave. Weight per Bale

        //Convert date string to array
        $dateArr = explode('/', $rs_rec['scale_id']);

        $scale_id = $rs_rec['scale_id'];
        $date = $rs_rec['date'];
        $plate_no = strtoupper($rs_rec['plate_no']);
        $container = $rs_rec['contvan_no'];
        $id_no = $supp_id;
        $rider = $supp;
        $invoice = $rs_rec['tr_no'];
        $ws_no = $ws;
        $str_no = $rs_rec['str_no'];

        $_rm_type = $rs_comm['name'];

        $rm_typeArr = explode("-", $_rm_type);
        $rm_type = implode('', $rm_typeArr);

        $weight = $_weight;
        $bales = $_bales;
        $location = $rs_details['location'];
        $average_weight = $ave;


        // push to data array
        $temp_data = array(
            'scale_id' => $scale_id,
            'date' => $date,
            'plate_no' => $plate_no,
            'container' => $container,
            'id_no' => $id_no,
            'rider' => $rider,
            'invoice' => $invoice,
            'ws_no' => $ws_no,
            'str_no' => $str_no,
            'rm_type' => $rm_type,
            'weight' => $weight,
            'bales' => $bales,
            'location' => $location,
            'average_weight' => $average_weight,
        );

        array_push($data, $temp_data);

    }

}

//================ Insert to database =================//
include 'config.php';

foreach ($data as $item) {

    //initialize variables

    $scale_id = $item['scale_id'];
    $date = $item['date'];
    $plate_no = $item['plate_no'];
    $container = $item['container'];
    $id_no = $item['id_no'];
    $rider = $item['rider'];
    $invoice = $item['invoice'];
    $ws_no = $item['ws_no'];
    $str_no = $item['str_no'];
    $rm_type = $item['rm_type'];
    $weight = $item['weight'];
    $bales = $item['bales'];
    $location = $item['location'];
    $average_weight = $item['average_weight'];

    // check if already exist

    $pss_del_sql = mysql_query("SELECT * FROM pss_delivery WHERE scale_id='$scale_id';");
    $pss_del_res = mysql_fetch_array($pss_del_sql);

    if (!$pss_del_res) {

        //generate delivery code
        $last_row_query = mysql_query("SELECT code FROM pss_delivery WHERE date ='$date' ORDER BY id DESC LIMIT 1;");
        $last_row_res = mysql_fetch_array($last_row_query);

        //$_date = explode('/', $date);
        //year code
        //$year = $_date[0];
        //$year_code = substr($year, -2);
        //month code
        //$month_code = $_date[1];


	//date code
	$date_code = date('ymd', strtotime($date));

        if ($last_row_res) {

            //get code
            $asd = explode('-', $last_row_res['code']);
            $_day_code = (int)$asd[1];

            //increment
            $day_code = sprintf("%02d", ($_day_code + 1));
            $code = $date_code . '-' . $day_code;

        } else {

            //$day_code = $_date[2];
            //$code = $year_code . $month_code . '-' . $day_code . '001';
            $code = $date_code . '-01';

        }
        //end delivery code

        mysql_query("INSERT INTO `pss_delivery`(`scale_id`, `code`, `date`, `plate_no`, `container`, `id_no`, `rider`, `invoice`, `ws_no`, `str_no`, `rm_type`, `weight`, `bales`, `location`, `ave_weight_bale`) VALUES ('$scale_id','$code','$date','$plate_no','$container','$id_no','$rider','$invoice','$ws_no','$str_no','$rm_type','$weight','$bales','$location','$average_weight');") or die(mysql_error());

    }
}


/*
if($updateCodeRes) {
    header('Location: pss_bg_process.php');
}
*/

?>


