<style>
    input{
        width: 70px;
    }
    .date{
        width: 90px;
    }
</style>
<?php
date_default_timezone_set('Asia/Manila');
@session_start();
include 'config.php';

if (isset($_POST['submit'])) {
    $c = $_POST['tm_c'];
    $ctr = 0;
    while ($ctr < $c) {
        if ($_POST['tm_price' . $ctr] != '') {
            mysql_query("DELETE FROM suppliers_price WHERE dt_id='1' and material_id='" . $_POST['tm_mat_id' . $ctr] . "' and supplier_id='" . $_POST['sup_id'] . "' and date='" . $_POST['date'] . "'");

            mysql_query("INSERT INTO suppliers_price (`dt_id`, `material_id`, `supplier_id`, `price`, `user_id`, `date`, `date_encode`)
				VALUES ('1','" . $_POST['tm_mat_id' . $ctr] . "','" . $_POST['sup_id'] . "','" . $_POST['tm_price' . $ctr] . "','" . $_SESSION['user_id'] . "','" . $_POST['date'] . "','" . date("Y/m/d") . "')");
        }
        $ctr++;
    }

    $c = $_POST['fsi_c'];
    $ctr = 0;
    while ($ctr < $c) {
        if ($_POST['fsi_price' . $ctr] != '') {
            mysql_query("DELETE FROM suppliers_price WHERE dt_id='3' and material_id='" . $_POST['fsi_mat_id' . $ctr] . "' and supplier_id='" . $_POST['sup_id'] . "' and date='" . $_POST['date'] . "'");

            mysql_query("INSERT INTO suppliers_price (`dt_id`, `material_id`, `supplier_id`, `price`, `user_id`, `date`, `date_encode`)
				VALUES ('3','" . $_POST['fsi_mat_id' . $ctr] . "','" . $_POST['sup_id'] . "','" . $_POST['fsi_price' . $ctr] . "','" . $_SESSION['user_id'] . "','" . $_POST['date'] . "','" . date("Y/m/d") . "')");
        }
        $ctr++;
    }

    echo "<script>
        location.replace('iframe/query_sup_prices.php?branch=" . $_GET['branch'] . "');
        </script>";
} else {
    $que = preg_split("[_]", $_GET['sup_id']);
    echo "<form action='../edit_prices.php?branch=" . $_GET['branch'] . "' method='POST'>";
    echo "<h2>$que[1] Pricing</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<td>";
    echo "<table>";
    echo "<tr>";
    echo "<td colspan='2' align='center'><b>TIPCO/MULTIPLY</b></td>";
    echo "</tr>";
    echo "<input type='hidden' name='sup_id' value='$que[0]'>";
    $ctr = 0;
    $sql_mat = mysql_query("SELECT * FROM material WHERE status!='deleted' && code!='OTHERS'");
    while ($rs_mat = mysql_fetch_array($sql_mat)) {
        $sql_price = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='1' and material_id='" . $rs_mat['material_id'] . "' and supplier_id='" . $que[0] . "' and date<='" . date("Y/m/d") . "' ORDER BY date DESC");
        $rs_price = mysql_fetch_array($sql_price);
        echo "<tr>";
        echo "<td>" . $rs_mat['code'] . "</td>";
        echo "<td>
<input type='hidden' name='tm_mat_id$ctr' value='" . $rs_mat['material_id'] . "'>";
        if ($rs_price['price'] == '') {
            $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $rs_mat['material_id'] . "'");
            $rs_def_price = mysql_fetch_array($sql_def_price);
            echo "<input type='hidden' name='tm_old_price$ctr' value=''>";
            echo "<input type='number' name='tm_price$ctr' value='" . $rs_def_price['price'] . "' size='5' step='0.01' min='00.01' max='99.99'></td>";
        } else {
            echo "<input type='hidden' name='tm_old_price$ctr' value='" . $rs_price['price'] . "'>";
            echo "<input type='number' name='tm_price$ctr' value='" . $rs_price['price'] . "' size='5' step='0.01' min='00.01' max='99.99' required></td>";
        }
        echo "</tr>";
        $ctr++;
    }

    echo "</table>";
    echo "<input type='hidden' name='tm_c' value='$ctr'>";
    echo "</td>";
    echo "<td>";

    echo "<table>";
    echo "<tr>";
    echo "<td colspan='2' align='center'><b>FSI</b></td>";
    echo "</tr>";
    echo "<input type='hidden' name='sup_id' value='$que[0]'>";
    $ctr = 0;
    $sql_mat = mysql_query("SELECT * FROM material WHERE status!='deleted' && code!='OTHERS'");
    while ($rs_mat = mysql_fetch_array($sql_mat)) {
        $sql_price = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='3' and material_id='" . $rs_mat['material_id'] . "' and supplier_id='" . $que[0] . "' and date<='" . date("Y/m/d") . "' ORDER BY date DESC");
        $rs_price = mysql_fetch_array($sql_price);
        echo "<tr>";
        echo "<td>" . $rs_mat['code'] . "</td>";
        echo "<td>
<input type='hidden' name='fsi_mat_id$ctr' value='" . $rs_mat['material_id'] . "'>";
        if ($rs_price['price'] == '') {
            $sql_def_price = mysql_query("SELECT * FROM default_price WHERE material_id='" . $rs_mat['material_id'] . "'");
            $rs_def_price = mysql_fetch_array($sql_def_price);
            echo "<input type='hidden' name='fsi_old_price$ctr' value=''>";
            echo "<input type='number' name='fsi_price$ctr' value='" . $rs_def_price['price'] . "' size='5' step='0.01' min='00.01' max='99.99'></td>";
        } else {
            echo "<input type='hidden' name='fsi_old_price$ctr' value='" . $rs_price['price'] . "'>";
            echo "<input type='number' name='fsi_price$ctr' value='" . $rs_price['price'] . "' size='5' step='0.01' min='00.01' max='99.99' required></td>";
        }
        echo "</tr>";
        $ctr++;
    }
    echo "</table>";
    echo "<input type='hidden' name='fsi_c' value='$ctr'>";
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='2'>&nbsp;</td>";
    echo "</tr>";
    echo "<tr>";
    echo '<td colspan="2" align="center">Date Effectivity: <input class = "date" type = "text" name = "date" value = "' . date("Y/m/d") . '" size = "10" required></td>';
    echo "</tr>";
    echo "</tr>";
    echo "<td colspan='2' align='center'><font size='2' color='red'>Format (YYYY/MM/DD)</font></td>";
    echo "</tr>";
    echo "<tr>";
    echo '<td colspan = "2" align="center"><input type = "submit" name = "submit" value = "Submit"></td>';
    echo "</tr>";
    echo "</table>";
    echo "</form>";
}
?>
