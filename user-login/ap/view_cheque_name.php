<?php

include 'config.php';
$sql_cheque = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $_POST['sup_id'] . "'");
echo "<option value=''></option>";
while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
    echo "<option value='" . $rs_cheque['name'] . "'>" . $rs_cheque['name'] . "</option>";
}
?>