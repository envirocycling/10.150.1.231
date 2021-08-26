<?php

require_once('/var/www/html/paymentsystem/config/curl_api.php');
require_once('/var/www/html/paymentsystem/config/query_builder.php');

$today = date('Y/m/d');

$endPoint = "http://api.ims.efi.net.ph/api/sales";

$log_file = '/var/www/html/paymentsystem/tasks/logs/errors.txt';

$query = "

    SELECT

    o.trans_id,
    d.detail_id,
    o.str_no,
    o.plate_number,
    dt.name AS `delivered_to`,
    o.date,
    b.branch_name AS branch,
    m.code AS `wp_grade`,
    d.net_weight AS `net_wt`,
    d.corrected_weight AS `weight`,
    d.mc,
    d.dirt,
    d.remarks

    FROM scale_outgoing AS o
    INNER JOIN branches AS b on b.branch_id = o.branch_id
    INNER JOIN delivered_to AS dt ON dt.dt_id = o.dt_id
    INNER JOIN scale_outgoing_details AS d ON d.trans_id = o.trans_id
    INNER JOIN material AS m ON m.material_id = d.material_id
    WHERE (o.date >= '2020/04/01' AND o.date <= '$today') AND d.up_sales = 0;

";

$sales = fetch($query, null);

//dd_p($sales);

if (count($sales) > 0) {

	foreach ($sales as $sale) {

        $sale->trans_id = (int) $sale->trans_id;
        $sale->detail_id = (int) $sale->detail_id;
        $sale->dr_number = $sale->str_no;

        $actual = json_decode(json_encode($sale), true);


        //dd_p($actual);

		try {

			callApi("POST", $endPoint, $sale);

			$updateData = array(
				'up_sales' => 1,
			);

			update('scale_outgoing_details', $updateData, 'detail_id', $sale->detail_id);

		} catch (Exception $e) {

			file_put_contents($log_file, $e->getMessage() . PHP_EOL, FILE_APPEND | LOCK_EX);
			exit();

		}

	}

}

?>

