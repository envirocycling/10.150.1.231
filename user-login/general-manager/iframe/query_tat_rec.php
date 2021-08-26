
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
<?php
include 'config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="80">Priority No.</th>
            <th class="data">Supplier Name</th>
            <th class="data"># of Grades</th>
            <th class="data">Actual Weight</th>
            <th class="data">Arrival</th>
            <th class="data">Start</th>
            <th class="data">Finish</th>
            <th class="data">Queue</th>
            <th class="data">Unloading</th>
            <th class="data">Total</th>
            <th class="data">-----</th>
        </tr>
        </thead>';
$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_rec['date'] . "</td>";
    echo "<td class='data'>" . $rs_rec['priority_no'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_id']."_".$rs_sup['supplier_name'] . "</td>";
    $sql_count = mysql_query("SELECT count(detail_id),sum(corrected_weight) FROM scale_receiving_details WHERE trans_id='".$rs_rec['trans_id']."'");
    $rs_count = mysql_fetch_array($sql_count);
    echo "<td class='data'>" . $rs_count['count(detail_id)'] . "</td>";
    echo "<td class='data'>" . $rs_count['sum(corrected_weight)'] . "</td>";
    echo "<td class='data'>" . date("h:i a",strtotime($rs_rec['arrival_time'])) . "</td>";
    echo "<td class='data'>" . date("h:i a",strtotime($rs_rec['start_time'])) . "</td>";
    echo "<td class='data'>" . date("h:i a",strtotime($rs_rec['finish_time'])) . "</td>";
    echo "<td class='data'>" . date("H:i",strtotime($rs_rec['queue_time'])) . "</td>";
    echo "<td class='data'>" . date("H:i",strtotime($rs_rec['unload_time'])) . "</td>";
    echo "<td class='data'>" . date("H:i",strtotime($rs_rec['total_time'])) . "</td>";
    echo "<td class='data'>".$rs_rec['remarks']."</td>";
    echo "</tr>";

}
echo "</table>";
?>