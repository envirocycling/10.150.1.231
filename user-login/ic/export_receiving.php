<?php

$page = $_SERVER['PHP_SELF'];
$sec = "5";
header("Refresh: $sec; url=$page");
?>
<table>
    <tr>
        <td><img src="images/magic_007.gif"></td>
    </tr>
    <tr>
        <td align="center">UPDATING RECEIVING</td>
    </tr>
</table>
<?php

$url = 'http://ims.efi.net.ph/pampanga_outgoing_module_new.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if (200 == $retcode) {

    include('config.php');

    $result = mysql_query("SELECT * from scale_receiving WHERE upload='0' and check='1'  Order by trans_id Asc LIMIT 1") or die(mysql_error());




    if (mysql_num_rows($result) > 0) {

        $parameter = "";
        $row = mysql_fetch_array($result);

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "' ") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);
        $branch_ = $select_supplier_row['branch'];

        $supplier_name = $select_supplier_row['supplier_name'];
        $supplier_id = $select_supplier_row['supplier_id'];



        $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "' ") or die(mysql_error());

        $select_dt_row = mysql_fetch_array($select_dt);

        $dt_id = $select_dt_row['name'];


        $select = mysql_query("SELECT * from scale_receiving_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());

        echo "<form action='pampanga_receiving_module.php' method='POST' name='myForm'>";
        $parameter = "";

        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];


            $str = $row['str_no'];
            $wp_grade = $material_ids;
            $weight = $select_row['net_weight'];
            $branch = $branch_;
            $date = $row['date'];
            $id = $row['trans_id'];
            $dtld_id = $select_row['detail_id'];
            $month_delivered = date("F", strtotime($date));
            $year_delivered = date("Y", strtotime($date));

            $parameter.=$supplier_id . "+" . $supplier_name . "+" . $wp_grade . "+" . $weight . "+" . $date . "+" . $branch . "+" . $id . "+" . $dtld_id . "+" . $month_delivered . "+" . $year_delivered . "+" . $id . "+" . $dtld_id . "+" . $str . "|";
        }

        echo "<input type='hidden' value='$parameter' name='parameter'>";
        echo
        "</form>";

        echo "
<script>
    document.myForm.submit();
</script>
";
    }
} else {
    ?>
    <script>
        location.replace('export_receiving.php');
    </script>
    <?php

}
?>