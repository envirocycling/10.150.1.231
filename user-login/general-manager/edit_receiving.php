<style>
    body{
        background-color: #dfddd2;
        font-family: arial;
    }
    .info{
        font-weight: bold
    }
    .head {
        font-weight: bold;
        text-align: center;
    }
    .head2 {
        font-weight: bold;
        text-align: center;
        font-size: 14px;
    }
    .details{
        font-weight: bold;
        font-size: 16px;
    }
    .input {
        width: 70px;
    }
    #pass{
        width: 100px;
    }
    .price {
        color: green;
        font-size: 12px;
        font-weight: bold;
        height: 180px;
        overflow: auto;
        width: 300px;
        padding:10px;
    }
    .remarks{
        width: 220px;
    }
</style>
<script src="js/jquery.min.js" type="text/javascript"></script>
<link href="src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: 'src/loading.gif',
            closeImage: 'src/closelabel.png'
        })
    })
</script>
<link rel="stylesheet" href="cbFilter/cbCss.css" />
<link rel="stylesheet" href="cbFilter/sup.css" />
<script src="cbFilter/jquery-1.8.3.js"></script>
<script src="cbFilter/jquery-ui.js"></script>
<script src="cbFilter/sup_combo.js"></script>
<script>
    function lesswt(str) {
        var splits = str.split("_");
        var mc = Number(document.getElementById("mc_" + splits[1]).value);
        var dirt = Number(document.getElementById("dirt_" + splits[1]).value);
        var net_weight = Number(document.getElementById("net_weight_" + splits[1]).value);
        var less = Number(mc + dirt);
        var corrected_weight = Number(net_weight - less);
        document.getElementById("less_weight_" + splits[1]).value = less;
        document.getElementById("corrected_wt_" + splits[1]).value = corrected_weight;
    }
</script>
<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

if (isset($_POST['submit'])) {
    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $_POST['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);

    mysql_query("UPDATE scale_receiving SET str_no='" . $_POST['str_no'] . "',series_no='" . $_POST['series_no'] . "',supplier_id='" . $_POST['supplier_id'] . "',upload='0',up_paper='0',checked='1' WHERE trans_id='" . $_POST['trans_id'] . "'");

    mysql_query("UPDATE scale_outgoing SET str_no='" . $_POST['str_no'] . "',series_no='" . $_POST['series_no'] . "',supplier_id='" . $_POST['supplier_id'] . "',upload='0',up_out='0',checked='1' WHERE rec_trans_id='" . $_POST['trans_id'] . "'");
    $c = $_POST['ctr'];
    $ctr = 0;
    while ($ctr < $c) {
        $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE material_id='" . $_POST['mat_' . $ctr] . "' and supplier_id='" . $_POST['supplier_id'] . "' and date<='" . $rs_trans['date'] . "' ORDER BY date DESC");
        $rs_sup_price_count = mysql_num_rows($sql_sup_price);
        $rs_sup_price = mysql_fetch_array($sql_sup_price);
        $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $_POST['mat_' . $ctr] . "'");
        $rs_def_price = mysql_fetch_array($sql_def_price);

        if ($rs_sup_price_count == 0) {
            $price = $rs_def_price['price'];
            $amount = $rs_def_price['price'] * $_POST['corrected_wt' . $ctr];
        } else {
            $price = $rs_sup_price['price'];
            $amount = $rs_sup_price['price'] * $_POST['corrected_wt' . $ctr];
        }

        mysql_query("UPDATE scale_receiving_details SET material_id='" . $_POST['mat_' . $ctr] . "',mc='" . $_POST['mc' . $ctr] . "',dirt='" . $_POST['dirt' . $ctr] . "',corrected_weight='" . $_POST['corrected_wt' . $ctr] . "',remarks='" . $_POST['remarks' . $ctr] . "',price='$price',amount='$amount' WHERE detail_id='" . $_POST['detail_id' . $ctr] . "'");

        mysql_query("UPDATE scale_outgoing_details SET material_id='" . $_POST['mat_' . $ctr] . "',mc='" . $_POST['mc' . $ctr] . "',dirt='" . $_POST['dirt' . $ctr] . "',corrected_weight='" . $_POST['corrected_wt' . $ctr] . "',remarks='" . $_POST['remarks' . $ctr] . "' WHERE rec_detail_id='" . $_POST['detail_id' . $ctr] . "'");

        $ctr++;
    }
    echo "<script>
        alert('Transaction successfully update.');
        window.close();
        </script>";
} else {

    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $_GET['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_trans['dt_id'] . "'");
    $rs_dt = mysql_fetch_array($sql_dt);

    echo "<center>";
    echo "<br>";
    echo "<form action='edit_receiving.php' method='POST'>";
    echo "<table class='details'>";
    echo "<tr>";
    echo "<td>Date delivered: </td>";
    echo "<td>" . $rs_trans['date'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td><b>Supplier Name:</b> </td>";
    echo "<td>";
    echo "<span id='sup_picker'>";
    echo "<select name='supplier_id' id='combobox' required>";
    echo "<option value='" . $rs_sup['id'] . "'>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</option>";
    $sql_sup_q = mysql_query("SELECT * FROM supplier");
    echo "<option value=''></option>";
    while ($rs_sup_q = mysql_fetch_array($sql_sup_q)) {
        echo "<option value='" . $rs_sup_q['id'] . "'>" . $rs_sup_q['supplier_id'] . "_" . $rs_sup_q['supplier_name'] . "</option>";
    }
    echo "</select>";
    echo "</span>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Plate Number: </td>";
    echo "<td>" . $rs_trans['plate_number'] . "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>STR Number: </td>";
    echo "<td><input type='text' name='str_no' value='" . $rs_trans['str_no'] . "'></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Series Number: </td>";
    echo "<td><input type='text' name='series_no' value='" . $rs_trans['series_no'] . "'></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Delivered To: </td>";
    echo "<td>" . $rs_dt['name'] . "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<table width='650'>";
    echo "<tr class='head2'>";
    echo "<td>Material</td>";
    echo "<td>Gross</td>";
    echo "<td>Tare</td>";
    echo "<td>Net Wt.</td>";
    echo "<td>MC</td>";
    echo "<td>Dirt</td>";
    echo "<td>Adj Weight</td>";
    echo "<td>Corrected</td>";
    echo "<td>Remarks</td>";
    echo "</tr>";
    echo "<input type='hidden' name='trans_id' value='" . $_GET['trans_id'] . "'>";
    $ctr = 0;
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $_GET['trans_id'] . "'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
        $rs_mat = mysql_fetch_array($sql_mat);
        $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE material_id='" . $rs_details['material_id'] . "' and supplier_id='" . $rs_trans['supplier_id'] . "' and date<='" . $rs_trans['date'] . "' ORDER BY id DESC");
        $rs_sup_price_count = mysql_num_rows($sql_sup_price);
        $rs_sup_price = mysql_fetch_array($sql_sup_price);
        $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $rs_details['material_id'] . "'");
        $rs_def_price = mysql_fetch_array($sql_def_price);
        echo "<tr>";
        echo "<input type='hidden' name='detail_id$ctr' value='" . $rs_details['detail_id'] . "'>";
        echo "<td>";
        echo "<select id='mat_$ctr' name='mat_$ctr'>";
        echo "<option value='" . $rs_details['material_id'] . "'>" . $rs_mat['code'] . "</option>";
        $sql_mat2 = mysql_query("SELECT * FROM material WHERE status!='deleted'");
        while ($rs_mat2 = mysql_fetch_array($sql_mat2)) {
            echo "<option value='" . $rs_mat2['material_id'] . "'>" . $rs_mat2['code'] . "</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td><input class='input' type='text' name='gross' value='" . $rs_details['gross'] . "' readonly></td>";
        echo "<td><input class='input' type='text' name='tare' value='" . $rs_details['tare'] . "' readonly></td>";
        echo "<td><input class='input' type='text' id='net_weight_" . $rs_details['detail_id'] . "' name='net_weight$ctr' value='" . $rs_details['net_weight'] . "' readonly></td>";

        echo "<td><input class='input' type='text' id='mc_" . $rs_details['detail_id'] . "' name='mc$ctr' value='" . $rs_details['mc'] . "' onkeyup='lesswt(this.id);'></td>";
        echo "<td><input class='input' type='text' id='dirt_" . $rs_details['detail_id'] . "' name='dirt$ctr' value='" . $rs_details['dirt'] . "' onkeyup='lesswt(this.id);'></td>";

        echo "<td><input class='input' type='text' id='less_weight_" . $rs_details['detail_id'] . "' name='less_weight$ctr' value='" . round($rs_details['mc'] + $rs_details['dirt'], 2) . "' readonly></td>";
        echo "<td><input class='input' type='text' id='corrected_wt_" . $rs_details['detail_id'] . "' name='corrected_wt$ctr' value='" . $rs_details['corrected_weight'] . "' readonly></td>";
        echo "<td><input class='remarks' type='text' name='remarks$ctr' value='" . $rs_details['remarks'] . "'></td>";
        echo "</tr>";
        $ctr++;
    }
    echo "<input type='hidden' name='ctr' value='$ctr'>";
    echo "<tr>";
    echo "<td colspan='12' align='center'><input type='submit' name='submit' value='Submit'></td>";
    echo "</tr>";
    echo "
</table>";
    echo "</form>";
    echo "</center>";
}
?>