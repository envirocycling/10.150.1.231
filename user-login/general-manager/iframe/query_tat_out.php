
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
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>
<base target="_parent" />
<?php
include 'config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="80">P No.</th>
            <th class="data">Truckers Name</th>
            <th class="data"># of Grades</th>
            <th class="data">Actual Weight</th>
            <th class="data">Arrival</th>
            <th class="data">Loading</th>
            <th class="data">Finish</th>
            <th class="data">Loading TAT</th>
            <th class="data">TAT</th>
            <th class="data">Departure</th>
            <th class="data">Complete TAT</th>
            <th class="data">-----</th>
        </tr>
        </thead>';

$sql_out = mysql_query("SELECT * FROM scale_outgoing WHERE date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
while ($rs_out = mysql_fetch_array($sql_out)) {
    $sql_trucking = mysql_query("SELECT * FROM trucking WHERE trucking_id='" . $rs_out['trucking_id'] . "'");
    $rs_trucking = mysql_fetch_array($sql_trucking);
    echo "<tr>";
    echo "<td>".$rs_out['date']."</td>";
    echo "<td>".$rs_out['priority_no']."</td>";
    echo "<td>".$rs_trucking['trucking_name']."</td>";
    $sql_count = mysql_query("SELECT count(detail_id),sum(net_weight) FROM scale_outgoing_details WHERE trans_id='".$rs_out['trans_id']."'");
    $rs_count = mysql_fetch_array($sql_count);
    echo "<td>".$rs_count['count(detail_id)']."</td>";
    echo "<td>".$rs_count['sum(net_weight)']."</td>";
    echo "<td>".$rs_out['arrival_time']."</td>";
    echo "<td>".$rs_out['loading_time']."</td>";
    echo "<td>".$rs_out['finish_time']."</td>";
    echo "<td>".$rs_out['loading_tat']."</td>";
    echo "<td>".$rs_out['tat']."</td>";
    echo "<td>".$rs_out['departure_time']."</td>";
    echo "<td>".$rs_out['complete_tat']."</td>";
    echo "<td class='data'>".$rs_out['remarks']."</td>";
    echo "</tr>";

}
echo "</table>";
?>