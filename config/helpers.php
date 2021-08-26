<?php

date_default_timezone_set('Asia/Manila');

function dd($value) {
	die(var_dump($value));
}

function dd_p($value) {
	echo '<pre>';
	die(var_dump($value));
	echo '</pre>';
}



function grade($_grade) {

	$grade = trim(str_replace('LCLC', 'LC', strtoupper($_grade)));

	if ($grade == 'LCMW' ||
		$grade == 'MW' ||
		$grade == 'MW1' ||
		$grade == 'MW2' ||
		$grade == 'MW3' ||
		$grade == 'MW_PPQ' ||
		$grade == 'MW_PPQ1' ||
		$grade == 'MW_PPQ2' ||
		$grade == 'MW_PC' ||
		$grade == 'MW_CIG' ||
		$grade == 'MW_S' ||
		$grade == 'MW_P' ||
		$grade == 'LCMW1' ||
		$grade == 'LCMW2' ||
		$grade == 'LCMW3' ||
		$grade == 'LCMW_PPQ' ||
		$grade == 'LCMW_PPQ1' ||
		$grade == 'LCMW_PPQ2' ||
		$grade == 'LCMW_PC' ||
		$grade == 'LCMW_CIG' ||
		$grade == 'LCMW_S' ||
		$grade == 'CT' ||
		$grade == 'CORETUBE' ||
		$grade == 'CT MW' ||
		$grade == 'CT M.WASTE' ||
		$grade == 'CORETUBE M.WASTE' ||
		$grade == 'M.WASTE' ||
		$grade == 'LCMW_P') {

		return 'LCMW';

	} else if ($grade == 'ONP' ||
		$grade == 'ONP_B' ||
		$grade == 'NPB' ||
		$grade == 'ONP_DE' ||
		$grade == 'OIN' ||
		$grade == 'LCONP' ||
		$grade == 'LCONP_B' ||
		$grade == 'LCNPB' ||
		$grade == 'LCONP_DE' ||
		$grade == 'LCOIN' ||
		$grade == 'LCONP_GUMS/STICKIES' ||
		$grade == 'ONP_GUMS/STICKIES') {

		return 'LCONP';

	} else if ($grade == 'LCWL' ||
		$grade == 'WL' ||
		$grade == 'WL_S' ||
		$grade == 'WL_B' ||
		$grade == 'WL_CBS' ||
		$grade == 'WL_BOOKS' ||
		$grade == 'WL_GW' ||
		$grade == 'WL_GUMS/STICKIES' ||
		$grade == 'LCWL_GW' ||
		$grade == 'LCWL_GUMS/STICKIES' ||
		$grade == 'LCWL_BOOKS' ||
		$grade == 'LCWL_CBS' ||
		$grade == 'LCWL_B' ||
		$grade == 'LCWL_S') {

		return 'LCWL';

	} else if ($grade == 'LCCBS' ||
		$grade == 'LCCBS_B' ||
		$grade == 'CBS' ||
		$grade == 'CBS_B') {

		return 'LCCBS';

	} else if ($grade == 'LCOCC' ||
		$grade == 'LCOCC_TRIMMINGS' ||
		$grade == 'OCC' ||
		$grade == 'OCC_TRIMMINGS') {

		return 'LCOCC';

	} else if ($grade == 'LCCB' ||
		$grade == 'LCCB_A' ||
		$grade == 'LCCB_B' ||
		$grade == 'CB_A' ||
		$grade == 'CB_B' ||
		$grade == 'CHIPBOARD') {

		return 'CHIPBOARD';

	} else {
		return 'OTHERS';
	}
}


function getLastTransId($table) {
	$record = getFirst("SELECT max(trans_id) as last_id FROM `{$table}`", null);
	return $record ? $record->last_id : null;
}

function getLastDetailId($table, $trans_id) {
	$record = getFirst("SELECT max(detail_id) as last_id FROM {$table} WHERE trans_id={$trans_id}", null);
	return $record ? $record->last_id : 0;
}

function getMaterialId($wp_grade) {
	$material = getFirst("SELECT * FROM material WHERE code='{$wp_grade}'", null);
	return $material ? $material->material_id : 0;
}

function countRecord($table, $where, $value) {
	$record = fetch("SELECT * FROM {$table} WHERE {$where} = '{$value}';", null);
	return $record ? count($record) : 0;
}

?>
