
<link rel="stylesheet" type="text/css" href="../../../css/table.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
            $(document).ready(function () {
    setupLeftMenu();
            $('.datatable').dataTable();
            setSidebarHeight();
    });</script>
<script type="text/javascript">             var tableToExcel = (function () {
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
    .total{
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
<base target="_parent" />
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
<?php
include '../config.php';
$var = array();
$var2 = array();
$col = 0;
$col2 = 0;
$array_partclrs = array();
$array_partclrs_amount = array();
$array_pay_id = array();


			$sql_bank2 = mysql_query("SELECT * FROM bank_accounts WHERE bank_code like '%" . $_GET['bank'] . "%' and bank_code!='SBC'");
while ($rs_bank2 = mysql_fetch_array($sql_bank2)) {

	if($_GET['type'] != 'cancelled'){
    $sql_paid2 = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank2['bank_code'] . "%' and bank_code!='SBC' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')   order by bank_code,date asc");
	}else{
		 $sql_paid2 = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank2['bank_code'] . "%' and bank_code!='SBC' and status like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "') order by bank_code,date asc");
	}
		while ($rs_paid2 = mysql_fetch_array($sql_paid2)){
		
				array_push($array_pay_id, $rs_paid2['payment_id']);
				$array_payee[$rs_paid2['payment_id']]=$rs_paid2['cheque_name'];
				$array_bank[$rs_paid2['payment_id']]=$rs_paid2['bank_code'];
				$array_date[$rs_paid2['payment_id']]=$rs_paid2['date'];
				$array_status[$rs_paid2['payment_id']]=$rs_paid2['status'];
				$array_cheque[$rs_paid2['payment_id']]=$rs_paid2['cheque_no'];
				
				if($rs_paid2['status'] != 'cancelled'){	
				$array_total[$rs_paid2['payment_id']]= round($rs_paid2['grand_total'],2);
				}
				
		 	$select3 = mysql_query("SELECT * from payment_others WHERE payment_id ='".$rs_paid2['payment_id']."'") or die (mysql_error());
			while($rs_paid3 = mysql_fetch_array($select3)){
				
				if($rs_paid2['status'] != 'cancelled'){						
					array_push($array_partclrs, $rs_paid3['particulars']);
					@$array_particulrs_amount[$rs_paid2['payment_id']][$rs_paid3['particulars']]+= $rs_paid3['amount'];
					}
			
		 	}
		 }
	}
	$new_array = array_unique($array_partclrs);
/*print_r ($array_particulrs_amount);*/
echo '<table class="CSSTableGenerator" id="example">
<tr>
<td>Bank ID</td>
<td>DATE</td>
<td>CHECK VOUCHER#</td>
<td>PAYEE</td>
<td>     </td>
<td>AMOUNT</td>
<td>    </td>';
foreach($new_array as $partcls){
echo '<td>'.@$partcls.'</td>';
}
echo '</tr>';
 

foreach( $array_pay_id as $id){
echo '<tr>'; 
	echo '<td>'.@$array_bank[$id].'</td>';
	echo '<td>'.@$array_date[$id].'</td>';
	echo '<td>'.@$array_cheque[$id].'</td>';
	echo '<td>'.@$array_payee[$id].'</td>';
	echo '<td>'.@$array_status[$id].'</td>';
	echo '<td>'.@$array_total[$id].'</td>';
	echo '<td>    </td>';
	
	foreach($new_array as $parts){
		echo '<td>'.@$array_particulrs_amount[$id][$parts].'</td>';
	}
echo '</tr>';
}

	
   
   
echo "</table>";
?>
<br>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
