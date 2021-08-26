<style>
    body{
        font-family: arial;
    }
</style>
<script src="js/jquery.min.js" type="text/javascript"></script>
<script>
    function net_weight(str) {
        var ctr = Number(document.getElementById("ctr").value - 1);
        var splits = str.split("_");
        if (ctr != splits[1]) {
            var gross = Number(document.getElementById("gross_" + splits[1]).value);
            var tare = Number(document.getElementById("tare_" + splits[1]).value);
            var net_weight = Number(gross - tare);
            document.getElementById("net_weight_" + splits[1]).value = net_weight;
            var val = splits[1];
            val++;
            document.getElementById("gross_" + val).value = tare;
            var gross2 = Number(document.getElementById("gross_" + val).value);
            var tare2 = Number(document.getElementById("tare_" + val).value);
            var net_weight2 = Number(gross2 - tare2);
            document.getElementById("net_weight_" + val).value = net_weight2;
        } else {
            var gross = Number(document.getElementById("gross_" + splits[1]).value);
            var tare = Number(document.getElementById("tare_" + splits[1]).value);
            var net_weight = Number(gross - tare);
            document.getElementById("net_weight_" + splits[1]).value = net_weight;
        }
    }
    function save() {
        var ctr = document.getElementById("ctr").value;
        var trans_id = document.getElementById("trans_id").value;
        var c = 0;
        var mat = '';
        var gross = '';
        var tare = '';
        var net_weight = '';
        while (c < ctr) {
            var val1 = document.getElementById("wp_gradeid_" + c).value;
            var val2 = document.getElementById("gross_" + c).value;
            var val3 = document.getElementById("tare_" + c).value;
            var val4 = document.getElementById("net_weight_" + c).value;
            if (mat == '') {
                mat += 'mat_id_' + c + '=' + val1;
                gross += 'gross_' + c + '=' + val2;
                tare += 'tare_' + c + '=' + val3;
                net_weight += 'net_weight_' + c + '=' + val4;
            } else {
                mat += '&mat_id_' + c + '=' + val1;
                gross += '&gross_' + c + '=' + val2;
                tare += '&tare_' + c + '=' + val3;
                net_weight += '&net_weight_' + c + '=' + val4;
            }
            c++;
        }
        var dataString = 'ctr=' + ctr + '&trans_id=' + trans_id + '&' + mat + '&' + gross + '&' + tare + '&' + net_weight;
        $.ajax({
            type: "POST",
            url: "save_edit_out_transaction.php",
            data: dataString,
            cache: false
        });
        alert('Successfully Save.');
        window.close();

//                alert(mat + '&' + gross + '&' + tare + '&' + net_weight);
    }
</script>
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
$sql_rec = mysql_query("SELECT * FROM scale_outgoing WHERE trans_id='" . $_GET['trans_id'] . "'");
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
        echo "<select id='wp_gradeid_$ctr' name='wp_gradeid_$ctr'>
     <option value='" . $rs_material['material_id'] . "'>" . $rs_material['code'] . "</option>";
        $sql_grade = mysql_query("SELECT * FROM material WHERE status!='deleted'");
        while ($rs_grade = mysql_fetch_array($sql_grade)) {
            echo "<option value='" . $rs_grade['material_id'] . "'>" . $rs_grade['code'] . "</option>";
        }
        echo "</td>";
        echo "<td align='center'>";
        if ($ctr == 0) {
            echo "<input id='gross_$ctr' type='text' name='gross' value='" . $rs_rec_details['gross'] . "' onkeyup='net_weight(this.id);' >";
        } else {
            echo "<input id='gross_$ctr' type='text' name='gross' value='" . $rs_rec_details['gross'] . "' onkeyup='net_weight(this.id);' readonly>";
        }
        echo "</td>";
        echo "<td align='center'><input id='tare_$ctr' type='text' name='tare' value='" . $rs_rec_details['tare'] . "' onkeyup='net_weight(this.id);' ></td>";
        echo "<td align='center'><input id='net_weight_$ctr' type='text' name='net_weight' value='" . $rs_rec_details['net_weight'] . "' readonly></td>";
        echo "<td align='center'>";
        ?>
                                <!--<a href='del_out_details.php?trans_id=<?php // echo $_GET['trans_id'];    ?>&detail_id=<?php // echo $rs_rec_details['detail_id'];    ?>'><button onclick="return confirm('Are you sure you want to delete this line?')">Delete</button></a>-->
        <?php
        echo "</td>";
        echo "</tr>";
        $ctr++;
    }
    echo "</table>";
    echo "<input id='trans_id' type='hidden' name='trans_id' value='" . $_GET['trans_id'] . "'>";
    echo "<input id='ctr' type='hidden' name='ctr' value='$ctr'>";
    echo "<br>";
    echo "<input type='submit' name='submit' value='Update' onclick='save();'>";
    echo "</center>";
}
?>
