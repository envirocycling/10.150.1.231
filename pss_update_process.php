<?php

ini_set('max_execution_time', 1000000);
include 'config.php';


$scale_id = $_POST['scale_id'];
$date = $_POST['date'];
$plate_no = $_POST['plate_no'];
$container = $_POST['container'];
$id_no = $_POST['id_no'];
$rider = $_POST['rider'];
$invoice = $_POST['invoice'];
$ws_no = $_POST['ws_no'];
$str_no = $_POST['str_no'];
$rm_type = $_POST['rm_type'];
$weight = $_POST['weight'];
$bales = $_POST['bales'];
$location = $_POST['location'];
$average_weight = $_POST['average_weight'];

// check if already exist

$pss_del_sql = mysql_query("SELECT * FROM pss_delivery WHERE scale_id='$scale_id';");
$pss_del_rows = mysql_num_rows($pss_del_sql);

$url = 'localhost';

if($scale_id == '' || $scale_id == '0') { ?>
	<script>
		window.top.location.href = "http://<?php echo $url; ?>/pss_update.php?scale_id=<?php echo $scale_id; ?>";
	</script>
<?php }else {


    $success = 0;

    if($pss_del_rows > 0) {

        
        mysql_query("UPDATE pss_delivery SET `scale_id`='$scale_id', `date`='$date', `plate_no`='$plate_no', `container`='$container', `id_no`='$id_no', `rider`='$rider', `invoice`='$invoice', `ws_no`='$ws_no', `str_no`='$str_no', `rm_type`='$rm_type', `weight`='$weight', `bales`='$bales', `location`='$location', `ave_weight_bale`='$average_weight' WHERE scale_id='$scale_id';") or die(mysql_error());

        $success = 1;

    }else {

	
        //generate delivery code
        $last_row_query = mysql_query("SELECT code FROM pss_delivery WHERE date ='$date' ORDER BY id DESC LIMIT 1;");
        $last_row_res = mysql_fetch_array($last_row_query);

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


		
	mysql_query("INSERT INTO `pss_delivery`(`scale_id`, `code`, `date`, `plate_no`, `container`, `id_no`, `rider`, `invoice`, `ws_no`, `str_no`, `rm_type`, `weight`, `bales`, `location`, `ave_weight_bale`) VALUES ('$scale_id','$code','$date','$plate_no','$container','$id_no','$rider','$invoice','$ws_no','$str_no','$rm_type','$weight','$bales','$location','$average_weight');") or die(mysql_error());

	$success = 1;

    }
}


if ($success == 1) { ?>
    <script>
        window.top.location.href = "http://<?php echo $url; ?>/pss_update.php?scale_id=<?php echo $scale_id; ?>";
    </script>
<?php } else if ($success == 0) { ?>
    <script>
        alert("System Error.");
        window.top.location.href = "http://<?php echo $url; ?>/pss_update.php";
    </script>
<?php }

?>

