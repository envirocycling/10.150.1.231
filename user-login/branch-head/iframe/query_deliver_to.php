
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
            <th class="data">Name</th>
            <th class="data">Details</th>
            <th class="data">Date Added</th>';
//             <th class="data">Action</th>
echo '</tr>
        </thead>';
$sql_del = mysql_query("SELECT * FROM delivered_to");
while ($rs_del = mysql_fetch_array($sql_del)) {
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_del['dt_id'] . "</td>";
    echo "<td class='data'>" . $rs_del['name'] . "</td>";
    echo "<td class='data'>" . $rs_del['details'] . "</td>";
    echo "<td class='data'>" . $rs_del['date_added'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>