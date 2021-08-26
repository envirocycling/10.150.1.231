<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery.min.js" type="text/javascript"></script>

<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });
</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
</style>

<style>
    /*    body {
            margin-top: 10px;
            font: 12px/18px Arial, sans-serif;
        }  */
</style>
<base target="_parent" />
<?php
@session_start();
include 'config.php';
$ctr = $_POST['ctr'];

//$ctr2 = $_POST['ctrrrr'];
$c = 0;
//$c2 = 0;
echo "<table border='1' class='data display datatable' id='example'>";
echo "<thead>";
echo "<tr>";
echo "<th class='data'>Supplier Name</th>";
echo "<th class='data'>WP Grade</th>";
echo "<th class='data'>Prev Price</th>";
echo "<th class='data'>Less Price</th>";
echo "<th class='data'>Add Price</th>";
echo "<th class='data'>Cur Price</th>";
echo "</tr>";
echo "</thead>";
while ($c < $ctr) {
    $sql_temp_sup = mysql_query("SELECT * FROM temp_sup_id");
    while ($rs_temp_sup = mysql_fetch_array($sql_temp_sup)) {
        $supplier_id = $rs_temp_sup['supplier_id'];
        if ($_POST['type' . $c] != 'SET') {
            $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and supplier_id='$supplier_id' and material_id='" . $_POST['mat_id' . $c] . "' and date<='" .  $_POST['date'] . "' ORDER BY date DESC");
            $rs_sup_price = mysql_fetch_array($sql_sup_price);

            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='$supplier_id'");
            $rs_sup = mysql_fetch_array($sql_sup);
            echo "<tr>";
            echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";

            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $_POST['mat_id' . $c] . "'");
            $rs_mat = mysql_fetch_array($sql_mat);
            echo "<td>" . $rs_mat['code'] . "</td>";

            if ($rs_sup_price['price'] == '') {
                echo "<td colspan='4'><font color='red'>Cant update price, No found price in database.</font></td>";
            } else {
                echo "<td>" . $rs_sup_price['price'] . "</td>";
                if ($_POST['type' . $c] == 'LESS') {
                    echo "<td>" . $_POST['price' . $c] . "</td>";
                    echo "<td></td>";
                    echo "<td>" . number_format($rs_sup_price['price'] - $_POST['price' . $c], 2) . "</td>";
                    $price = round($rs_sup_price['price'] - $_POST['price' . $c], 2);
                } else {
                    echo "<td></td>";
                    echo "<td>" . $_POST['price' . $c] . "</td>";
                    echo "<td>" . number_format($rs_sup_price['price'] + $_POST['price' . $c], 2) . "</td>";
                    $price = round($rs_sup_price['price'] + $_POST['price' . $c], 2);
                }
                mysql_query("DELETE FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and material_id='" . $_POST['mat_id' . $c] . "' and supplier_id='$supplier_id' and date='" . $_POST['date'] . "'");

                mysql_query("INSERT INTO suppliers_price (`dt_id`, `material_id`, `supplier_id`, `price`, `user_id`, `date`, `date_encode`)
                    VALUES ('" . $_POST['dt_id'] . "','" . $_POST['mat_id' . $c] . "','$supplier_id','$price','" . $_SESSION['user_id'] . "','" . $_POST['date'] . "','" . date("Y/m/d") . "')");
            }
            echo "</tr>";
        } else {
            $sql_sup_price = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and supplier_id='$supplier_id' and material_id='" . $_POST['mat_id' . $c] . "' and date<='" .  $_POST['date'] . "' ORDER BY date DESC");
            $rs_sup_price = mysql_fetch_array($sql_sup_price);

            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='$supplier_id'");
            $rs_sup = mysql_fetch_array($sql_sup);

            echo "<tr>";
            echo "<td>" . $rs_sup['supplier_id'] . "-" . $rs_sup['supplier_name'] . "</td>";
            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $_POST['mat_id' . $c] . "'");
            $rs_mat = mysql_fetch_array($sql_mat);
            echo "<td>" . $rs_mat['code'] . "</td>";

            echo "<td>" . $rs_sup_price['price'] . "</td>";

            echo "<td></td>";
            echo "<td></td>";
            echo "<td>" . number_format($_POST['price' . $c], 2) . "</td>";

            echo "</tr>";

            mysql_query("DELETE FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and material_id='" . $_POST['mat_id' . $c] . "' and supplier_id='$supplier_id' and date='" . $_POST['date'] . "'");

            mysql_query("INSERT INTO suppliers_price (`dt_id`, `material_id`, `supplier_id`, `price`, `user_id`, `date`, `date_encode`)
                    VALUES ('" . $_POST['dt_id'] . "','" . $_POST['mat_id' . $c] . "','$supplier_id','" . $_POST['price' . $c] . "','" . $_SESSION['user_id'] . "','" . $_POST['date'] . "','" . date("Y/m/d") . "')");
        }
    }
    $c++;
}
echo "</table>";

mysql_query("DELETE FROM temp_sup_id");
?>
<script>
    alert('Successfully Update Price.');
</script>
<a href="../update_all_prices.php"><button>Back</button></a>