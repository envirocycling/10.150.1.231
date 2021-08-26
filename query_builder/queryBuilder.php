<?php include_once 'config/config.php';

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

?>