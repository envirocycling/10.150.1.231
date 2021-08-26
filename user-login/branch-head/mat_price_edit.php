<?php
include 'config.php';
if (isset ($_POST['submit'])) {
    $sql_check = mysql_query("SELECT * FROM default_price WHERE material_id='".$_POST['mat_id']."'");
    $rs_count = mysql_num_rows($sql_check);
    if ($rs_count != '0') {
        mysql_query("UPDATE default_price SET price='".$_POST['price']."' WHERE material_id='".$_POST['mat_id']."'");
        echo "<script >
                location . replace('iframe/query_mat_prices.php');
        </script>";
    } else {
        mysql_query("INSERT INTO default_price (material_id,price) VALUES ('".$_POST['mat_id']."','".$_POST['price']."')");
        echo "<script >
                location . replace('iframe/query_mat_prices.php');
        </script>";
    }
}
$sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$_GET['material_id']."'");
$rs_mat = mysql_fetch_array($sql_mat);
$sql_price = mysql_query("SELECT * FROM default_price WHERE material_id='".$rs_mat['material_id']."'");
$rs_price = mysql_fetch_array($sql_price);
?>
<center>
    <h2>Edit Material Price</h2>
    <form action="../mat_price_edit.php" method="POST">
        <input type="hidden" name="mat_id" value="<?php echo $rs_mat['material_id'];?>">
        <table>
            <tr>
                <td><?php echo $rs_mat['code'];?></td>
                <td><input type="text" name="price" value="<?php echo $rs_price['price'];?>"></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td>
            </tr>
        </table>
    </form>
</center>