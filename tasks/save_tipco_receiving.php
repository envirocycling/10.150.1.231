<?php require_once '/var/www/html/paymentsystem/config/query_builder_tipco.php';

function dateOut($time_out, $date_out) {

	$_time_out = 0;

	$timeArr = array_reverse(explode(":", date("H:i:s", strtotime($time_out))));

	foreach ($timeArr as $key => $value) {

		if ($key > 2) {
			break;
		}

		$_time_out += pow(60, $key) * $value;
	}

	//$date_time = $time_out - 25200;

	if ($_time_out < 25200) {
		return date('Y/m/d', strtotime("-1 day", strtotime($date_out)));
	} else {
		return date("Y/m/d", strtotime($date_out));
	}
}

function getEfiSupplierId($id) {

	require '/var/www/html/paymentsystem/config/connection.php';

	//die("SELECT * FROM `supplier` WHERE `supplier_id` = {$id};");

	$q = $pdo->prepare("SELECT * FROM `supplier` WHERE `supplier_id` = {$id};");
	$q->execute();
	$response = $q->fetchAll();

	if (count($response) > 0) {
		return $response[0]->id;
	}

	return null;
}

function getEfiMaterialId($wp_grade) {

	require '/var/www/html/paymentsystem/config/connection.php';

	$code = str_replace('-', '', $wp_grade);

	if($code == 'LCCB') {
		$grade = 'CHIPBOARD';
	} else if($code == 'LCDLK' || $code == 'LCOCC_DLK') {
		$grade = 'LCOCC_TRIMMINGS';
	} else if($code == 'LCMW_COR') {
		$grade = 'LCMW';
	} else if($code == 'LCCORETUBE') {
		$grade = 'LCMW3';
	} else if($code == 'LCWL_T') {
		$grade = 'LCWL';
	} else if($code == 'LCWLB' || $code == 'LCWL_WB') {
		$grade = 'LCWL_WB';
	} else {
		$grade = $code;
	}


	$q = $pdo->prepare("SELECT * FROM `material` WHERE code = '{$grade}';");
	$q->execute();
	$response = $q->fetchAll();

	if (count($response) > 0) {
		return $response[0]->material_id;
	}

	return null;
}

function getDeliveredToId($name) {

	require '/var/www/html/paymentsystem/config/connection.php';

	$q = $pdo->prepare("SELECT * FROM `delivered_to` WHERE name = '{$name}';");
	$q->execute();
	$response = $q->fetchAll();

	if (count($response) > 0) {
		return $response[0]->dt_id;
	}

	return null;
}

function getBranchId($b) {

	require '/var/www/html/paymentsystem/config/connection.php';

	if ($b === 'SANFERNANDO') {
		$branch = 'San Fernando';
	} else {
		$branch = ucwords($b);
	}



	$q = $pdo->prepare("SELECT * FROM `branches` WHERE `branch_name` = '{$branch}';");
	$q->execute();
	$response = $q->fetchAll();

	//die(var_dump($response));

	if (count($response) > 0) {
		return (int) $response[0]->branch_id;
	}



	return 7;
}

function find($table, $conditions) {

	require '/var/www/html/paymentsystem/config/connection.php';

	$conditionsArray = array();

	foreach ($conditions as $key => $value) {
		$conditionsArray[] = "{$key}=:{$key}";
	}

	$consditionStr = implode(' AND ', $conditionsArray);

	$sql = "SELECT * FROM `{$table}` WHERE {$consditionStr}";

	$stmt = $pdo->prepare($sql);
	$stmt->execute($conditions);
	$results = $stmt->fetchAll();

	return (count($results) > 0);

}

function create($table, $data) {

	require '/var/www/html/paymentsystem/config/connection.php';

	$fieldsArray = array_keys($data);
	$valuesArray = array();

	foreach ($fieldsArray as $field) {
		$valuesArray[] = ":{$field}";
	}

	$fieldStr = implode(', ', $fieldsArray);
	$valueStr = implode(', ', $valuesArray);

	//die("INSERT INTO {$table} ({$fieldStr}) VALUES ({$valueStr})");
	

	$sql = "INSERT INTO {$table} ({$fieldStr}) VALUES ({$valueStr})";

	$stmt = $pdo->prepare($sql);

	$res = $stmt->execute($data);

	//die(var_dump($data));

	if ($res) {
		return $pdo->lastInsertId();
	}

	return $res;

}

function insertDetails($table, $data, $trans_id) {
	foreach ($data as $details) {
		$details['trans_id'] = $trans_id;
		create($table, $details);
	}
}

function updateTipco($scaleId) {
	update('scale', array('up' => 1), 'scale_id', $scaleId);
}


$queryScale = "
	SELECT scale.scale_id,
	scale.str_no,
	scale.tr_no,
 	scale.plate_no,
 	scale.time_out,
 	scale.date_out,
	supp.name as supplier_name,
	comp.name as company_name
	FROM `scale`
	INNER JOIN `supplier` as supp ON supp.supplier_id = scale.supplier_id
	INNER JOIN `company` as comp ON comp.company_id = scale.company_id
	WHERE
	 (((scale.company_id = '1' OR scale.company_id = '5') AND scale.status = 'COMPLETED' AND scale.linked = 1)
	 OR (scale.company_id = '2' AND scale.status = 'COMPLETED')) AND
	scale.date >= '2020/08/01' AND
	supp.owner = 'EFI' AND
	scale.up = 0;";


// $queryString = "
// 	SELECT scale.str_no,
// 		scale.tr_no,
// 		scale.plate_no,
// 		scale.time_out as s_time_out,
// 		scale.date_out as s_date_out,
// 		supp.name as supplier_name,
// 		comp.name as company_name,
// 		comm.name as grade,
// 		details.date_in as d_date_in,
// 		details.weigh_in as d_weigh_in,
// 		details.date_out as d_date_out,
// 		details.weigh_out as d_weigh_out,
// 		details.gross,
// 		details.tare,
// 		details.net_weight,
// 		details.moisture,
// 		details.bales,
// 		details.moisture_bales,
// 		details.reject_perct,
// 		details.reject_kg,
// 		details.com_remarks
// 	FROM `scale`
// 	INNER JOIN `scale_details` as details ON details.scale_id = scale.scale_id
// 	INNER JOIN `supplier` as supp ON supp.supplier_id = scale.supplier_id
// 	INNER JOIN `company` as comp ON comp.company_id = scale.company_id
// 	INNER JOIN `commodity` as comm ON comm.com_id = details.com_id
// 	WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') AND
// 	scale.status = 'COMPLETED' AND scale.linked = 1 AND scale.date >= '2020/08/01' AND
// 	supp.owner = 'EFI' AND scale.up = 0;";

$scale = getFirst($queryScale, null);

//dd_p($scale);


$receivingTable = 'scale_receiving';
$outgoingTable = 'scale_outgoing';

if ($scale) {

	$supp_arr = split('-', $scale->supplier_name);

	$branch_id = getBranchId(end($supp_arr));

	$supplier_id = (int) $supp_arr[0];

	$deliveredTo = getDeliveredToId($scale->company_name);

	$date_out = dateOut($scale->time_out, $scale->date_out);

	$data = array(
		'date' => $date_out,
		'date_out' => $date_out,
		'str_no' => $scale->str_no,
		'tr_no' => $scale->tr_no,
		'plate_number' => $scale->plate_no,
		'supplier_id' => getEfiSupplierId($supplier_id),
		'dt_id' => getDeliveredToId($scale->company_name),
	);

	$queryScaleDetails = "SELECT * FROM `scale_details` WHERE `scale_id` = {$scale->scale_id}";
	$scaleDetails = fetch($queryScaleDetails, null);

	$dataDetails = array();

	foreach ($scaleDetails as $detail) {

		$tipco_material_code = getFirst("SELECT * FROM `commodity` WHERE com_id='{$detail->com_id}'", null);

		$material_id = getEfiMaterialId($tipco_material_code->name);

		$_date_in = trim($detail->date_in);
		$_weigh_in = trim($detail->weigh_in);

		$_date_out = trim($detail->date_out);
		$_weigh_out = trim($detail->weigh_out);

		$gross = trim($detail->gross);
		$tare = trim($detail->tare);
		$weight = trim($detail->net_weight);

		$mc_perct = trim($detail->moisture);
		$bales = trim($detail->bales);

		$mc_bales = trim($detail->moisture_bales);
		$dirt_perct = trim($detail->reject_perct);
		$dirt_kg = trim($detail->reject_kg);
		$remarks = $detail->com_remarks;

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

		$dataDetails[] = array(
			'material_id' => $material_id,
			'date_in' => $_date_in,
			'weigh_in' => $_weigh_in,
			'date_out' => $_date_out,
			'weigh_out' => $_weigh_out,
			'gross' => $gross,
			'tare' => $tare,
			'net_weight' => $weight,
			'mc_perct' => $mc_perct,
			'mc_bales' => $mc_bales,
			'mc' => $mc,
			'dirt_perct' => $dirt_perct,
			'dirt_kg' => $dirt_kg,
			'dirt' => $total_rej,
			'corrected_weight' => $total_net_wt,
			'bales' => $bales,
			'remarks' => $remarks,
		);

	}

	$success = false;

	if ($branch_id === 7) {
		// should inserted on both receiving and outgoing.

		if (!find($receivingTable, $data)) {
			//not existing, then create
			if ($rec_trans_id = create($receivingTable, $data)) {

				//attach receiving details
				insertDetails('scale_receiving_details', $dataDetails, $rec_trans_id);

				if (!find($outgoingTable, $data)) {
					//existing outgoing,

					//attach receiving trans_id on outgoing
					$data['branch_id'] = $branch_id;
					$data['rec_trans_id'] = $rec_trans_id;

					if ($out_trans_id = create($outgoingTable, $data)) {
						//attach to outgoing details
						insertDetails('scale_outgoing_details', $dataDetails, $out_trans_id);
					}
				}
			}
		}

		//update tipco receiving
		updateTipco($scale->scale_id);

	} else {


		//insert outgoing only
		$data['branch_id'] = $branch_id;

		if (!find($outgoingTable, $data)) {
			//existing receiving
			//die('exist');

			//die(var_dump($data));

			if ($t_id = create($outgoingTable, $data)) {

				//die(var_dump($dataDetails));

				//attach details
				insertDetails('scale_outgoing_details', $dataDetails, $t_id);

				//update tipco receiving
				updateTipco($scale->scale_id);

			}

		} else {

			//die('not exist');

			//update tipco receiving
			updateTipco($scale->scale_id);
		}

	}

	var_dump($scale);

} else {
	die("Data Not Found!");
}

?>
