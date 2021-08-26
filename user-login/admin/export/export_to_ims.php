<?php

session_start();
include('../config.php');
$page = $_SERVER['PHP_SELF'];
$sec = "10";
header("Refresh: $sec; url=$page");
if (@$_GET['update'] == 'yes') {
    $tid = $_GET['tid'];
    $strno = $_GET['strno'];
    if (mysql_query("UPDATE scale_outgoing SET upload='1' WHERE  trans_id='$tid' and str_no='$strno'") or die(mysql_error())) {
        
    } else {
        ?>
        <script>
            alert("ERROR");
            location.replace("update_ims.php");
        </script>
        <?php

    }
}
?>
<center>
    <table width="90%" height="200">
        <tr height="15px">
            <td align="center"><h1>Updating IMS</h1><br />
                <img src="../images/update.gif">
            </td>
        </tr>
        </tr>
    </table>
</center>
<?php

$url = 'http://ims.efi.net.ph/actual_module_new1.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if (200 == $retcode) {

    include('../config.php');
    $result = mysql_query("SELECT * from scale_outgoing WHERE upload='0' and checked='1' Order by trans_id Asc LIMIT 1") or die(mysql_error());

    if (mysql_num_rows($result) > 0) {

        $parameter = "";
        $row = mysql_fetch_array($result);

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "' ") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);
		
		$select_branch = mysql_query("SELECT * from branches WHERE branch_id='".$row['branch_id']."'") or die (mysql_error());
		$my_branch = mysql_fetch_array($select_branch);
        $branch_ = $my_branch['branch_name'];

        $supplier_name = $select_supplier_row['supplier_name'];


        $select_dt = mysql_query("SELECT * from delivered_to WHERE dt_id='" . $row['dt_id'] . "' ") or die(mysql_error());

        $select_dt_row = mysql_fetch_array($select_dt);

        $dt_id = $select_dt_row['name'];


        $select = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());
        echo "<form action='http://ims.efi.net.ph/actual_module_new1.php' method='POST' name='myForm'>";
        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];


            $str_no = $row['str_no'];
            $delivered_to = $dt_id;
            $plate_number = $row['plate_number'];
            $wp_grade = $material_ids;
            $weight = $select_row['corrected_weight'];
            if (empty($weight) || $weight == ' ') {
                $weight = $select_row['net_weight'];
            }
            $branch = $branch_;
            $date = $row['date'];
            $dirt = $select_row['dirt'];
            $mc = $select_row['mc'];
            $net = $select_row['net_weight'];
            $remarks = $select_row['remarks'];
            $id = $row['trans_id'];
            $dtld_id = $select_row['detail_id'];


            $parameter.=$str_no . "+" . $delivered_to . "+" . $plate_number . "+" . $wp_grade . "+" . $weight . "+" . $branch . "+" . $date . "+" . $mc . "+" . $dirt . "+" . $net . "+" . $remarks . "+" . $id . "+" . $dtld_id . "|";
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
    $page = $_SERVER['PHP_SELF'];
    $sec = "10";
    header("Refresh: $sec; url=$page");
    ?>
    <script>
    //        location.replace("export_to_ims.php");
    </script>
    <?php

}
?>