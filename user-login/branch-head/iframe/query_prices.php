
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
<?php
include 'config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="20">ID</th>
            <th class="data">Breaktime Name</th>
            <th class="data">Start Time</th>
            <th class="data">End Time</th>
            <th class="data">Duration</th>
            <th class="data">Action</th>';
echo '</tr>
</thead>';
$sql_breaktime = mysql_query("SELECT * FROM breaktime WHERE break_id!='6'");
while ($rs_breaktime = mysql_fetch_array($sql_breaktime)) {
    echo "<tr class='
    data'>";
    echo "<td class='data'>" . $rs_breaktime['break_id'] . "</td>";
    echo "<td class='data'>" . ucfirst($rs_breaktime['break_name']) . "</td>";
    echo "<td class='data'>" . date("h:i a", strtotime($rs_breaktime['start_time'])) . "</td>";
    echo "<td class='data'>" . date("h:i a", strtotime($rs_breaktime['end_time'])) . "</td>";
    echo "<td class='data'>" . date("H:i", strtotime($rs_breaktime['duration'])) . "</td>";
    echo "<td class='data'><a rel='facebox' href='../breaktime_edit.php?break_id=" . $rs_breaktime['break_id'] . "'><button>Edit</button></a></td>";
    echo "</tr>";
}
echo "</table>";
?>