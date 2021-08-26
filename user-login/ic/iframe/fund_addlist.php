
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
<?php
include '../config.php';

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
			<th class="data" width="40">Date</th>
            <th class="data" width="80">Branch</th>
            <th class="data" width="80">Amount</th>
            <th class="data" width="80">Remarks</th>
            <th class="data">Status</th>            
            <th class="data">Prepared By</th>
            <th class="data">Verified By</th>
        </tr>
        </thead>';

        $sql_rec = mysql_query("SELECT * FROM fund_adtl_request WHERE date <='" . date('Y-m-d', strtotime('+1 day', strtotime($_GET['from']))) . "' ") or die(mysql_error());


while ($rs_rec = mysql_fetch_array($sql_rec)) {
$sql_branch = mysql_query("SELECT * from branches WHERE branch_id='".$rs_rec['branch_id']."'");
$row_branch = mysql_fetch_array($sql_branch);
   
   if($rs_rec['remarks'] == '3'){
   	$status = 'Approved';
   }else{
   	$status = 'Cancelled';
   }
    echo "<tr class='data'>";
    echo "<td class='data'>" . date('Y/m/d h:iA', strtotime($rs_rec['date'])) . "</td>";
	echo "<td class='data'>" . $row_branch['branch_name'] . "</td>";
    echo "<td class='data'>" . $rs_rec['amount'] . "</td>";
    echo "<td class='data'>" . $rs_rec['remarks'] . "</td>";
    echo "<td class='data'>" . $status . "</td>";
    echo "<td class='data'>" . strtoupper($rs_rec['fullname']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_rec['verified_name']) . "</td>";
    echo "</tr>";
}
echo "</table>";
?>