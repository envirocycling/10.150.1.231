
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
            <th class="data">Trucking Name</th>
            <th class="data">Trucks</th>
            <th class="data">Owner Name</th>
            <th class="data">Contact</th>
            <th class="data">Date Added</th>';
//             <th class="data">Action</th>
echo '</tr>
        </thead>';
$sql_truck = mysql_query("SELECT * FROM trucking");
while ($rs_truck = mysql_fetch_array($sql_truck)) {
    $sql = mysql_query("SELECT count(plate_number) FROM plate_number_trucking WHERE trucking_id='".$rs_truck['trucking_id']."'");
    $rs = mysql_fetch_array($sql);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_truck['trucking_id'] . "</td>";
    echo "<td class='data'>" . $rs_truck['trucking_name'] . "</td>";
    echo "<td class='data'><a rel='facebox' href='../trucking_trucks.php?sup_id=" . $rs_truck['trucking_id'] . "_" . $rs_truck['trucking_name'] . "'>" . $rs['count(plate_number)'] . "</a></td>";
    echo "<td class='data'>" . $rs_truck['owner_name'] . "</td>";
    echo "<td class='data'>" . $rs_truck['owner_contact'] . "</td>";
    echo "<td class='data'>" . $rs_truck['date_added'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>