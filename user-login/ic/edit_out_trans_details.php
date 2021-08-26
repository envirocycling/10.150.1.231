<style>
    body{
        font-family: arial;
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
<?php
include 'config.php';
if (isset($_POST['submit'])) {
    mysql_query("UPDATE scale_outgoing SET str_no='" . $_POST['str_no'] . "', supplier_id='" . $_POST['supplier_id'] . "',upload='0',up_out='0',checked='1' WHERE trans_id='" . $_GET['trans_id'] . "'");

//    mysql_query("UPDATE scale_receiving SET str_no='" . $_POST['str_no'] . "', supplier_id='" . $_POST['supplier_id'] . "' WHERE trans_id='" . $_GET['rec_trans_id'] . "'");
}
$sql_rec = mysql_query("SELECT * FROM scale_outgoing WHERE trans_id='" . $_GET['trans_id'] . "'");
$rs_rec = mysql_fetch_array($sql_rec);

$supplier_id = $rs_rec['supplier_id'];
$sql_sup = mysql_query("SELECT * FROM supplier WHERE id='$supplier_id'");
$rs_sup = mysql_fetch_array($sql_sup);
$supplier_name = $rs_sup['supplier_name'];
$plate_number = $rs_rec['plate_number'];
$str_no = $rs_rec['str_no'];
$date = $rs_rec['date'];
echo "<center>";
echo "<h2>Edit Delivery Transaction</h2>";
echo "<form action='edit_out_trans_details.php?trans_id=" . $_GET['trans_id'] . "&rec_trans_id=" . $rs_rec['rec_trans_id'] . "' method='POST'>";
echo "<table>";
echo "<tr>";
echo "<td><b>Date: </td>";
echo "<td><u>$date</u></td>";
echo "</tr>";
echo "<tr>";
echo "<td><b>STR No.:</b></td>";
echo "<td><input type='text' name='str_no' value='$str_no'></td>";
echo "</tr>";
echo "<tr>";
echo "<td><b>Supplier Name:</b> </td>";
echo "<td>";
echo "<span id='sup_picker'>";
echo "<select name='supplier_id' id='combobox' required>";
echo "<option value='" . $rs_sup['id'] . "'>" . $rs_sup['supplier_id'] . "_" . $supplier_name . "</option>";
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
echo "<td><b>Plate No:</b> </td>";
echo "<td><u>$plate_number</u></td>";
echo "</tr>";
echo "</table>";

echo "<div id='asterisk3'>*************************************************************</div>";
echo "<b>WASTEPAPER GRADES</b>";
echo "<table  width='500'>";
echo "<tr>";
echo "<th>WP Grade</th>";
echo "<th>Gross (kg)</th>";
echo "<th>Tare (kg)</th>";
echo "<th>Net Wt (kg)</th>";
echo "</tr>";
$ctr = 0;
$sql_rec_details = mysql_query("SELECT * FROM scale_outgoing_details WHERE trans_id='" . $_GET['trans_id'] . "'");
while ($rs_rec_details = mysql_fetch_array($sql_rec_details)) {
    $detail_id = $rs_rec_details['detail_id'];
    echo "<tr>";
    $sql_material = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_rec_details['material_id'] . "'");
    $rs_material = mysql_fetch_array($sql_material);
    echo "<td align='center'>";
    echo "<input type='hidden' name='detail_id_$ctr' value='$detail_id'>";
    echo "<input type='hidden' name='wp_gradeid_$ctr' value='" . $rs_material['material_id'] . "'>";
    echo "<input type='text' name='wp_grade$ctr' value='" . $rs_material['code'] . "' readonly>";
    echo "</td>";
    echo "<td align='center'>";
    if ($ctr == 0) {
        echo "<input id='gross_$ctr' type='text' name='gross' value='" . $rs_rec_details['gross'] . "' onkeyup='net_weight(this.id);' readonly>";
    } else {
        echo "<input id='gross_$ctr' type='text' name='gross' value='" . $rs_rec_details['gross'] . "' onkeyup='net_weight(this.id);' readonly>";
    }
    echo "</td>";
    echo "<td align='center'><input id='tare_$ctr' type='text' name='tare' value='" . $rs_rec_details['tare'] . "' onkeyup='net_weight(this.id);' readonly></td>";
    echo "<td align='center'><input id='net_weight_$ctr' type='text' name='net_weight' value='" . $rs_rec_details['net_weight'] . "' readonly></td>";
    echo "</tr>";
    $ctr++;
}
echo "</table>";
echo "<input id='trans_id' type='hidden' name='trans_id' value='" . $_GET['trans_id'] . "'>";
echo "<input type='submit' name='submit' value='Update'>";
echo "<form>";
echo "</center>";
?>

