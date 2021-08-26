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
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
$ctr = $_POST['ctr'];

$c = 0;
echo "<table border='1' class='data display datatable' id='example'>";
echo "<thead>";
echo "<tr>";
echo "<th class='data'>Supplier Name</th>";
echo "<th class='data'>WP Grade</th>";
echo "<th class='data'>Prev Price</th>";
echo "<th class='data'>Less Price</th>";
echo "<th class='data'>Add Price</th>";
echo "<th class='data'>Cur Price</th>";
//echo "<td>Query</td>";
echo "</tr>";
echo "</thead>";
while ($c < $ctr) {
    if (!empty($_POST['type' . $c]) && !empty($_POST['price' . $c])) {
        if ($_POST['type' . $c] != 'SET') {
            $sql_sup = mysql_query("SELECT * FROM suppliers_price INNER JOIN supplier ON suppliers_price.supplier_id=supplier.id WHERE suppliers_price.dt_id='" . $_POST['dt_id'] . "' and suppliers_price.material_id='" . $_POST['mat_id' . $c] . "' and supplier.branch like '%" . $_POST['branch'] . "%' and suppliers_price.price>0 GROUP BY suppliers_price.supplier_id");
            while ($rs_sup = mysql_fetch_array($sql_sup)) {
                $sql_sup_prices = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and material_id='" . $_POST['mat_id' . $c] . "' and date<='" . $_POST['date'] . "' and supplier_id='" . $rs_sup['id'] . "' ORDER BY date DESC");
                $rs_sup_prices = mysql_fetch_array($sql_sup_prices);
                echo "<tr>";
                echo "<td class='data'>" . $rs_sup['supplier_id'] . "-" . $rs_sup['supplier_name'] . "</td>";
                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $_POST['mat_id' . $c] . "'");
                $rs_mat = mysql_fetch_array($sql_mat);
                echo "<td class='data'>" . $rs_mat['code'] . "</td>";

                echo "<td class='data'>" . $rs_sup_prices['price'] . "</td>";
                if ($_POST['type' . $c] == 'LESS') {
                    echo "<td class='data'>" . $_POST['price' . $c] . "</td>";
                    echo "<td class='data'></td>";
                    echo "<td class='data'>" . number_format($rs_sup_prices['price'] - $_POST['price' . $c], 2) . "</td>";
                    $price = round($rs_sup_prices['price'] - $_POST['price' . $c], 2);
                } else {
                    echo "<td class='data'></td>";
                    echo "<td class='data'>" . $_POST['price' . $c] . "</td>";
                    echo "<td class='data'>" . number_format($rs_sup_prices['price'] + $_POST['price' . $c], 2) . "</td>";
                    $price = round($rs_sup_prices['price'] + $_POST['price' . $c], 2);
                }
                echo "</tr>";

                mysql_query("DELETE FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and material_id='" . $_POST['mat_id' . $c] . "' and supplier_id='" . $rs_sup['id'] . "' and date='" . $_POST['date'] . "'");

                mysql_query("INSERT INTO suppliers_price (`dt_id`,`material_id`, `supplier_id`, `price`, `user_id`, `date`, `date_encode`)
                    VALUES ('" . $_POST['dt_id'] . "','" . $_POST['mat_id' . $c] . "','" . $rs_sup['id'] . "','$price','" . $_SESSION['user_id'] . "','" . $_POST['date'] . "','" . date("Y/m/d") . "')");
            }
        } else {
            $sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%" . $_POST['branch'] . "%'");
            while ($rs_sup = mysql_fetch_array($sql_sup)) {

                $sql_sup_prices = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and material_id='" . $_POST['mat_id' . $c] . "' and date<='" . $_POST['date'] . "' and supplier_id='" . $rs_sup['id'] . "' ORDER BY date DESC");
                $rs_sup_prices = mysql_fetch_array($sql_sup_prices);

                echo "<tr>";
                echo "<td class='data'>" . $rs_sup['supplier_id'] . "-" . $rs_sup['supplier_name'] . "</td>";
                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $_POST['mat_id' . $c] . "'");
                $rs_mat = mysql_fetch_array($sql_mat);
                echo "<td class='data'>" . $rs_mat['code'] . "</td>";

                echo "<td class='data'>" . $rs_sup_prices['price'] . "</td>";

                echo "<td class='data'></td>";
                echo "<td class='data'></td>";
                echo "<td class='data'>" . number_format($_POST['price' . $c], 2) . "</td>";

                echo "</tr>";

                mysql_query("DELETE FROM suppliers_price WHERE dt_id='" . $_POST['dt_id'] . "' and material_id='" . $_POST['mat_id' . $c] . "' and supplier_id='" . $rs_sup['id'] . "' and date='" . $_POST['date'] . "'");

                mysql_query("INSERT INTO suppliers_price (`dt_id`, `material_id`, `supplier_id`, `price`, `user_id`, `date`, `date_encode`)
                    VALUES ('" . $_POST['dt_id'] . "','" . $_POST['mat_id' . $c] . "','" . $rs_sup['id'] . "','" . $_POST['price' . $c] . "','" . $_SESSION['user_id'] . "','" . $_POST['date'] . "','" . date("Y/m/d") . "')");
            }
        }
    }
    $c++;
}
echo "</table>";
?>
<script>
    alert('Successfully Update Price.');
</script>
<a href="../update_all_prices.php"><button>Back</button></a>