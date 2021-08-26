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
    .submit{
        height: 20px;
        width: 60px;
        font-size: 12px;
    }
    .submit2{
        height: 20px;
        width: 120px;
        font-size: 12px;
    }
</style>
<?php
include 'con1.php';
$sql_others = mysql_query("SELECT * FROM tbl_deleted_data Order by table_name,branch Asc") or die (mysql_error());
echo '<table class="data display datatable" id="example">
<thead>        
<tr class="data">
			<th class="data" colspan="2">Action</th>
			<th class="data" width="80">Table Name</th>
			<th class="data" width="80">Trans ID</th>
            <th class="data" width="80">Str</th>
            <th class="data" width="50">Date</th>
			<th class="data" width="50">Branch</th>
            <th class="data" width="300">Supplier ID</th>
			<th class="data" width="300">Supplier Name</th>
            <th class="data">Plate No.</th>
            <th class="data">WP Grade</th>
            <th class="data">Weight</th>
			<th class="data">Net Weight</th>
            <th class="data">Mc</th>
			<th class="data">Dirt</th>
			<th class="data">Unit Cost</th>
			<th class="data">Paper Buying</th>
			<th class="data">Trucking</th>
			<th class="data">Priority Number</th>
			<th class="data">Supplier Type</th>
			<th class="data">BH in Charge</th>
			<th class="data">Month</th>
			<th class="data">Day</th>
			<th class="data">Year</th>
			
        </tr>
        </thead>';

while ($rs_others = mysql_fetch_array($sql_others)) {
	$id = $rs_others['id'];
    echo "<tr class='data'>";
	echo "<td class='data'><button>DelPer</button></td>";
	echo "<td class='data'><a href='pro_restore.php?id=$id'><button>Restore</button></a></td>";
    echo "<td class='data'>" . $rs_others['table_name'] . "</td>";
    echo "<td class='data'>" . $rs_others['trans_id'] . "</td>";
    echo "<td class='data'>" . $rs_others['str_no'] . "</td>";
    echo "<td class='data'>" . $rs_others['date'] . "</td>";
	echo "<td class='data'>" . $rs_others['branch'] . "</td>";
	echo "<td class='data'>" . $rs_others['supplier_id'] . "</td>";
	echo "<td class='data'>" . $rs_others['supplier_name'] . "</td>";
	echo "<td class='data'>" . $rs_others['plate_number'] . "</td>";
	echo "<td class='data'>" . $rs_others['wp_grade'] . "</td>";
	echo "<td class='data'>" . $rs_others['weight'] . "</td>";
	echo "<td class='data'>" . $rs_others['net_weight'] . "</td>";
	echo "<td class='data'>" . $rs_others['mc'] . "</td>";
	echo "<td class='data'>" . $rs_others['dirt'] . "</td>";
	echo "<td class='data'>" . $rs_others['unit_cost'] . "</td>";
	echo "<td class='data'>" . $rs_others['paper_buying'] . "</td>";
	echo "<td class='data'>" . $rs_others['trucking'] . "</td>";
	echo "<td class='data'>" . $rs_others['priority_number'] . "</td>";
	echo "<td class='data'>" . $rs_others['supp_type'] . "</td>";
	echo "<td class='data'>" . $rs_others['bh_incharge'] . "</td>";
	echo "<td class='data'>" . $rs_others['month'] . "</td>";
	echo "<td class='data'>" . $rs_others['day'] . "</td>";
	echo "<td class='data'>" . $rs_others['year'] . "</td>";
	

    echo "</tr>";
}
echo "</table>";
?>