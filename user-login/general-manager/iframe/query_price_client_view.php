
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
date_default_timezone_set("Asia/Singapore");
$from = date('Y-m-d', strtotime($_POST['from']));
$to =  date('Y-m-d', strtotime($_POST['to']));
$sql_clientSet = mysql_query("SELECT * FROM client WHERE cid='".$_POST['client']."'");
$row_clintSet = mysql_fetch_array($sql_clientSet);
$current_date = date('Y-m-d');

echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data">Client</th>';
$arr_grade_history = array();
$arr_grade = array();
$arr_mat = array();
$arr_mat_heading = array();
$arr_grade_heading = array();

$sql_client_grade= mysql_query("SELECT * FROM client_price WHERE client_id='".$_POST['client']."' and date_effective >= '$from' and date_effective <= '$to'") or die(mysql_error());
while($row_client_grade = mysql_fetch_array($sql_client_grade)){
    $sql_material = mysql_query("SELECT * FROM material WHERE material_id='".$row_client_grade['material_id']."'");
    $row_material = mysql_fetch_array($sql_material);
    $sql_current_price= mysql_query("SELECT * FROM client_price WHERE client_id='".$_POST['client']."' and material_id='".$row_client_grade['material_id']."' and date_effective <= '$current_date' Order by date_effective Desc LIMIT 1") or die(mysql_error());
    $row_current_price = mysql_fetch_array($sql_current_price);
    if($row_current_price['date_effective'] == $row_client_grade['date_effective'] && $row_client_grade['price'] == $row_current_price['price']){
        $arr_grade_history[$row_client_grade['material_id']] .= '<font color="green"><i>'.date('d-M-y',strtotime($row_client_grade['date_effective'])).'</i>:&nbsp;&nbsp;<b><u>'.$row_client_grade['price'].'</b></u></font><hr>';
    }else{
        $arr_grade_history[$row_client_grade['material_id']] .= '<i>'.date('d-M-y',strtotime($row_client_grade['date_effective'])).'</i>:&nbsp;&nbsp;<b><u>'.$row_client_grade['price'].'</b></u><hr>';
    }
        array_push($arr_grade,$row_material['code']);
        array_push($arr_mat,$row_client_grade['material_id']);
}
$arr_grade_heading = array_unique($arr_grade);
$arr_mat_heading = array_unique($arr_mat);
foreach($arr_grade_heading as $slct_grade){
        echo'<th class="data">'. $slct_grade.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>';
}
echo '</tr>
        </thead>';
echo "<tr class='data'>";
    echo "<td class='data'><center><h1>".strtoupper($row_clintSet['client_name'])."</h1></center></td>";
foreach($arr_mat_heading as $slcts_mat){
    echo "<td class='data' valign='top'>".$arr_grade_history[$slcts_mat]."</td>";
}
echo "</tr>";
echo "</table>";
?>