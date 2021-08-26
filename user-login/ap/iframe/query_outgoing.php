<?php
session_start();
?>
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
<script>
    function openWindow(str) {
        document.getElementById(str).value = "Edit";
        window.open("../save_outgoing.php?trans_id=" + str, 'mywindow', 'width=900,height=500,left=180,top=20');
    }
    function openWindow2(str) {
        window.open("../edit_out_transaction.php?trans_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');
    }
    function openWindow3(str) {
        window.open("../view_out_trans_details.php?trans_id=" + str, 'mywindow', 'width=900,height=500,left=180,top=20');
    }
</script>
<?php
include '../config.php';
$total_weight = 0;
$total_less_weight = 0;
$corrected_weight = 0;
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="80">STR #</th>
            <th class="data" width="80">Series #</th>
            <th class="data" width="80">Supplier Name</th>
            <th class="data">Plate #</th>            
            <th class="data">Delivered To</th>
            <th class="data">Branch</th>
            <th class="data">Action</th>
        </tr>
        </thead>';
$sql_rec = mysql_query("SELECT * FROM scale_outgoing WHERE branch_id!='7' and status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_count = mysql_query("SELECT * FROM scale_outgoing_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
    $rs_count = mysql_num_rows($sql_count);

    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
	
	$my_branch = mysql_query("SELECT * from branches WHERE branch_id='".$rs_rec['branch_id']."'")or die (mysql_error());
	$myb_branch_row = mysql_fetch_array($my_branch);

    $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_rec['dt_id'] . "'");
    $rs_dt = mysql_fetch_array($sql_dt);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_rec['date'] . "</td>";
    echo "<td class='data'>" . $rs_rec['str_no'] . "</td>";
    echo "<td class='data'>" . $rs_rec['series_no'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "_" . strtoupper($rs_sup['supplier_name']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_rec['plate_number']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_dt['name']) . "</td>";
    echo "<td class='data'>" . strtoupper($myb_branch_row['branch_name']) . "</td>";
    echo "<td class='data'>";
    if ($rs_rec['checked'] == '0' && ($_SESSION['user_id'] == 29  || $_SESSION['user_id'] == 40)) {
        echo "<input type='submit' id='" . $rs_rec['trans_id'] . "' onclick='openWindow(this.id);' class='button' name='save' value='Save'> ";
    } else  if($_SESSION['user_id'] == 29  || $_SESSION['user_id'] == 40){
        echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow(this.id);' class='button'>Edit</button> ";
    }
    if ($rs_count > 1 && ($_SESSION['user_id'] == 29  || $_SESSION['user_id'] == 40) ) {
        echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow2(this.id);' class='button'>New STR</button> ";
    }
    echo "<button class='submitq' id='" . $rs_rec['trans_id'] . "' onclick='openWindow3(this.id);' class='button'>View</button></td>";
    echo "</tr>";
}
?>