
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
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
<base target="_parent" />
<center>
    <h2>Pending Payment</h2>
</center>
<form action="../payment.php" method="POST">
    <?php
    include '../config.php';
    echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
<th></th>
            <th class="data" width="20">Date</th>
            <th class="data">STR No.</th>
            <th class="data">Supplier Name</th>';
    echo '</tr>
</thead>';
    $trans = "";
    $sql_pending = mysql_query("SELECT * FROM scale_receiving WHERE status='generated'");
    while ($rs_pending = mysql_fetch_array($sql_pending)) {
        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_pending['supplier_id'] . "'");
        $rs_sup = mysql_fetch_array($sql_sup);
        if (!empty($trans)) {
            $trans.="_" . $rs_pending['trans_id'];
        } else {
            $trans.=$rs_pending['trans_id'];
        }
        echo "<tr>";
        echo "<td><input type='checkbox' name='" . $rs_pending['trans_id'] . "' value='pay'></td>";
        echo "<td>" . $rs_pending['date'] . "</td>";
        echo "<td>" . $rs_pending['str_no'] . "</td>";
        echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>

    <div class="button">
        <input type="submit" class="submit" name="submit" value="Submit">
    </div>
</form>