<?php
$page = $_SERVER['PHP_SELF'];
$sec = "5";
header("Refresh: $sec; url=$page");
?>
<?php
$url = 'http://ims.efi.net.ph/paper_buying_module_pampanga_new.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if (200 == $retcode) {

    include('config.php');
    ini_set('max_execution_time', 1000);



    $result = mysql_query("SELECT * from scale_receiving WHERE up_paper='0' Order by trans_id Asc LIMIT 1") or die(mysql_error());


    if (mysql_num_rows($result) > 0) {

        $parameter = "";
        $row = mysql_fetch_array($result);
        $str = $row['str_no'];
        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "' ") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);
        $branch_ = $select_supplier_row['branch'];

        $supplier_name = $select_supplier_row['supplier_name'];
        $supplier_id = $select_supplier_row['supplier_id'];



        $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "' ") or die(mysql_error());

        $select_dt_row = mysql_fetch_array($select_dt);

        $dt_id = $select_dt_row['name'];


        $select = mysql_query("SELECT * from scale_receiving_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());


        echo "<form action='paper_buying_module_pampanga.php' method='POST' name='myForm'>";

        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];

            $trans_id = $row['trans_id'];
            $detail_id = $select_row['detail_id'];
            /* echo "<form action='http://ims.efi.net.ph/paper_buying_module_pampanga.php' method='POST' name='myForm'>"; */

            $date_received = $row['date'];
            $priority_number = "N/A";
            $supplier_id = $row['supplier_id'];
            $supplier_name = $supplier_name;
            $plate_number = $row['plate_number'];

            $wp_grade = $material_ids;
            if ((strpos($wp_grade, 'LC') === TRUE)) {
                if ($wp_grade != 'LCWL') {
                    $wp_grade = substr($wp_grade, 2);
                }
            }
            $corrected_weight = $select_row['corrected_weight'];
            $price = $select_row['price'];
            $paper_buying = ($corrected_weight * $price);
            $parameter.=$date_received . "+" . $priority_number . "+" . $supplier_id . "+" . $supplier_name . "+" . $plate_number . "+" . $wp_grade . "+" . $corrected_weight . "+" . $price . "+" . $paper_buying . "+" . $branch_ . "+" . $str . "+" . $trans_id . "+" . $detail_id . "|";
        }
        echo "</table>";
        echo "<input type='hidden' value='Pampanga' name='branch'>";
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
        location.replace("export_paper_buying.php");
    </script>
    <?php
}
?>

<!-- <script>
alert('Exporting Paper Buying Currenty Not Available.');
location.replace('reports.php');
</script> -->