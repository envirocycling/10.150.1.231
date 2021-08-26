
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
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
$col = 0;

echo '<table class="data display datatable" id="example">
<thead>
<tr class = "data">
<th class = "data">Bank ID</th>
<th class = "data">CV Date</th>
<th class = "data">CV #</th>
<th class = "data">Payee</th>
<th class = "data">Amount</th>
<th class = "data">Status</th>';

			$sql_bank2 = mysql_query("SELECT * FROM bank_accounts WHERE bank_code like '%" . $_GET['bank'] . "%' and bank_code!='SBC'");
while ($rs_bank2 = mysql_fetch_array($sql_bank2)) {

	if($_GET['type'] != 'cancelled'){
    $sql_paid2 = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank2['bank_code'] . "%' and bank_code!='SBC' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')");
	}else{
		 $sql_paid2 = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank2['bank_code'] . "%' and bank_code!='SBC' and status like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')");
	}
		while ($rs_paid2 = mysql_fetch_array($sql_paid2)){
		 	$select3 = mysql_query("SELECT * from payment_others WHERE payment_id ='".$rs_paid2['payment_id']."'") or die (mysql_error());
			while($rs_paid3 = mysql_fetch_array($select3)){
				if($rs_paid2['status'] != 'cancelled'){
							echo '<th class="data">'.$rs_paid3['particulars'].'</th>';
							$var[$col]=$rs_paid3['payment_id'];
					$col++;
				}
		 	}
		 }
	}

echo '</tr>
</thead>';
$head_col = $col;
$head_col2 = $col;

$sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code like '%" . $_GET['bank'] . "%' and bank_code!='SBC'");
while ($rs_bank = mysql_fetch_array($sql_bank)) {

    $total = 0;
	if($_GET['type'] != 'cancelled'){
    $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank['bank_code'] . "%' and bank_code!='SBC' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')");
	}else{
		 $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank['bank_code'] . "%' and bank_code!='SBC' and status like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')");
	}
	
    while ($rs_paid = mysql_fetch_array($sql_paid)) {
		
		$select = mysql_query("SELECT * from payment_others Where payment_id ='".$rs_paid['payment_id']."'") or die (mysql_error());
		$select2 = mysql_query("SELECT * from payment_others Where payment_id ='".$rs_paid['payment_id']."'") or die (mysql_error());
		$my_rows = mysql_fetch_array($select2);
		
        echo "<tr>";
        echo "<td>" . $rs_paid['bank_code'] . "</td>";
        echo "<td>" . $rs_paid['date'] . "</td>";
		echo "<td>" . $rs_paid['cheque_no'] . "</td>";
        echo "<td>" . $rs_paid['cheque_name'] . "</td>";

        if ($rs_paid['status'] == 'cancelled') {
			if($_GET['type'] == 'cancelled')
			{echo $rs_paid['grand_total'];}
			 echo "<td>&nbsp;</td>";
            echo "<td>Cancelled</td>";
        } else {
            $total+=$rs_paid['grand_total'];
            echo "<td>";
			echo round($rs_paid['grand_total'],2);
			echo "</td>";
			echo '<td>&nbsp;</td>';
        }
		$num=0;
		while($num < $head_col){
		echo '<td>';
			if($rs_paid['status'] != 'cancelled' && $var[$num] == $rs_paid['payment_id']){
			 echo 	$my_rows['amount'];
			 }
		echo'</td>';
		$num++;
		}
        echo "</tr>";
		
    }
	
    echo "<tr class = 'total'>";
    echo "<td><b>" . $rs_bank['bank_code'] . "z</b></td>";
		echo '<td>&nbsp;</td>';
	echo '<td>&nbsp;</td>';
    echo "<td><b>TOTAL FOR " . $rs_bank['bank_code'] . "</b></td>";
    echo "<td><b>" . number_format($total, 2) . "</b></td>";
	echo '<td>&nbsp;</td>';
	$num2 = 0;
		while($num2 < $head_col){
		echo '<td></td>';
		$num2++;
		}
    echo "</tr>";
	
}

echo "</table>";
?>
<br>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">