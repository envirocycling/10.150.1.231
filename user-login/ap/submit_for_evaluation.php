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
    .plusMinus{
        height: 30px;
        width: 30px;
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
    $(document).ready(function () {
        var c = 2;
        while (c <= 10) {
            $("#row_" + c).hide();
            c++;
        }

        $("#plus").click(function () {
            var row_count = $('#row_show').val();
            if (row_count < 10) {
                row_count++;
                $('#row_' + row_count).show();
                $('#row_show').val(row_count);
            } else {
                alert('Limit is 10 items only.');
            }
        });

        $("#minus").click(function () {
            var row_count = $('#row_show').val();
            if (row_count > 1) {
                $('#row_' + row_count).hide();
                row_count--;
                $('#row_show').val(row_count);
            }
        });
    });

    function compute(str) {
        var splits = str.split("_");
        var mc = Number(document.getElementById("mc_" + splits[1]).value);
        var dirt = Number(document.getElementById("dirt_" + splits[1]).value);
        var net_weight = Number(document.getElementById("netweight_" + splits[1]).value);
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

    if ($_POST['detail_id_1'] == '') {
        echo "<script>
        alert('Error.');
        history.back();
        </script>";
    } else {

        $save_by = $_SESSION['user_id'];

        mysql_query("UPDATE scale_receiving SET status='generated',save_by='$save_by' WHERE trans_id='" . $_POST['trans_id'] . "'");

        $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $_POST['trans_id'] . "'");
        while ($rs_details = mysql_fetch_array($sql_details)) {
            mysql_query("INSERT INTO `scale_recdet_forevaluation`(`prev_detail_id`, `trans_id`, `material_id`, `date_in`, `weigh_in`, `date_out`, `weigh_out`, `gross`, `tare`, `net_weight`, `mc_perct`, `mc`, `dirt`, `corrected_weight`, `bales`, `remarks`)
                VALUES ('" . $rs_details['detail_id'] . "','" . $_POST['trans_id'] . "','" . $rs_details['material_id'] . "','" . $rs_details['date_in'] . "','" . $rs_details['weigh_in'] . "','" . $rs_details['date_out'] . "','" . $rs_details['weigh_out'] . "','" . $rs_details['gross'] . "','" . $rs_details['tare'] . "','" . $rs_details['net_weight'] . "','" . $rs_details['mc_perct'] . "','" . $rs_details['mc'] . "','" . $rs_details['dirt'] . "','" . $rs_details['corrected_weight'] . "','" . $rs_details['bales'] . "','" . $rs_details['remarks'] . "')");

            $sql_max = mysql_query("SELECT max(detail_id) FROM scale_recdet_forevaluation");
            $rs_max = mysql_fetch_array($sql_max);
            $ctr = $_POST['ctr'];
            $c = 0;
            while ($c < $ctr) {
                if ($_POST['detail_id_' . $c] == $rs_details['detail_id']) {
                    $_POST['detail_id_' . $c] = $rs_max['max(detail_id)'];
                }
                $c++;
            }
        }
        mysql_query("DELETE FROM scale_receiving_details WHERE trans_id='" . $_POST['trans_id'] . "'");

        $sql_out_id = mysql_query("SELECT * FROM scale_outgoing WHERE rec_trans_id='" . $_POST['trans_id'] . "'");
        $rs_out_id = mysql_fetch_array($sql_out_id);

        mysql_query("DELETE FROM scale_outgoing_details WHERE trans_id='" . $rs_out_id['trans_id'] . "'");

        $ctr = $_POST['ctr'];
        $c = 0;
        while ($c < $ctr) {
            if ($_POST['mat_' . $c] != '') {
                mysql_query("INSERT INTO `scale_receiving_details`(`trans_id`, `fe_detail_id`, `material_id`, `net_weight`, `mc`, `dirt`, `corrected_weight`, `remarks`)
                VALUES ('" . $_POST['trans_id'] . "','" . $_POST['detail_id_' . $c] . "','" . $_POST['mat_' . $c] . "','" . $_POST['net_weight' . $c] . "','" . $_POST['mc' . $c] . "','" . $_POST['dirt' . $c] . "','" . $_POST['corrected_wt' . $c] . "','" . $_POST['remarks' . $c] . "')");

                $sql_max = mysql_query("SELECT max(detail_id) FROM scale_receiving_details");
                $rs_max = mysql_fetch_array($sql_max);

                mysql_query("INSERT INTO `scale_outgoing_details`(`trans_id`,`material_id`, `net_weight`,`mc`,`dirt`,`corrected_weight`,`remarks`,`rec_detail_id`)
             VALUES ('" . $rs_out_id['trans_id'] . "','" . $_POST['mat_' . $c] . "','" . $_POST['net_weight' . $c] . "','" . $_POST['mc' . $c] . "','" . $_POST['dirt' . $c] . "','" . $_POST['net_weight' . $c] . "','" . $_POST['remarks' . $c] . "','" . $rs_max['max(detail_id)'] . "')");
            }
            $c++;
        }

        echo "<script>
        alert('Transaction successfully transfer to Payment System.');
        location.replace('save_receiving.php?trans_id=" . $_POST['trans_id'] . "');
        </script>";
    }
} else {

    $sql_trans = mysql_query("SELECT * FROM scale_receiving WHERE trans_id='" . $_GET['trans_id'] . "'");
    $rs_trans = mysql_fetch_array($sql_trans);
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_trans['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_trans['dt_id'] . "'");
    $rs_dt = mysql_fetch_array($sql_dt);

    echo "<center>";
    echo "<h2>Encoding Evaluation Result</h2>";
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
    echo "<td><input type='text' name='str_no' value='" . $rs_trans['str_no'] . "' readonly></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Series Number: </td>";
    echo "<td><input type='text' name='series_no' value='" . $rs_trans['series_no'] . "' readonly></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Delivered To: </td>";
    echo "<td>" . $rs_dt['name'] . "</td>";
    echo "</tr>";
    echo "</table>";



    echo "<table width='650'>";

    echo "<tr class='head2'>";
    echo "<td colspan='12' align='center'>FROM</td>";
    echo "</tr>";

    echo "<tr class='head2'>";
    echo "<td>Data Id</td>";
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
    $link_id = array();
    $link_data = array();
    $ctr = 0;
    $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $_GET['trans_id'] . "'");
    while ($rs_details = mysql_fetch_array($sql_details)) {
        $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
        $rs_mat = mysql_fetch_array($sql_mat);
        echo "<tr>";
        echo "<td><input class='input' type='text' value='DATA" . round($ctr + 1) . "'></td>";
        echo "<td>";

        echo "<select>";
        array_push($link_id, $rs_details['detail_id']);
        $link_data[$rs_details['detail_id']] = $rs_mat['code'];
        echo "<option value='" . $rs_details['material_id'] . "'>" . $rs_mat['code'] . "</option>";

        echo "</select>";
        echo "</td>";
        echo "<td><input class='input' type='text' value='" . $rs_details['gross'] . "' readonly></td>";
        echo "<td><input class='input' type='text' value='" . $rs_details['tare'] . "' readonly></td>";
        echo "<td><input class='input' type='text' value='" . $rs_details['net_weight'] . "' readonly></td>";

        echo "<td><input class='input' type='text' value='" . $rs_details['mc'] . "' readonly></td>";
        echo "<td><input class='input' type='text' value='" . $rs_details['dirt'] . "' readonly></td>";

        echo "<td><input class='input' type='text' value='" . round($rs_details['mc'] + $rs_details['dirt'], 2) . "' readonly></td>";
        echo "<td><input class='input' type='text' value='" . $rs_details['corrected_weight'] . "' readonly></td>";
        echo "<td><input class='remarks' type='text' value='" . $rs_details['remarks'] . "' readonly></td>";
        echo "</tr>";
        $ctr++;
    }

    echo "</table>";

    echo "<br><br>";

    echo "<form action='submit_for_evaluation.php' method='POST'>";
    echo "<input type='hidden' name='trans_id' value='" . $_GET['trans_id'] . "'>";
    echo "<table>";
    echo "<tr class='head2'>";
    echo "<td colspan='12' align='center'>TO</td>";
    echo "</tr>";

    echo "<tr class='head2'>";
    echo "<td>Data Id</td>";
    echo "<td>Material</td>";
    echo "<td>Net Wt.</td>";
    echo "<td>MC</td>";
    echo "<td>Dirt</td>";
    echo "<td>Adj Weight</td>";
    echo "<td>Corrected</td>";
    echo "<td>Remarks</td>";
    echo "</tr>";

    $ctr = 0;
    $c = 1;

    while ($ctr < 10) {
        echo "<tr id='row_$c'>";
        echo "<td>";
        echo "<select id='detail_id_$ctr' name='detail_id_$ctr'>";
        echo "<option value=''></option>";
        $cc = 1;
        foreach ($link_id as $id) {
            echo "<option value='$id'>DATA$cc - $link_data[$id]</option>";
            $cc++;
        }
        echo "</select>";
        echo "</td>";
        echo "<td>";
        echo "<select id='mat_$ctr' name='mat_$ctr'>";
        echo "<option value=''></option>";
        $sql_mat2 = mysql_query("SELECT * FROM material WHERE status!='deleted'");
        while ($rs_mat2 = mysql_fetch_array($sql_mat2)) {
            echo "<option value='" . $rs_mat2['material_id'] . "'>" . $rs_mat2['code'] . "</option>";
        }
        echo "</select>";
        echo "</td>";
        echo "<td><input class='input' type='text' id='netweight_$ctr' name='net_weight$ctr' value='' onkeyup='compute(this.id);'></td>";

        echo "<td><input class='input' type='text' id='mc_$ctr' name='mc$ctr' value='' onkeyup='compute(this.id);'></td>";
        echo "<td><input class='input' type='text' id='dirt_$ctr' name='dirt$ctr' value='' onkeyup='compute(this.id);'></td>";

        echo "<td><input class='input' type='text' id='less_weight_$ctr' name='less_weight$ctr' value='' readonly></td>";
        echo "<td><input class='input' type='text' id='corrected_wt_$ctr' name='corrected_wt$ctr' value='' readonly></td>";
        echo "<td><input class='remarks' type='text' name='remarks$ctr' value=''></td>";
        echo "</tr>";
        $c++;
        $ctr++;
    }
    echo "</table>";
    echo "<input type='hidden' name='ctr' value='$ctr'>";
    ?>
    <input type="submit" name="submit" value="Submit" onclick="return confirm('Are you sure you want to save? When you click [ok] this action cant be undo.')">
    <br>
    <br>
    <?php
    echo "</form>";
    echo '<div align="right" style="width: 800px;">
                    <input id="row_show" type="hidden" name="row_show" value="1" readonly>
                    <button id="plus" class="plusMinus">+</button> <button id="minus" class="plusMinus">-</button>
                </div>';
    ?>
    <font color="red">When you click submit, this action cant be undo.</font>
    <?php
    echo "</center>";
}
?>
