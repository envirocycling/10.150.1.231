<?php

date_default_timezone_set("Asia/Manila");
include "config.php";

$cashbond = 5200;
$amortization = 13541.60;
$tr = 32;

$years = array('2019','2020', '2021', '2022', '2023', '2024');
$months = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');

foreach ($years as $year) {

	foreach ($months as $month) {

		$query_amort = "INSERT INTO `truck_payment`
		 (`tr_id`, `payment_id`, `pay_name`, `type`, `amount`, `status`, `month`, `date`, `date_paid`)
		 VALUES
		 ($tr, 0, 'A/P- NT', 'amortization', $amortization, '', '{$year}-{$month}', '{$year}-{$month}-01 00:00:00', '0000-00-00 00:00:00');
		 ";

		mysql_query($query_amort) or die(mysql_error());

		$query_cb = "INSERT INTO `truck_payment`
		 (`tr_id`, `payment_id`, `pay_name`, `type`, `amount`, `status`, `month`, `date`, `date_paid`)
		 VALUES
		 ($tr, 0, 'SUPPLIER CASHBOND', 'cashbond', $cashbond, '', '{$year}-{$month}', '{$year}-{$month}-01 00:00:00', '0000-00-00 00:00:00');
		 ";

		mysql_query($query_cb) or die(mysql_error());

	}

}

echo "Success!!";

?>
