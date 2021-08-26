<?php
@session_start();
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
         <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link href="css/adv_form.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <link rel="stylesheet" type="text/css" href="css/adv_form.css" />
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
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
                font-size: 11px;
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
                    <h2>Employee Advances</h2>

                    <br>
                    <form action="report_fundtransfer.php" method="POST">
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
                                <td colspan="2"><input type="submit" class="large-submit" name='submit' value="Submit"></td>
                            </tr>
                        </table>
                    </form>

                    <?php
                    if (isset($_POST['submit'])) {

                        $from = $_POST['from'];
                        $to = $_POST['to'];

                      
                            $sql_eadv = mysql_query("SELECT * FROM payment WHERE (date>='$from' and date<='$to') and pay_type='Other Payment' and (description LIKE '%ft%' or description LIKE '%fund%' or description LIKE '%transfer%' or cheque_name LIKE '%lorna%') and status !='cancelled' and status !='deleted'") or die(mysql_error());
                        //echo "SELECT * FROM payment WHERE (date>='$from' and date<='$to') and pay_type='Other Payment' and (description LIKE '%fund%' or description LIKE '%fund transfer%' or description LIKE '%fund%' or description LIKE '%transfer%') and status !='cancelled' and status !='deleted'";
                        ?>
                        <br> <br><button onclick="tableToExcel('example', 'Employee CA-Report')"  class="button">XLS</button>
                        <br /><br />
                       <table class="data display datatable" id="example">
                            <thead>
                                <tr class="data">
                                    <th class="data">Date</th>
                                    <th class="data">Bank Code</th>
                                    <th class="data">Acct. Name</th>
                                    <th class="data">Acct #</th>
                                    <th class="data">Cheque #</th>
                                    <th class="data">Voucher #</th>
                                    <th class="data">Cheque Name</th>
                                    <th class="data">Description</th>
                                    <th class="data">Amount</th>
                                </tr>
                            </thead>
                        
                       <?php while ($rs_eadv = mysql_fetch_array($sql_eadv)) {
                                                                                   
                           echo '<tr>
                                    <td class="data">'.$rs_eadv['date'].'</td>
                                    <td class="data">'.$rs_eadv['bank_code'].'</td>
                                    <td class="data">'.$rs_eadv['account_name'].'</td>
                                    <td class="data">'.$rs_eadv['account_number'].'</td>
                                    <td class="data">'.$rs_eadv['cheque_no'].'</td>
                                    <td class="data">'.$rs_eadv['voucher_no'].'</td>
                                    <td class="data">'.$rs_eadv['cheque_name'].'</td>
                                    <td class="data">'.$rs_eadv['description'].'</td>
                                    <td class="data">'.$rs_eadv['grand_total'].'</td>
                                 </tr>';
                           
                            
                        }
                      echo '</table>';?>
                      <br> <br><button onclick="tableToExcel('example', 'Employee CA-Report')"  class="button">XLS</button>
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
