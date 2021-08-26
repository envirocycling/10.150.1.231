
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

if (isset ($_GET['status'])){
    mysql_query("UPDATE supplier SET manual_encoding='".$_GET['status']."' WHERE id='".$_GET['supplier_id']."'");
}

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="20">ID</th>
            <th class="data">Supplier Name</th>
            <th class="data">Owner Name</th>
            <th class="data">Manual Encoding</th>
            <th class="data">Action</th>';
echo '</tr>
        </thead>';

$sql_sup = mysql_query("SELECT * FROM supplier");
while ($rs_sup = mysql_fetch_array($sql_sup)) {
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_name'] . "</td>";
    echo "<td class='data'>" . $rs_sup['owner_name'] . "</td>";

    if ($rs_sup['manual_encoding'] == '') {
        echo "<td class='data'>OFF</td>";
        echo "<td class='data'><a href='query_suppliers_manual_encoding.php?status=on&supplier_id=".$rs_sup['id']."'><button>ON</button></a></td>";
    } else {
        echo "<td class='data'>" . strtoupper($rs_sup['manual_encoding']) . "</td>";
        echo "<td class='data'><a href='query_suppliers_manual_encoding.php?status=off&supplier_id=".$rs_sup['id']."'><button>OFF</button></a></td>";
    }

    echo "</tr>";
}
echo "</table>";
?>