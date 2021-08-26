
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
<script>
    function openWindow(str) {
        window.open("../view_tipco.php?scale_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');

    }
</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .submitq {
        height: 20px;
        width: 60px;
    }
    .total {
        background-color: yellow;
        font-weight: bold;
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
include '../configTPTS.php';

$total_weight = 0;
$total_less_weight = 0;
$corrected_weight = 0;
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="80">WS #</th>
            <th class="data" width="80">STR #</th>
            <th class="data" width="80">Supplier Name</th>
            <th class="data">Plate #</th>            
            <th class="data">Delivered To</th>
            <th class="data">Status</th>
            <th class="data">Action</th>
        </tr>
        </thead>';

$sql_rec = mysql_query("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') and scale.status!='DELETED' and scale.date>='" . $_GET['from'] . "' and scale.date<='" . $_GET['to'] . "' and supplier.owner='EFI'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_comp = mysql_query("SELECT * FROM company WHERE company_id='" . $rs_rec['company_id'] . "'");
    $rs_comp = mysql_fetch_array($sql_comp);

    $sql_sup = mysql_query("SELECT * FROM supplier WHERE supplier_id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);

    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_rec['date'] . "</td>";
    if ($rs_rec['ws_no'] == '0') {
        echo "<td class='data'></td>";
    } else {
        echo "<td class='data'>WS" . sprintf("%06s", $rs_rec['ws_no']) . "</td>";
    }
    echo "<td class='data'>" . $rs_rec['str_no'] . "</td>";
    echo "<td class='data'>" . strtoupper($rs_sup['name']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_rec['plate_no']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_comp['name']) . "</td>";
    if ($rs_rec['status1'] == '') {
        echo "<td class='data'>PENDING</td>";
    } else {
        echo "<td class='data'>" . strtoupper($rs_rec['status1']) . "</td>";
    }
    echo "<td class='data'>";
    echo "<button id='" . $rs_rec['scale_id'] . "' onclick='openWindow(this.id);' class='button'>View</button>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>