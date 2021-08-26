<?php 

require_once 'helpers.php';
require_once 'connection_tipco.php';

function fetch($query, $params) {

	global $pdo;

	$q = $pdo->prepare($query);

	$q->execute($params);
	
	return $q->fetchAll();

}

function getFirst($query, $params) {

	global $pdo;

	$q = $pdo->prepare($query);

	$q->execute($params);

	$response = $q->fetchAll();

	if (count($response) > 0) {

		return $response[0];
	}

	return null;

}

function getWhere($table, $field, $value) {
	$query = "SELECT * FROM {$table} WHERE {$field} = ? LIMIT 1;";
	return fetch($query, $value);
}

function update($table, $data, $whereField, $whereValue) {

	global $pdo;

	$formattedFields = array();

	foreach (array_keys($data) as $field) {

		$formattedFields[] = "{$field}=:{$field}";

	}

	$fields = implode(', ', $formattedFields);

	$sql = "UPDATE {$table} SET {$fields} WHERE {$whereField} = {$whereValue}";

	$stmt = $pdo->prepare($sql);

	return $stmt->execute($data);

}


?>