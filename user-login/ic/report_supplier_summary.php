<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/adv_form.css" />
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
        <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setupLeftMenu();
                $('.datatable').dataTable();
                setSidebarHeight();
            });
        </script>
        <style>
            .table{
                font-size: 18px;
            }
            .select2{
                width: 250px;
            }
            #table{
                width: 800px;
            }
            table{
                font-size: 13px;
            }
            #summary{
                width: 500px;
            }
            .tcal{
                text-transform: uppercase;
                border-radius: 4px;
                height: 30px;
                width: 180px;
                font-size: 18px;
            }
            .button{
                border-radius: 4px;
                width: 50px;
                height: 30px;
                font-size: 15px;
                
            }
        </style>
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
    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle">
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <div style="margin-left: 20px;">
                    <h2>Supplier Advances Summary</h2>

                    <br>
                    <form method="POST">
                        <table class="table">
                            <tr>
                                <td>From: <input class="tcal" type="text" name="from" value="<?php
                                    if (isset($_POST['from'])) {
                                        echo $_POST['from'];
                                    } else {
                                        echo date("Y/m/d");
                                    }
                                    ?>" size="10" readonly required></td>
                                <td>To: <input class="tcal" type="text" name="to" value="<?php
                                    if (isset($_POST['to'])) {
                                        echo $_POST['to'];
                                    } else {
                                        echo date("Y/m/d");
                                    }
                                    ?>" size="10" readonly required></td>
                                <td>&nbsp;&nbsp;&nbsp;Branch:
                                    <select name="branch" class="tcal">
                                        <?php
                                            if(isset($_POST['submit'])){
                                                echo '<option value="'.$_POST['branch'].'">'.$_POST['branch'].'</option>';
                                            }
                                            echo '<option value="">All</option>';
                                            $sql_branch = mysql_query("SELECT * from branches WHERE branch_id != '10'") or die (mysql_error());
                                            while($row_branch = mysql_fetch_array($sql_branch)){
                                                echo '<option value="'.$row_branch['branch_name'].'">'.$row_branch['branch_name'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </td>
                                <td colspan="3">&nbsp;&nbsp;&nbsp;Type:
                                    <select name="type" class="tcal">
                                        <?php
                                        if(isset($_POST['submit'])){
                                            if(empty($_POST['type'])){
                                                @$atrr  = 'selected';
                                            }else{
                                                @$atrr = '';
                                            }
                                        }else{
                                            $atrr = '';
                                        }
                                            $sql_type = mysql_query("SELECT * from adv_type WHERE status=''") or die(mysql_error());
                                            echo '<option value="">ALL</option>';
                                            while($row_type = mysql_fetch_array($sql_type)){
                                                if(isset($_POST['submit'])){
                                                    if($row_type['acty_id'] == $_POST['type']){
                                                        @$atrr = 'selected';
                                                    }else{
                                                        @$atrr = '';
                                                    }
                                                }else{
                                                    @$atrr = '';
                                                }
                                                echo '<option value="'.$row_type['acty_id'].'" '.@$atrr.'>'.$row_type['details'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </td> 
                                <td colspan="3">&nbsp;&nbsp;<input type="submit" class="large-submit" name='submit' value="Submit"></td>
                            </tr>
                        </table>
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {

                        $from = str_replace('/', '-', $_POST['from']);
                        $to = str_replace('/', '-', $_POST['to']) . " 23:59:59";
                        $branch = $_POST['branch'];
                        
                        if(!empty($_POST['type'])){
                            $myQuery = 'acty_id = '.$_POST['type'];
                        }else{
                            $myQuery = 'acty_id > 0';
                        }
                        
                        $date_issued = array();
                        $branch_arr = array();
                        $issued_to = array();
                        $amount_issued = array();
                        $ca_type = array();
                        $amount_paid = array();
                        $type_arr= array();
                        $last_deduction= array();
                        $remarks_arr = array();

                        $sql_branches = mysql_query("SELECT * from branches WHERE branch_name LIKE '%$branch%' and branch_id != '10'") or die (mysql_error());
                            while($row_branches = mysql_fetch_array($sql_branches)){
                                if($row_branches['branch_id'] != 7){
                                    $db_name = 'truck_scale';
                                    $url = $row_branches['ip_address'].'/ts';
                                }else{
                                    $db_name = 'efi_pamp';
                                    $url = $row_branches['ip_address'].'/paymentsystem';
                                }
                                $ch = curl_init($url);
                                curl_setopt($ch, CURLOPT_NOBODY, true);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_exec($ch);
                                $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                curl_close($ch);
                                if (200 == $retcode) {
                                    array_push($branch_arr,$row_branches['branch_id']);
                                    $url_sqli = $row_branches['ip_address'];
                                    $sqli = mysqli_connect("$url_sqli", 'efi', 'enviro101', "$db_name");
                                    if($row_branches['branch_id'] == 7){
                                        $sql_suppAdv = mysqli_query($sqli,"SELECT * from adv WHERE status = 'issued' and branch_id = '".$row_branches['branch_id']."' and date_processed <= '$to' and $myQuery order by date_processed Asc") or die(mysqli_error());
                                    }else{
                                      $sql_suppAdv = mysqli_query($sqli,"SELECT * from adv WHERE status = 'issued' and date_processed <= '$to' and $myQuery order by date_processed Asc") or die(mysqli_error());
                                    }
                                    
                                    while($row_suppAdv = mysqli_fetch_array($sql_suppAdv)){
                                        $date_issued[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] = $row_suppAdv['date_processed'];
                                        $issued_to[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] = $row_suppAdv['supplier_id'];
                                        $amount_issued[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] = $row_suppAdv['amount'];
                                        $remarks_arr[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] = $row_suppAdv['justification'];
                                        $type_arr[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] = $row_suppAdv['acty_id'];
                                       // echo "SELECT * from payment_adjustment WHERE ac_id='".$row_suppAdv['ac_id']."' and adj_type='deduct'<br>";
                                        $sql_amountPaid = mysqli_query($sqli, "SELECT * from payment_adjustment WHERE ac_id='".$row_suppAdv['ac_id']."' and adj_type='deduct'");
                                        while($row_amountPaid = mysqli_fetch_array($sql_amountPaid)){
                                            //echo "SELECT * from payment WHERE date<= '".date('Y/m/d', strtotime($to))."' and status!= 'deleted' and status!= 'cancelled' and payment_id = '".$row_amountPaid['payment_id']."'<br>";
                                        $sql_payment = mysqli_query($sqli, "SELECT * from payment WHERE date <= '".date('Y/m/d', strtotime($to))."' and status != 'deleted' and status != 'cancelled' and payment_id = '".$row_amountPaid['payment_id']."' ");
                                            if(mysqli_num_rows($sql_payment) > 0){
                                               // echo $row_amountPaid['amount'].'<br>';
                                                $amount_paid[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] += $row_amountPaid['amount'];
                                            }
                                        }
                                        
                                        $sql_lastdeductionadj= mysqli_query($sqli, "SELECT * from payment_adjustment WHERE ac_id='".$row_suppAdv['ac_id']."' and adj_type='deduct' ORDER BY adj_id Desc");
                                            while($row_lastdeductionadj = mysqli_fetch_array($sql_lastdeductionadj)){
                                                $sql_lastdeduction= mysqli_query($sqli, "SELECT * from payment WHERE status != 'deleted' and status != 'cancelled' and payment_id='".$row_lastdeductionadj['payment_id']."'");
                                                if(mysqli_num_rows($sql_lastdeduction) == 1){
                                                    $row_lastdeduction = mysqli_fetch_array($sql_lastdeduction);
                                                  $last_deduction[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] = 'Date:'.$row_lastdeduction['date'].' Amount:'.number_format($row_lastdeductionadj['amount'],2);
                                                  break;
                                                }
                                            }
                                        
                                        $sql_payment_cash = mysqli_query($sqli, "SELECT * from adv_payment WHERE paid_date <= '$to' and paid_date != '0000-00-00 00:00:00' and ac_id = '".$row_suppAdv['ac_id']."' and can_id='0'");
                                            if(mysqli_num_rows($sql_payment_cash) > 0){
                                               // echo $row_amountPaid['amount'].'<br>';
                                                while($row_payCash = mysqli_fetch_array($sql_payment_cash)){
                                                    @$amount_paid[$row_branches['branch_id']][$row_suppAdv['supplier_id']][$row_suppAdv['date_processed']] += $row_payCash['amount'];
                                                }
                                            }
                                            //echo "SELECT * from adv_payment WHERE paid_date <= '$to' and paid_date != '0000-00-00 00:00:00' and ac_id = '".$row_suppAdv['ac_id']."' and can_id='0'<br>";
                                    
                                        
                                    }
                                }
                            }
                        ?>
                        <br> <br><button onclick="tableToExcel('example', 'Supplier CA-Report')"  class="button">XLS</button>
                        <br /><br />
                       <table class="data display datatable" id="example">
                            <thead>
                                <tr class="data">
                                    <th class="data">Branch</th>
                                    <th class="data">Date Issued</th>
                                    <th class="data">Issued To</th>
                                    <th class="data">Amount Issued</th>
                                    <th class="data">CA Type</th>
                                    <th class="data">Amount Paid</th>
                                    <th class="data">Outstanding Balance</th>
                                    <th class="data">Ageing (Days)</th>
                                    <th class="data">Last Deducted</th>
                                    <th class="data">Remarks</th>
                                </tr>
                            </thead>
                        
                       <?php
                            $current_date = date('Y/m/d');
                            $total_ageing = 0;
                            $total_amountIssued = 0;
                            $total_amountPaid = 0;
                            $total_amountBalance = 0;
                            foreach($branch_arr as $slctd_branch){
                                $sql_branches = mysql_query("SELECT * from branches WHERE branch_id = '$slctd_branch'") or die (mysql_error());
                                $row_branches = mysql_fetch_array($sql_branches);
                                $url = $row_branches['ip_address'];
                                if($row_branches['branch_id'] != 7){
                                    $db_name = 'truck_scale';
                                    $sqli2 = mysqli_connect("$url", 'efi', 'enviro101', "$db_name");
                                    $sql_suppAdv2 = mysqli_query($sqli2,"SELECT * from adv WHERE status = 'issued' and date_processed <= '$to' and $myQuery order by date_processed Asc") or die(mysqli_error());
                                }else{
                                    $db_name = 'efi_pamp';
                                    $sqli2 = mysqli_connect("$url", 'efi', 'enviro101', "$db_name");
                                    $sql_suppAdv2 = mysqli_query($sqli2,"SELECT * from adv WHERE status = 'issued' and branch_id = '".$row_branches['branch_id']."' and date_processed <= '$to' and $myQuery  order by date_processed Asc") or die(mysqli_error());
                                }
                                while($row_suppAdv2 = mysqli_fetch_array($sql_suppAdv2)){
                                   $sql_supp = mysqli_query($sqli2, "SELECT * from supplier WHERE id='" . $issued_to[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']] . "'");
                                   $row_supp = mysqli_fetch_array($sql_supp);
                                   
                                   $sql_type= mysql_query("SELECT * from adv_type WHERE acty_id	='" . $type_arr[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']] . "'");
                                   $row_type = mysql_fetch_array($sql_type);

                                   $date_issuedData = date('Y/m/d', strtotime($date_issued[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']]));
                                   $amountData = $amount_issued[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']];
                                   $amount_paidData = $amount_paid[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']];
                                   $balance = $amountData - $amount_paidData;
                                   $remarks = $remarks_arr[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']];
                                   $last_deductions = $last_deduction[$slctd_branch][$row_suppAdv2['supplier_id']][$row_suppAdv2['date_processed']];

                                   //$date1 = $row_pr['gm_date'];
                                   //$date2 = $row_pr['hr_serve_date'];
                                   $start = new DateTime($date_issuedData);
                                   $end = new DateTime($current_date);
                                   $days2 = $start->diff($end, true)->days;

                                   $sundays = intval($days2 / 7) + ($start->format('N') + $days2 % 7 >= 7);
                                   
                                   $diff = abs(strtotime($current_date) - strtotime($date_issuedData));
                                   $years = floor(($diff / (365 * 60 * 60 * 24)/ (30 * 60 * 60 * 24))/ (60 * 60 * 24));
                                   $months = floor((($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24))/ (60 * 60 * 24));
                                   $days = (floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24))) - $sundays;
                                   if ($days > 1) {
                                       $aging = $days . ' days';
                                   } else {
                                       $aging = $days . ' day';
                                   }
                                   echo '<tr>';
                                        echo '<td>'.$row_branches['branch_name'].'</td>';
                                        echo '<td>'.$date_issuedData.'</td>';
                                        echo '<td>'.$row_supp['supplier_name'].'</td>';
                                        echo '<td>'.number_format($amountData,2).'</td>';
                                        echo '<td>'.$row_type['details'].'</td>';
                                        echo '<td>'.number_format($amount_paidData,2).'</td>';
                                        echo '<td>'.number_format($balance,2).'</td>';
                                        echo '<td>'.$aging.'</td>';
                                        echo '<td>'.$last_deductions.'</td>';
                                        echo '<td style="width:20%;">'.$remarks.'</td>';
                                    echo '</tr>';
                                    $total_ageing += $days;
                                    $total_amountIssued += $amountData;
                                    $total_amountPaid += $amount_paidData;
                                    $total_amountBalance += $balance;
                                }
                            }
                                echo '<tr style="background-color:yellow;">';
                                    echo '<td>zTOTALz</td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td>'.number_format($total_amountIssued,2).'</td>';
                                    echo '<td></td>';
                                    echo '<td>'.number_format($total_amountPaid,2).'</td>';
                                    echo '<td>'.number_format($total_amountBalance,2).'</td>';
                                    echo '<td>'.$total_ageing.' days</td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                echo '</tr>';
                      echo '</table>';?>
                      <br> <br><button onclick="tableToExcel('example', 'Supplier CA-Report')"  class="button">XLS</button>
                      <?php  echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                    }else{
                        echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>';
                    }
                    ?>
                </div>

            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
