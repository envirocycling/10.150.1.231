<style>
    #prices{
        text-align:left;

    }
</style>
<div id="prices">

    <?php
    include("config.php");
    $supplier_id = $_GET['sup_id'];
    $query = "SELECT *  FROM supplier where id='$supplier_id'  ";
    $result = mysql_query($query);
    $row = mysql_fetch_array($result);
    echo " <h3><u>" . $row['supplier_name'] . "</u>&nbsp; Price History </h3><hr>";

    $grade_array = array();

    $sql_grade = mysql_query("SELECT * FROM suppliers_price where supplier_id='$supplier_id' GROUP BY material_id");
    while ($rs_grade = mysql_fetch_array($sql_grade)) {
        array_push($grade_array, $rs_grade['material_id']);
    }

    echo "<table border='1' width='500' cellspacing='0'>";
    echo "<tr>";
    echo "<td><h3>TIPCO/MULTIPLY</h3></td>";
    echo "<td><h3>FSI</h3></td>";
    echo "</tr>";
    echo "<tr style='vertical-align: top;'>";
    echo "<td>";

    $log_id = array();

    foreach ($grade_array as $grade) {
        $sql_date = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='1' and supplier_id='$supplier_id' and material_id='$grade' ORDER by date DESC");
        $rs_date = mysql_fetch_array($sql_date);
        array_push($log_id, $rs_date['id']);
    }

    $query = "SELECT * FROM suppliers_price where dt_id='1' and supplier_id='$supplier_id' order by date desc ";
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result)) {
        $ctr = 0;
        foreach ($log_id as $id) {
            if ($id == $row['id']) {
                echo "<b><font color='green'>";
                if ($row['price'] > 0) {
                    $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $row['material_id'] . "'");
                    $rs_mat = mysql_fetch_array($sql_mat);
                    echo $rs_mat['code'];
                    echo " Price:";
                    echo " <u>" . $row['price'] . "</u>";
                    echo "<br>Date Updated: " . $row['date'];
                    echo "<hr>";
                }
                echo "</font></b>";
                $ctr++;
            }
        }
        if ($ctr == 0) {
            if ($row['price'] > 0) {
                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $row['material_id'] . "'");
                $rs_mat = mysql_fetch_array($sql_mat);
                echo $rs_mat['code'];
                echo "Price:";
                echo " <u>" . $row['price'] . "</u>";
                echo "<br>Date Updated: " . $row['date'];
                echo "<hr>";
            }
        }
    }
    echo "</td>";
    echo "<td style='vetical-align: top;'>";

    unset($log_id);

    $log_id = array();

    foreach ($grade_array as $grade) {
        $sql_date = mysql_query("SELECT * FROM suppliers_price WHERE dt_id='3' and supplier_id='$supplier_id' and material_id='$grade' ORDER by date DESC");
        $rs_date = mysql_fetch_array($sql_date);
        array_push($log_id, $rs_date['id']);
    }

    $query = "SELECT * FROM suppliers_price where dt_id='3' and supplier_id='$supplier_id' order by date desc ";
    $result = mysql_query($query);

    while ($row = mysql_fetch_array($result)) {
        $ctr = 0;
        foreach ($log_id as $id) {
            if ($id == $row['id']) {
                echo "<b><font color='green'>";
                if ($row['price'] > 0) {
                    $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $row['material_id'] . "'");
                    $rs_mat = mysql_fetch_array($sql_mat);
                    echo $rs_mat['code'];
                    echo " Price:";
                    echo " <u>" . $row['price'] . "</u>";
                    echo "<br>Date Updated: " . $row['date'];
                    echo "<hr>";
                }
                echo "</font></b>";
                $ctr++;
            }
        }
        if ($ctr == 0) {
            if ($row['price'] > 0) {
                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $row['material_id'] . "'");
                $rs_mat = mysql_fetch_array($sql_mat);
                echo $rs_mat['code'];
                echo "Price:";
                echo " <u>" . $row['price'] . "</u>";
                echo "<br>Date Updated: " . $row['date'];
                echo "<hr>";
            }
        }
    }
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    ?>

</div>