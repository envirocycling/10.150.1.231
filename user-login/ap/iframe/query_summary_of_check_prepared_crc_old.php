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
$col = 0;
$num = 1;
echo '<table class="data display datatable" id="example">
<thead>';

echo '<tr class = "data">
<th class = "data">Date</th>
<th class = "data">CHECK VOUCHER #</th>
<th class = "data">Payee</th>
<th class = "data">Paritculars</th>
<th class = "data">Amount</th>
<th class = "data">Status</th>';
/* $tbl = mysql_query("SELECT * FROM bank_accounts WHERE bank_code like '%" . $_GET['bank'] . "%' and bank_code!='SBC'");
  $tbl_row = mysql_fetch_array();
  $sql_paid2 = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $tbl_row['bank_code'] . "%' and bank_code!='SBC' and status!='cancelled' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')") or die (mysql_error());
  while ($rs_paid2 = mysql_fetch_array($sql_paid2)) {
  $select4 = mysql_query("SELECT * from payment_others Where payment_id ='".$rs_paid2['payment_id']."' and (amount !=0 or amount!='')") or die (mysql_error());
  while($my_row3 = mysql_fetch_array($select4)){
  echo '<th class = "data">'.$my_row3['particulars'].'</th>';
  $col++;
  }
  } */
echo '</tr>
</thead>';

$sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code like '%" . $_GET['bank'] . "%' and bank_code!='SBC'");
while ($rs_bank = mysql_fetch_array($sql_bank)) {

    $total = 0;
    $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $rs_bank['bank_code'] . "%' and bank_code!='SBC'  and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')");
    while ($rs_paid = mysql_fetch_array($sql_paid)) {

        $select = mysql_query("SELECT * from payment_others Where payment_id ='" . $rs_paid['payment_id'] . "'") or die(mysql_error());
        $select2 = mysql_query("SELECT * from payment_others Where payment_id ='" . $rs_paid['payment_id'] . "'") or die(mysql_error());
        $select3 = mysql_query("SELECT * from payment_others Where payment_id ='" . $rs_paid['payment_id'] . "'") or die(mysql_error());
        $rows = mysql_fetch_array($seletc2);

        echo "<tr>";
        echo "<td>" . $rs_paid['date'] . "</td>";
        echo "<td>" . $rs_paid['cheque_no'] . "</td>";
        echo "<td>" . $rs_paid['cheque_name'] . "</td>";

        echo "<td width='40%'>";
        while ($my_row = mysql_fetch_array($select2)) {
            echo $my_row['particulars'] . '<br/>';
        }
        echo"</td>";

        $total+=$rs_paid['grand_total'];
        if ($rs_paid['status'] == 'cancelled') {
            echo "<td>";
            echo "</td>";
            echo "<td>";
            echo $rs_paid['status'];
            echo "</td>";
        } else {
            echo "<td>";
            while ($my_row = mysql_fetch_array($select3)) {
                echo $my_row['amount'] . '<br />';
            }
            echo '<b>' . $rs_paid['grand_total'] . '</b>';
            echo "</td>";
        }

        /* 	$tbl1 = mysql_query("SELECT * FROM bank_accounts WHERE bank_code like '%" . $_GET['bank'] . "%' and bank_code!='SBC'");
          $tbl_row1 = mysql_fetch_array();
          $sql_paid1 = mysql_query("SELECT * FROM payment WHERE bank_code like '%" . $tbl_row1['bank_code'] . "%' and bank_code!='SBC' and status!='cancelled' and type like '%" . $_GET['type'] . "%' and (date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "')") or die (mysql_error());
          while ($rs_paid1 = mysql_fetch_array($sql_paid1)) {
          $select1 = mysql_query("SELECT * from payment_others Where payment_id ='".$rs_paid1['payment_id']."' and (amount !=0 or amount!='')") or die (mysql_error());
          while($my_row1 = mysql_fetch_array($select1)){
          echo '<td class = "data">'.$my_row1['amount'].'</td>';
          }
          } */


        echo "</tr>";
    }
    echo "<tr class = 'total'>";
    echo "<td>" . $rs_bank['bank_code'] . "z</td>";
    echo "<td></td>";
    /*  echo "<td></td>";
      echo "<td></td>";
      echo "<td></td>"; */
    echo "<td></td>";
    echo "<td>TOTAL FOR " . $rs_bank['bank_code'] . "</td>";
    echo "<td>" . number_format($total, 2) . "</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "</tr>";
}
echo "</table>";
?>
<br>
<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">