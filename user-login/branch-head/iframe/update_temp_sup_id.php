<?php

include 'config.php';

if (isset($_POST['type'])) {
    if ($_POST['type'] == 'checked') {
        mysql_query("INSERT INTO temp_sup_id (supplier_id) VALUES ('" . $_POST['supplier_id'] . "')");
    }
    if ($_POST['type'] == 'checked_all') {
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%" . $_POST['branch'] . "%'");
        while ($rs_sup = mysql_fetch_array($sql_sup)) {
            mysql_query("INSERT INTO temp_sup_id (supplier_id) VALUES ('" . $rs_sup['id'] . "')");
        }
    }
    if ($_POST['type'] == 'unchecked') {
        mysql_query("DELETE FROM temp_sup_id WHERE supplier_id='" . $_POST['supplier_id'] . "'");
    }
    if ($_POST['type'] == 'unchecked_all') {
        mysql_query("DELETE FROM temp_sup_id");
    }
}
?>