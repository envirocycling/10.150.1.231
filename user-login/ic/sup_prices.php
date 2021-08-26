<style>
    #prices{
        text-align:left;
    }
</style>
<div id="prices">

    <?php
    include("config.php");
    $supplier_id=$_GET['sup_id'];
    $query="SELECT *  FROM supplier where supplier_id='$supplier_id'  ";
    $result=mysql_query($query);
    $row = mysql_fetch_array($result);
    echo " <h3><u>".$row['supplier_name']. "</u>&nbsp; Price History </h3><hr>";

    $grade_array = array();

    $sql_grade = mysql_query("SELECT * FROM suppliers_price where supplier_id='$supplier_id' GROUP BY material_id");
    while ($rs_grade = mysql_fetch_array($sql_grade)) {
        array_push($grade_array, $rs_grade['material_id']);
    }

//    print_r ($grade_array);
    $log_id = array ();

    foreach($grade_array as $grade) {
        $sql_date = mysql_query("SELECT * FROM suppliers_price WHERE supplier_id='$supplier_id' and material_id='$grade' ORDER by date DESC");
        $rs_date = mysql_fetch_array($sql_date);
        array_push($log_id, $rs_date['id']);
    }

//    print_r ($log_id);

    $query="SELECT * FROM suppliers_price where supplier_id='$supplier_id' order by date desc ";
    $result=mysql_query($query);



    while($row = mysql_fetch_array($result)) {
        $ctr = 0;
        foreach ($log_id as $id) {
            if ($id == $row['id']) {
                echo "<b><font color='green'>";
                if($row['price']>0) {
                    $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$row['material_id']."'");
                    $rs_mat = mysql_fetch_array($sql_mat);
                    echo $rs_mat['code'];
                    echo " Price:";
                    echo " <u>".$row['price']."</u>";
                    echo "<br>Date Updated: ".$row['date'];
                    echo "<hr>";
                }
                echo "</font></b>";
                $ctr++;
            }
        }
        if ($ctr ==0) {
            if($row['price']>0) {
                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$row['material_id']."'");
                $rs_mat = mysql_fetch_array($sql_mat);
                echo $rs_mat['code'];
                echo "Price:";
                echo " <u>".$row['price']."</u>";
                echo "<br>Date Updated: ".$row['date'];
                echo "<hr>";
            }
        }
    }

    ?>

</div>