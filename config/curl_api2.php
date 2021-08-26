<?php

// https://weichie.com/blog/curl-api-calls-with-php/

function callApi($method, $url, $data) {

	$ch = curl_init();

	switch ($method) {

	case 'POST':
		curl_setopt($ch, CURLOPT_POST, 1);
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
		break;
	case 'PUT':
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		if ($data) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}
		break;
	case 'DELETE':
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		break;
	default:
		if ($data) {
			$url = sprintf("%s?%s", $url, http_build_query($data));
		}
	}

	// OPTIONS:
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
	));
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	$response = curl_exec($ch);

	$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	date_default_timezone_set('Asia/Manila');

	$_d = date("Y-m-d h:i:s");

	if (curl_errno($ch)) {

		throw new Exception("{$_d} -> {$method} -> {$url} -> " . curl_error($ch));

	}

	curl_close($ch);

	if ($statusCode === 200 || $statusCode === 201) {

		return [
			'data' => $response,
			'status' => $statusCode,
		];

	} else {

		throw new Exception("{$_d} -> Status Code: {$statusCode} -> {$method} -> {$url}");
	}

}

?>