<?php

date_default_timezone_set("Asia/Singapore");
	
include './user-login/branch-head/configTPTS.php';

$page = $_SERVER['PHP_SELF'];
$sec = "300";

header("Refresh: $sec; url=$page");
		
if($_GET['scale_id']) {

	$scale_id = $_GET['scale_id'];	
	mysql_query("UPDATE scale SET up='1' WHERE scale_id = '$scale_id' ") or die (mysql_error());

}
?>
<html>
<head>
<title>PSS BACKGROUND PROCESS</title>
</head>
<center>
    <table width="90%" height="200">
        <tr height="15px">
            <td align="center"><h1>Updating PSS Receipts</h1><br />
                <img src="images/ajax-loader.gif">
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
</html>

<?php

$url = 'http://localhost/pss_update_process.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


if (200 == $retcode) {


	$today = date('Y/m/d');


	$sql_rec = mysql_query("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE scale.company_id='1' and scale.status ='COMPLETED' and (scale.date>='2019/05/01' and scale.date<='$today') and supplier.owner='EFI' and up='0' order by scale_id asc LIMIT 1");

	if(mysql_num_rows($sql_rec) > 0) {
		
		echo "<form action='/pss_update_process.php' method='POST' name='myForm'>";		
		
		while ($rs_rec = mysql_fetch_array($sql_rec)) {

		    $scale_id = $rs_rec['scale_id'];
		    //$date = '';
		    //$plate_no = '';
		    //$container = '';
		    //$id_no = '';
		    //$rider = '';
		    //$invoice = '';
		    //$ws_no = '';
		    //$str_no = '';
		    //$rm_type = '';
		    //$weight = '';
		    //$bales = '';
		    //$location = '';
		    //$average_weight = '';

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

		    if(mysql_num_rows($sql_details) > 0) {

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

			       	    echo "<input type='hidden' name='scale_id' value='$scale_id'>";
				    echo "<input type='hidden' name='date' value='$date'>";
				    echo "<input type='hidden' name='plate_no' value='$plate_no'>";
				    echo "<input type='hidden' name='container' value='$container'>";
				    echo "<input type='hidden' name='id_no' value='$id_no'>";
			       	    echo "<input type='hidden' name='rider' value='$rider'>";
			       	    echo "<input type='hidden' name='invoice' value='$invoice'>";
			       	    echo "<input type='hidden' name='ws_no' value='$ws_no'>";
				    echo "<input type='hidden' name='str_no' value='$str_no'>";
			       	    echo "<input type='hidden' name='rm_type' value='$_rm_type'>";
			       	    echo "<input type='hidden' name='weight' value='$weight'>";
			      	    echo "<input type='hidden' name='bales' value='$bales'>";
			       	    echo "<input type='hidden' name='location' value='$location'>";
			       	    echo "<input type='hidden' name='average_weight' value='$average_weight'>";

				    echo "<br>";

			     }
		    } else { ?>

			<script>
				window.top.location.href = "http://10.151.16.231/pss_update.php?scale_id=<?php echo $scale_id; ?>";
			</script>
		
		    <?php }


		}

		echo "</form>";
		echo "<script>document.myForm.submit();</script>";

	}
}else {
 
	$page = $_SERVER['PHP_SELF'];
	$sec = "300";
	header("Refresh: $sec; url=$page");

}
	
?>
