
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
include '../config.php';

echo "";
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="20">ID</th>
            <th class="data">Supplier Name</th>
            <th class="data">Cheque Name</th>
            <th class="data">Back Accounts</th>
            <th class="data">Owner Name</th>
            <th class="data">Contact</th>
            <th class="data">Prices</th>';
//             <th class="data">Action</th>
echo '</tr>
        </thead>';
$sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%".$_GET['branch']."%'");
while ($rs_sup = mysql_fetch_array($sql_sup)) {
    $sql_name = mysql_query("SELECT count(id) FROM cheque_name WHERE supplier_id='".$rs_sup['id']."'");
    $rs_name = mysql_fetch_array($sql_name);
    $sql_acc = mysql_query("SELECT count(bank_account_id) FROM sup_bank_accounts WHERE supplier_id='".$rs_sup['id']."'");
    $rs_acc = mysql_fetch_array($sql_acc);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_name'] . "</td>";
    echo "<td class='data'><a rel='facebox' href='../suppliers_cheque_name.php?sup_id=" . $rs_sup['id'] . "_" . $rs_sup['supplier_name'] . "'>" . $rs_name['count(id)'] . "</a></td>";
    echo "<td class='data'><a rel='facebox' href='../suppliers_bank_account.php?sup_id=" . $rs_sup['id'] . "_" . $rs_sup['supplier_name'] . "'>" . $rs_acc['count(bank_account_id)'] . "</a></td>";
    echo "<td class='data'>" . $rs_sup['owner_name'] . "</td>";
    echo "<td class='data'>" . $rs_sup['owner_contact'] . "</td>";
    echo "<td class='data'><a rel='facebox' href='../edit_prices.php?sup_id=" . $rs_sup['id'] . "_" . $rs_sup['supplier_name'] . "'><button>View</button></a><a rel='facebox' href='../sup_prices.php?sup_id=" . $rs_sup['id'] . "'><button>Prices</button></a></td>";
    echo "</tr>";
}
echo "</table>";
?>