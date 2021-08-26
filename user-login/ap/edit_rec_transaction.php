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

<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $c = 0;
    $ctr = $_POST['ctr'];
    $count = 0;

    while ($c < $ctr) {
        if (isset($_POST['detail_id_' . $c])) {
            $count++;
        }
        $c++;
    }

    if ($count == $ctr) {
        echo "<script>";
        echo "alert('error');";
        echo "</script>";
    } else {
        $c = 0;
        $trans_id = $_POST['trans_id'];
        $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='$trans_id'");
        $rs_trans = mysql_fetch_array($sql_trans);

        $scale_rec = mysql_query("INSERT INTO `scale_receiving`(`str_no`, `date`, `supplier_id`, `dt_id`, `plate_number`)
        VALUES ('" . $rs_trans['str_no'] . "B','" . $rs_trans['date'] . "','" . $rs_trans['supplier_id'] . "','" . $rs_trans['dt_id'] . "','" . $rs_trans['plate_number'] . "')")or die(mysql_error());

        $sql_rec_max = mysql_query("SELECT max(trans_id) FROM scale_receiving");
        $rs_rec_max = mysql_fetch_array($sql_rec_max);

        $scale_rec = mysql_query("INSERT INTO `scale_outgoing`(`str_no`, `date`, `supplier_id`, `branch_id`, `dt_id`, `plate_number`, `rec_trans_id`)
        VALUES ('" . $rs_trans['str_no'] . "B','" . $rs_trans['date'] . "','" . $rs_trans['supplier_id'] . "','7','" . $rs_trans['dt_id'] . "','" . $rs_trans['plate_number'] . "','" . $rs_rec_max['max(trans_id)'] . "')")or die(mysql_error());

        $sql_out_max = mysql_query("SELECT max(trans_id) FROM scale_outgoing");
        $rs_out_max = mysql_fetch_array($sql_out_max);

        while ($c < $ctr) {
            if (isset($_POST['detail_id_' . $c])) {
                mysql_query("UPDATE scale_receiving_details SET trans_id='" . $rs_rec_max['max(trans_id)'] . "' WHERE detail_id='" . $_POST['detail_id_' . $c] . "'");

                mysql_query("UPDATE scale_outgoing_details SET trans_id='" . $rs_out_max['max(trans_id)'] . "' WHERE rec_detail_id='" . $_POST['detail_id_' . $c] . "'");
            }
            $c++;
        }
        echo "<script>";
        echo "alert('Generate New STR Successfully');";
        echo "window.close();";
        echo "</script>";
    }
} else {
    $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $_GET['trans_id'] . "'");
    $rs_rec = mysql_fetch_array($sql_rec);
    if ($rs_rec['status'] == 'paid') {
        echo "<script>
        alert('You cant edit transaction, This is already proccess for payment.');
        window.close();
        </script>";
    } else {

        $supplier_id = $rs_rec['supplier_id'];
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='$supplier_id'");
        $rs_sup = mysql_fetch_array($sql_sup);
        $supplier_name = $rs_sup['supplier_name'];
        $plate_number = $rs_rec['plate_number'];
        $str_no = $rs_rec['str_no'];
        $date = $rs_rec['date'];
        echo "<center>";
        echo "<h2>Edit Receiving Transaction</h2>";
        echo "<table>";
        echo "<tr>";
        echo "<td colspan='3' align='center'>
    <table>
    <tr>
    <td><b>Date: <u>$date</u>&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;&nbsp;&nbsp;<b>STR No.:</b> <u>$str_no</u></td>
    </td>
    </table></td>";
        echo "</tr>";
        echo "<tr>";
//    echo "<td><b>Supplier Name:</b> <u><a rel='facebox' href='edit_sup.php?trans_id=" . $_GET['trans_id'] . "'>" . $rs_sup['supplier_id'] . "_" . $supplier_name . "</a></u>&nbsp;&nbsp;</td>";
        echo "<td><b>Supplier Name:</b> <u>" . $rs_sup['supplier_id'] . "_" . $supplier_name . "</u>&nbsp;&nbsp;</td>";
        echo "<td>&nbsp;&nbsp;<b>Plate No:</b> <u>$plate_number</u>&nbsp;&nbsp;</td>";
        echo "</tr>";
        echo "</table>";
        echo "<div id='asterisk3'>*************************************************************</div>";
        echo "<b>WASTEPAPER GRADES</b>";
        echo "<form action='edit_rec_transaction.php' method='POST'>";
        echo "<table  width='500'>";
        echo "<tr>";
        echo "<th>Select</th>";
        echo "<th>WP Grade</th>";
        echo "<th>Gross (kg)</th>";
        echo "<th>Tare (kg)</th>";
        echo "<th>Net Wt (kg)</th>";
        echo "</tr>";
        $ctr = 0;
        $sql_rec_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $_GET['trans_id'] . "'");
        while ($rs_rec_details = mysql_fetch_array($sql_rec_details)) {
            $detail_id = $rs_rec_details['detail_id'];
            echo "<tr>";
            $sql_material = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_rec_details['material_id'] . "'");
            $rs_material = mysql_fetch_array($sql_material);
            echo "<td align='center'><input type='checkbox' name='detail_id_$ctr' value='$detail_id'></td>";
            echo "<td align='center'>";
            echo "<input type='text' name='wp_gradeid_$ctr' value='" . $rs_material['code'] . "' readonly>";
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
        echo "<input id='ctr' type='hidden' name='ctr' value='$ctr'>";
        echo "<input type='submit' name='submit' value='Generate New STR'>";
        echo "</form>";
        echo "</center>";
    }
}
?>

