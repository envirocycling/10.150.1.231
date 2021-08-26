<?php

require_once '/var/www/html/paymentsystem/config/query_builder.php';

$root = "/var/www/html/paymentsystem";
$dir = "{$root}/ftp_data/";
$dir_duplicate = "{$root}/ftp_duplicate/";
$dir_uploaded = "{$root}/ftp_uploaded/";
$log_file = "{$root}/tasks/logs/errors_tipco_receiving.txt";

$duplicate = 0;
$files = array();
$data_array = array();

$dh = opendir($dir);

$branches = array_map(function ($branch) {
	return strtoupper($branch->branch_name);
}, fetch("SELECT * FROM `branches`;", null));

while (false !== ($filename = readdir($dh))) {

	$file = trim($filename);

	if ($file !== '..' && $file !== '.') {
		$files[] = $filename;
	}

}

rsort($files);
$count = count($files);

$c = 0;

if ($count > 0) {

	while ($c < 1) {

		$file = fopen($dir . $files[$c], "r");

		$ctr = 0;

		while (!feof($file)) {
			$data_array[$ctr] = fgets($file);
			$ctr++;
		}

		$date = trim(substr($data_array[0], 0, -1));
		$date_out = trim(substr($data_array[0], 0, -1));
		$dr_number = trim(substr($data_array[1], 0, -1));
		$str_no = trim(substr($data_array[1], 0, -1));
		$tr_no = substr($data_array[2], 0, -1);

		$sup_data = preg_split("/[-]/", trim($data_array[3]));

		$supplier_id = $sup_data[0];

		$rs_sup = getFirst("SELECT * FROM supplier WHERE supplier_id={$supplier_id}", null);

		if (isset($sup_data[2])) {

			$sec_index = strtoupper($sup_data[2]);

			if ($sec_index == 'SANFERNANDO') {
				$branch = 'SAN FERNANDO';
			} else if ($sec_index == 'STA ROSA NE') {
				$branch = 'STA ROSA';
			} else {
				$branch = in_array($sec_index, $branches) ? $sec_index : 'PAMPANGA';
			}

		} else {
			$branch = 'PAMPANGA';
		}

		$rs_branch = getFirst("SELECT * FROM branches WHERE branch_name like '%$branch%'", null);

		$plate_number = trim(substr($data_array[4], 0, -1));
		$delivered_to = trim(substr($data_array[5], 0, -1));

		$query_dlvrdto_row = getFirst("SELECT * from delivered_to WHERE name='{$delivered_to}'", null);

		$delivered_to_id = (int) $query_dlvrdto_row->dt_id;

		$table = $branch == 'PAMPANGA' ? 'scale_receiving' : 'scale_outgoing';
		$existing = getFirst("SELECT * FROM {$table} WHERE str_no ='{$str_no}'", null);

		if ($existing && $str_no != '') {

			$str_no_new = $str_no;
			$existing_dt = (int) $existing->dt_id;

			if ($delivered_to_id === $existing_dt) {
				$duplicate++;
			} else {
				$str_no_new = $str_no . 'B';
			}

		} else {

			$str_no_new = $str_no;

		}

		if ($duplicate > 0) {

			fclose($file);

			$move_from = $dir . $files[$c];
			$move_to = $dir_duplicate . $files[$c];

			if (!rename($move_from, $move_to)) {
				file_put_contents($log_file, error_get_last() . PHP_EOL, FILE_APPEND | LOCK_EX);
				exit();
			}

		} else {

			$data_insert = array(
				'str_no' => $str_no_new,
				'tr_no' => $tr_no,
				'date' => $date,
				'date_out' => $date_out,
				'supplier_id' => $rs_sup->id,
				'dt_id' => $delivered_to_id,
				'plate_number' => $plate_number,
			);


			if ($branch == 'PAMPANGA') {

				$res_rec = insert('scale_receiving', $data_insert);

				if (!$res_rec) {
					file_put_contents($log_file, "Failed to insert Receiving: STR NO - {$str_no_new}" . PHP_EOL, FILE_APPEND | LOCK_EX);
					exit();
				}

				$data_insert['branch_id'] = $rs_branch->branch_id;
				$data_insert['rec_trans_id'] = getLastTransId('scale_receiving');
				$res_out = insert('scale_outgoing', $data_insert);

				if (!$res_out) {
					file_put_contents($log_file, "Failed to insert Outgoing: STR NO - {$str_no_new}" . PHP_EOL, FILE_APPEND | LOCK_EX);
					exit();
				}

			} else {

				

				$data_insert['branch_id'] = $rs_branch->branch_id;


				$res_out = insert('scale_outgoing', $data_insert);

				if (!$res_out) {
					file_put_contents($log_file, "Failed to insert Outgoing: STR NO - {$str_no_new}" . PHP_EOL, FILE_APPEND | LOCK_EX);
					exit();
				}
			}

			$c2 = 6;

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

					$trans_id_out = getLastTransId('scale_outgoing');
					$trans_id_rec = getLastTransId('scale_receiving');

					$data_details_insert = array(
						'material_id' => getMaterialId($wp_grade),
						'date_in' => $date_in,
						'weigh_in' => $weigh_in,
						'date_out' => $date_out,
						'weigh_out' => $weigh_out,
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

					if ($branch == 'PAMPANGA') {

						$data_details_insert['trans_id'] = $trans_id_rec;
						$res_rec = insert('scale_receiving_details', $data_details_insert);

						if (!$res_rec) {
							file_put_contents($log_file, "Failed to Insert Scale Receiving Details: trans_id - {$trans_id_rec}" . PHP_EOL, FILE_APPEND | LOCK_EX);
							exit();
						}

						$data_details_insert['trans_id'] = $trans_id_out;
						$data_details_insert['rec_detail_id'] = getLastDetailId('scale_receiving_details', $trans_id_rec);
						$res_out = insert('scale_outgoing_details', $data_details_insert);

						if (!$res_out) {
							file_put_contents($log_file, "Failed to Insert Scale Outgoing Details: trans_id - {$trans_id_out}" . PHP_EOL, FILE_APPEND | LOCK_EX);
							exit();
						}

					} else {

						$data_details_insert['trans_id'] = $trans_id_out;
						$res_out = insert('scale_outgoing_details', $data_details_insert);

						if (!$res_out) {
							file_put_contents($log_file, "Failed to Insert Scale Outgoing Details: trans_id - {$trans_id_out}" . PHP_EOL, FILE_APPEND | LOCK_EX);
							exit();
						}
					}

				} else {
					$c2++;
				}
			}

			fclose($file);

			$count_rec = (int) countRecord('scale_receiving', 'str_no', $str_no_new);
			$count_out = (int) countRecord('scale_outgoing', 'str_no', $str_no_new);

			$check = $count_rec + $count_out;

			if ($check > 0) {

				$move_from = $dir . $files[$c];
				$move_to = $dir_uploaded . $files[$c];

				rename($move_from, $move_to);
			}
		}

		$c++;
	}
}

?>
