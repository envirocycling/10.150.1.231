
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
    button{
        height: 20px;
        width: 100px;
    }
</style>
<script type="text/javascript">   
    function f_price(data){
        var id = data.split("_");
        if(id[0] == 'price'){
            window.open('../price_client_encode.php?action=' + id[0] + '&cid=' + id[1] + '&date_effective=' + id[2], 'mywindow', 'width=400,height=520,left=500,top=50');
        }else{
            window.open('../price_client_encode.php?action=' + id[0] + '&cid=' + id[1], 'mywindow', 'width=900,height=500,left=160,top=100');
        }
    }
</script>
<?php
include '../config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data">Date Submitted</th>
            <th class="data">Date Effective</th>
            <th class="data" width="20">ID</th>
            <th class="data">Client Name</th>
            <th class="data">Description</th>
            <th class="data">Status</th>
            <th class="data">Action</th>';
echo '</tr>
        </thead>';
$sql_client_price = mysql_query("SELECT * FROM client_price WHERE status='0' group by date_effective") or die(mysql_error());
while ($row_client_price= mysql_fetch_array($sql_client_price)) {
    $sql_client = mysql_query("SELECT * from client WHERE cid='".$row_client_price['client_id']."'") or die(mysql_error());
    $row_client = mysql_fetch_array($sql_client);
    $date = date('Y/m/d', strtotime($row_client_price['date_modify']));
    $date_effective = date('Y/m/d', strtotime($row_client_price['date_effective']));
    echo "<tr class='data'>";
    echo "<td class='data'>" . $date . "</td>";
    echo "<td class='data'>" . $date_effective . "</td>";
    echo "<td class='data'>" . $row_client_price['client_id'] . "</td>";
    echo "<td class='data'>" . strtoupper($row_client['client_name']) . "</td>";
    echo "<td class='data'>" . strtoupper($row_client['contact']) . "</td>";
    echo "<td class='data'>" . strtoupper($row_client['description']) . "</td>";
    echo "<td class='data'><center><button onclick='f_price(this.id);' id='price_".$row_client['cid'].'_'.$row_client_price['date_effective']."'>View</button></td>";
    echo "</tr>";
}
echo "</table>";
?>