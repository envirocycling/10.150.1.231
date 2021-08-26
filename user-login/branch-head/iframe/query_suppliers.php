
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
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
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
            <th class="data">Supplier Name</th>
            <th class="data">Owner Name</th>
            <th class="data">Contact</th>
            <th class="data">Branch</th>';
echo '</tr>
        </thead>';

$sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%" . $_GET['branch'] . "%'");
while ($rs_sup = mysql_fetch_array($sql_sup)) {
//    $sql_trucks = mysql_query("SELECT count(plate_number) FROM plate_number WHERE supplier_id='" . $rs_sup['id'] . "'");
//    $rs_trucks = mysql_fetch_array($sql_trucks);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "</td>";
    echo "<td class='data'><a rel='facebox' href='../edit_supplier.php?sup_id=" . $rs_sup['id'] . "'>" . $rs_sup['supplier_name'] . "</a></td>";
    echo "<td class='data'>" . $rs_sup['owner_name'] . "</td>";
    echo "<td class='data'>" . $rs_sup['owner_contact'] . "</td>";
    echo "<td class='data'>" . $rs_sup['branch'] . "</td>";
//    echo "<td class='data'>" . $rs_sup['date_added'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>