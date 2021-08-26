<?php

include('config.php');
$page = $_SERVER['PHP_SELF'];
$sec = "10";
header("Refresh: $sec; url=$page");

if (@$_GET['update'] == 'outgoing') {
    $tid = $_GET['tid'];
    $strno = $_GET['strno'];
    if (mysql_query("UPDATE scale_outgoing SET up_out='1' WHERE trans_id='$tid' and str_no='$strno'")) {
        
    } else {
        ?>
        <script>
            alert("ERROR");
            location.replace("update_ims.php");
        </script>
        <?php

    }
} else if (@$_GET['update'] == 'receiving') {
    $tid = $_GET['tid'];
    $strno = $_GET['strno'];
    if (mysql_query("UPDATE scale_receiving SET upload='1' WHERE  trans_id='$tid' and str_no='$strno'")) {
        
    } else {
        ?>
        <script>
            alert("ERROR");
            location.replace("update_ims.php");
        </script>
        <?php

    }
} else if (@$_GET['update'] == 'paper') {
    $tid = $_GET['tid'];
    $strno = $_GET['strno'];
    if (mysql_query("UPDATE scale_receiving SET up_paper='1' WHERE trans_id='$tid' and str_no='$strno'")) {
        
    } else {
        ?>
        <script>
            alert("ERROR");
            location.replace("update_ims.php");
        </script>
        <?php

    }
}else if (@$_GET['update'] == 'yes') {
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
            <td align="center"><h1>Exporting data to IMS.</h1><br />
                <img src="images/update.gif">
                <br />
                <br />
            </td>
        </tr>
        <tr height="15px">
            <td align="center"><font color='red'><h1>Please don't close this window.</h1></font><br />
            </td>
        </tr>
    </table>
</center>
<?php

$url = 'http://ims.efi.net.ph/pampanga_receiving_module_new1.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if (200 == $retcode) {

    include('config.php');
    $result2 = mysql_query("SELECT * from scale_outgoing WHERE upload='0' and checked='1' Order by trans_id Asc LIMIT 1") or die(mysql_error());

    if (mysql_num_rows($result2) > 0) {

        $parameter = "";
        $row = mysql_fetch_array($result2);

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "' ") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);
        $branch_ = $select_supplier_row['branch'];

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
			
// 			branch
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

    $result = mysql_query("SELECT * from scale_receiving WHERE upload='0' and checked='1'  Order by trans_id Asc LIMIT 1") or die(mysql_error());

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

        echo "<form action='http://ims.efi.net.ph/pampanga_receiving_module_new1.php' method='POST' name='myForm'>";
        $parameter = "";

        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];

            $plate = $row['plate_number'];
            $str = $row['str_no'];
            $wp_grade = $material_ids;
            $weight = $select_row['corrected_weight'];
            $branch = $branch_;
            $date = $row['date'];
            $id = $row['trans_id'];
            $dtld_id = $select_row['detail_id'];
            $month_delivered = date("F", strtotime($date));
            $year_delivered = date("Y", strtotime($date));

            $parameter.=$supplier_id . "+" . $supplier_name . "+" . $wp_grade . "+" . $weight . "+" . $date . "+" . $branch . "+" . $id . "+" . $dtld_id . "+" . $month_delivered . "+" . $year_delivered . "+" . $str . "+" . $plate . "|";
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
    $result = mysql_query("SELECT * from scale_receiving WHERE up_paper='0' and checked='1' Order by trans_id Asc LIMIT 1") or die(mysql_error());


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

        /* echo "<form action='http://ims.efi.net.ph/paper_buying_module_pampanga.php' method='POST' name='myForm'>"; */
        echo "<form action='http://ims.efi.net.ph/paper_buying_module_pampanga_new1.php' method='POST' name='myForm'>";

        while ($select_row = mysql_fetch_array($select)) {

            $price = $select_row['price'];

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            if (empty($select_row['price']) || $select_row['price'] == ' ' || $select_row['price'] = '' || $select_row['price'] == 0 || $select_row['price'] == 0.00) {
                $select_price = mysql_query("SELECT * from suppliers_price Where supplier_id='" . $row['supplier_id'] . "' and material_id='" . $select_row['material_id'] . "' order by date Desc LIMIT 1") or die(mysql_error());
                $select_price_row = mysql_fetch_array($select_price);
                $price = $select_price_row['price'];
            }

            $material_ids = $select_material_row['code'];

            $trans_id = $row['trans_id'];
            $detail_id = $select_row['detail_id'];


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

            $paper_buying = ($corrected_weight * $price);
            $parameter.=$date_received . "+" . $priority_number . "+" . $supplier_id . "+" . $supplier_name . "+" . $plate_number . "+" . $wp_grade . "+" . $corrected_weight . "+" . $price . "+" . $paper_buying . "+" . $branch_ . "+" . $str . "+" . $trans_id . "+" . $detail_id . "|";
        }

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

    $result = mysql_query("SELECT * from scale_outgoing WHERE up_out='0' and checked='1' and branch_id='7' Order by trans_id Asc LIMIT 1") or die(mysql_error());



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


        $select = mysql_query("SELECT * from scale_outgoing_details WHERE trans_id='" . $row['trans_id'] . "'") or die(mysql_error());
        /* echo "<form action='http://ims.efi.net.ph/pampanga_outgoing_module.php' method='POST' name='myForm'>"; */
        echo "<form action='http://ims.efi.net.ph/pampanga_outgoing_module_new1.php' method='POST' name='myForm'>";
        $parameter = "";
        while ($select_row = mysql_fetch_array($select)) {

            $select_material = mysql_query("SELECT * from material WHERE material_id='" . $select_row['material_id'] . "'") or die(mysql_error());
            $select_material_row = mysql_fetch_array($select_material);
            $material_ids = $select_material_row['code'];

            $str = $row['str_no'];
            $wp_grade = $material_ids;
            $weight = $select_row['corrected_weight'];
            if (empty($weight) or $weight == 0) {
                $weight = $select_row['net_weight'];
            }
            $branch = $branch_;
            $date = $row['date'];
            $id = $row['trans_id'];
            $dtld_id = $select_row['detail_id'];
            $plate_number = $row['plate_number'];



            $parameter.= $str . "+" . $date . "+" . $supplier_name . "+" . $plate_number . "+" . $wp_grade . "+" . $weight . "+" . $branch . "+" . $id . "+" . $dtld_id . "|";
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
	
	$result2 = mysql_query("SELECT * from scale_outgoing WHERE upload='0' and checked='1' Order by trans_id Asc LIMIT 1") or die(mysql_error());

    if (mysql_num_rows($result2) > 0) {

        $parameter = "";
        $row = mysql_fetch_array($result2);

        $select_supplier = mysql_query("SELECT * from supplier WHERE id='" . $row['supplier_id'] . "' ") or die(mysql_error());
        $select_supplier_row = mysql_fetch_array($select_supplier);
        $branch_ = $select_supplier_row['branch'];

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
			
// 			branch
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
	
	
$url = 'http://ims.efi.net.ph/update_supp_pam.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if (200 == $retcode) {
		$my_page ="http://ims.efi.net.ph/update_supp_pam.php?branch=Pampanga";
		$sec = "5";
		header("Refresh: $sec, url=$my_page");
}

	
} else {
    $page = $_SERVER['PHP_SELF'];
    $sec = "10";
    header("Refresh: $sec; url=$page");
    ?>
    <script>
    //        location.replace('export_receiving.php');
    </script>
    <?php

}
?>