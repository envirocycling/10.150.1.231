
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
<script type="text/javascript">
                    var tableToExcel = (function () {
                    var uri = 'data:application/vnd.ms-excel;base64,'
                            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                                                        , base64 = function (s) {
    return window.btoa(unescape(encodeURIComponent(s)))
                }
        , format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }
        return function (table, name) {
            if (!table.nodeType)
                table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            window.location.href = uri + base64(format(template, ctx))
        }
    })()
</script>

<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .total {
        font-weight: bold;
        background-color: yellow;
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
<base target="_parent" />
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
<?php
include '../config.php';
$total_weight = 0;
$total_amount = 0;
$total_tot_ts_fee = 0;
$total_net_amount = 0;
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data">Date Delivered</th>
            <th class="data">STR #</th>
            <th class="data">Cheque #</th>
            <th class="data">Supplier Name</th>
            <th class="data">Plate #</th>
            <th class="data">WP Grade</th>
            <th class="data">Status</th>
            <th class="data">Date Paid</th>
            <th class="data">Net Weight</th>
            <th class="data">Unit Cost</th>
            <th class="data">Paper Buying</th>
</tr>
        </thead>';
$sql_com = mysql_query("SELECT * from company");
$row_company = mysql_fetch_array($sql_com);

$last_month = date('Y/m', strtotime('-1 month', strtotime($_GET['from'])));
$last_days = date('t',strtotime($_GET['from']));
$last_date = $last_month.$last_days;
$sql_lastMonth = mysql_query("SELECT * FROM scale_receiving WHERE date <= '$last_date' and status!='void'");
while ($rs_lastMonth = mysql_fetch_array($sql_lastMonth)) {
                //if(date('Y/m',strtotime($rs_lastMonth['date_paid'])) == date('Y/m',strtotime($_GET['to']))){
                if($rs_lastMonth['status'] == 'paid' && date('Y/m',strtotime($rs_lastMonth['date_paid'])) == date('Y/m',strtotime($_GET['to']))){
                    if(strpos($rs_lastMonth['cheque_no'], 'SBC')  !== false ){
                                $check_no = 'SBC'.$row_company['code'].'_'.$rs_lastMonth['voucher_no'];
                                $status = ucwords($rs_lastMonth['status']);
                            }else{
                                $check_no = $rs_lastMonth['cheque_no'];
                                $status = ucwords($rs_lastMonth['status']);
                            }
                            $show = 1;
                }else if($rs_lastMonth['status'] != 'paid' && empty($rs_lastMonth['date_paid'])){
                    $check_no = '';
                    $status = 'Unpaid';
                    $show= 1;
                    
                }else{
                    $show = 0;
                }
                
                if($show == 1){
                $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_lastMonth['trans_id']."'");

                    while($row_details = mysql_fetch_array($sql_details)){
                        
                        echo "<tr>";
                            echo "<td>" . $rs_lastMonth['date'] . "</td>";
                            echo "<td>" .$rs_lastMonth['str_no'] . "</td>";
                            echo "<td>" .$check_no . "</td>";
                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs_lastMonth['supplier_id']."'");
                            $rs_sup = mysql_fetch_array($sql_sup);
                            echo "<td>".$rs_sup['supplier_id']."_".$rs_sup['supplier_name']."</td>";
                            echo "<td>".$rs_lastMonth['plate_number']."</td>";
                            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$row_details['material_id']."'");
                            $rs_mat = mysql_fetch_array($sql_mat);
                            echo "<td>".$rs_mat['code']."</td>";
                            echo "<td>".$status."</td>";
                            echo "<td>".$rs_lastMonth['date_paid']."</td>";
                            echo "<td>".$row_details['corrected_weight']."</td>";
                            $total_weight+=$row_details['corrected_weight'];
                            echo "<td>".$row_details['price']."</td>";
                            echo "<td>".$row_details['amount']."</td>";
                            $total_amount+=$row_details['amount'];
                            echo "</tr>";
                    }
                }
}


$sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE date>='".$_GET['from']."' and date<='".$_GET['to']."'");
while ($rs_rec = mysql_fetch_array($sql_rec)) {
            if($rs_rec['status'] == 'paid'){
                if(empty($rs_rec['date_paid'])){
                    if(date('Y/m',strtotime($rs_rec['date'])) == date('Y/m',strtotime($_GET['to']))){
                        $status = 'Paid';
                    }else{
                        $status = 'Unpaid';
                    }
                }else{
                   if(date('Y/m',strtotime($rs_rec['date_paid'])) == date('Y/m',strtotime($rs_rec['date']))){
                        $status = 'Paid';
                    }else{
                        $status = 'Unpaid';
                    }
                }
            }else{
                $status = 'Unpaid';
            }
            
                if($status == 'Paid'){
                        if(strpos($rs_rec['cheque_no'],'SBC')  !== false ){
                                $check_no = 'SBC'.$row_company['code'].'_'.$rs_rec['voucher_no'];
                        }else{
                        $check_no = $rs_rec['cheque_no'];
                    }
                }else{
                    $check_no = '';
                }
        
        $sql_details = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='".$rs_rec['trans_id']."'");

        while($row_details = mysql_fetch_array($sql_details)){
            echo "<tr>";
                echo "<td>" . $rs_rec['date'] . "</td>";
                echo "<td>" .$rs_rec['str_no'] . "</td>";
                echo "<td>" .$check_no . "</td>";
                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='".$rs_rec['supplier_id']."'");
                $rs_sup = mysql_fetch_array($sql_sup);
                echo "<td>".$rs_sup['supplier_id']."_".$rs_sup['supplier_name']."</td>";
                echo "<td>".$rs_rec['plate_number']."</td>";
                $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='".$row_details['material_id']."'");
                $rs_mat = mysql_fetch_array($sql_mat);
                echo "<td>".$rs_mat['code']."</td>";
                echo "<td>".$status."</td>";
                echo "<td>".$rs_rec['date_paid']."</td>";
                echo "<td>".$row_details['corrected_weight']."</td>";
                $total_weight+=$row_details['corrected_weight'];
                echo "<td>".$row_details['price']."</td>";
                echo "<td>".$row_details['amount']."</td>";
                $total_amount+=$row_details['amount'];
                echo "</tr>";
        }
}
echo "<tr class='total'>";
echo "<td>!TOTAL!</td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td>$total_weight</td>";
echo "<td></td>";
echo "<td>".number_format($total_amount,2)."</td>";
echo "</tr>";
echo "</table>";
?>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">