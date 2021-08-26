
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
<script>
    function openWindow(str) {
        window.open("../out_void.php?trans_id=" + str, 'mywindow', 'width=600,height=280,left=250,top=50');

    }
</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
</style>
<base target="_parent" />
<?php
include 'config.php';
$sql_out = mysql_query("SELECT * FROM scale_outgoing WHERE status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
echo '<table class="data display datatable" id="example">
<thead>        
<tr class="data">
            <th class="data">Priority No.</th>
            <th class="data">Trucking Name</th>
            <th class="data">Plate No.</th>
            <th class="data">Grade</th>
             <th class="data">Action</th>
        </tr>
        </thead>';

while ($rs_out = mysql_fetch_array($sql_out)) {
    $sql_trucking = mysql_query("SELECT * FROM trucking WHERE trucking_id='" . $rs_out['trucking_id'] . "'");
    $rs_trucking = mysql_fetch_array($sql_trucking);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_out['priority_no'] . "</td>";
    echo "<td class='data'>" . $rs_trucking['trucking_name'] . "</td>";
    echo "<td class='data'>" . $rs_out['plate_number'] . "</td>";
    $sql_count = mysql_query("SELECT count(detail_id) FROM scale_outgoing_details WHERE trans_id='" . $rs_out['trans_id'] . "'");
    $rs_count = mysql_fetch_array($sql_count);
    echo "<td class='data'>" . $rs_count['count(detail_id)'] . "</td>";
    echo "<td class='data'>
        <button id='" . $rs_out['trans_id'] . "' class='submit' onclick='openWindow(this.id);'>Void</button>
        <a href='../outgoing_ticket.php?trans_id=" . $rs_out['trans_id'] . "'><button class='submit'>View</button></a>
        </td>";
    echo "</tr>";
}
echo "</table>";
?>