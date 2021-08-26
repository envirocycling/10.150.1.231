<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
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
                            var tableToExcel = (function () {             var uri = 'data:application/vnd.ms-excel;base64,'
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
                    <form action="report_employee_advances.php" method="POST">
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

                        $from = str_replace('/', '-', $_POST['from']);
                        $to = str_replace('/', '-', $_POST['to']) . " 23:59:59";


                        $sql_eadv = mysql_query("SELECT * FROM employee_advances WHERE (date>='$from' and date<='$to')");
                        ?>
                        <br> <br><button onclick="tableToExcel('example', 'Employee CA-Report')"  class="button">XLS</button>
                        <br /><br />
                        <table class="data display datatable" id="example">
                            <thead>
                                <tr class="data">
                                    <th class="data">Date Created</th>
                                    <th class="data">Branch</th>
                                    <th class="data">Date Issued</th>
                                    <th class="data">Date Liquidated</th>
                                    <th class="data">Ref No.</th>
                                    <th class="data">Employee Name</th>
                                    <th class="data">Purpose</th>
                                    <th class="data">Status</th>
                                    <th class="data">PCV No.</th>
                                    <th class="data">Cash Advance</th>
                                    <th class="data">Liquidated</th>
                                    <th class="data">Balance</th>
                                    <th class="data">Days</th>
                                    <th class="data">Approver</th>
                                    <th class="data">Prepared By</th>
                                </tr>
                            </thead>

                            <?php
                            while ($rs_eadv = mysql_fetch_array($sql_eadv)) {
                                $liquidated = $rs_eadv['total_expense'] + $rs_eadv['returned_excess_cash'];
                                $variance_comp = $rs_eadv['amount'] - $liquidated;
                                $variance = ($variance_comp < 0 ? "(" . abs($variance_comp) . ")" : $variance_comp);
                                $date_issued = date('Y/m/d', strtotime($rs_eadv['date_received']));
                                $date_liquidated = date('Y/m/d', strtotime($rs_eadv['date_liquidated']));
                                $date = date('Y/m/d', strtotime($rs_eadv['date']));
                                $end = new DateTime(date('Y-m-d', strtotime($date_issued)));

                                $chk_dateissued = strtotime($rs_eadv['date_received']);
                                $chk_liquidated = strtotime($rs_eadv['date_liquidated']);

                                if (empty($chk_dateissued) || $chk_dateissued < 0) {
                                    $date_issued = '';
                                    $end = '';
                                }
                                if (empty($chk_liquidated) || $chk_liquidated < 0) {
                                    $date_liquidated = '';
                                }

                                //$datetime1 = date_create($date_liquidated);
                                $date1 = date('Y-m-d');
                                if ($variance > 0 && $rs_eadv['status'] == 'liquidated') {
                                    $datetime1 = date_create($date_liquidated);
                                    $start = new DateTime(date('Y-m-d', strtotime($date_liquidated)));
                                } else if ($rs_eadv['status'] == 'issued') {
                                    $datetime1 = date_create($date1);
                                    $start = new DateTime($date1);
                                }
                                $datetime2 = date_create($date_issued);

                                $sql_approver = mysql_query("SELECT * from users WHERE user_id = '" . $rs_eadv['approver'] . "'") or die(mysql_error());
                                $row_approver = mysql_fetch_array($sql_approver);

                                $sql_prepapredby = mysql_query("SELECT * from users WHERE user_id = '" . $rs_eadv['prepared_by'] . "'") or die(mysql_error());
                                $row_preparedby = mysql_fetch_array($sql_prepapredby);

                                $sql_emp = mysql_query("SELECT * from employee WHERE emp_id = '" . $rs_eadv['emp_id'] . "'") or die(mysql_error());
                                $row_emp = mysql_fetch_array($sql_emp);

                                $sql_branch = mysql_query("SELECT * from branches WHERE branch_id='" . $rs_eadv['branch_id'] . "'") or die(mysql_error());
                                $row_branch = mysql_fetch_array($sql_branch);

                                $approver = ucwords($row_approver['firstname']) . ', ' . ucwords($row_approver['lastname']);
                                $preparedby = ucwords($row_preparedby['firstname']) . ', ' . ucwords($row_preparedby['lastname']);

                                $approver_expleode = explode("-", $rs_eadv['approver']);
                                $prepared_expleode = explode("-", $rs_eadv['prepared_by']);
                                if (!empty($approver_expleode[1])) {
                                    $approver = $approver_expleode[1];
                                }if (!empty($prepared_expleode[1])) {
                                    $preparedby = $approver_expleode[1];
                                }

                                echo '<tr>
                                    <td class="data">' . $date . '</td>
                                    <td class="data">' . $row_branch['branch_name'] . '</td>
                                    <td class="data">' . $date_issued . '</td>
                                    <td class="data">' . $date_liquidated . '</td>
                                    <td class="data">' . strtoupper($rs_eadv['ref_no']) . '</td>
                                    <td class="data">' . ucwords($row_emp['name']) . '</td>
                                    <td class="data">' . ucwords($rs_eadv['purpose']) . '</td>
                                    <td class="data">' . ucwords($rs_eadv['status']) . '</td>
                                    <td class="data">' . strtoupper($rs_eadv['pcv_no']) . '</td>
                                    <td class="data">' . number_format($rs_eadv['amount']) . '</td>
                                    <td class="data">' . number_format($liquidated) . '</td>
                                    <td class="data">' . number_format($variance) . '</td>
                                    <td class="data">';
//                                $start = new DateTime('2013-01-06');
                                $days = $start->diff($end, true)->days;

                                $sundays = intval($days / 7) + ($start->format('N') + $days % 7 >= 7);
                                $age_days = date_diff($datetime1, $datetime2);
                                if ($rs_eadv['status'] == 'issued') {
                                    echo $age_days->format('%a') - $sundays;
                                }echo '</td>
                                    <td class="data">' . $approver . '</td>
                                    <td class="data">' . $preparedby . '</td>
                                 </tr>';
                            }
                            echo '</table>';
                            ?>
                            <br> <br><button onclick="tableToExcel('example', 'Employee CA-Report')"  class="button">XLS</button>
    <?php
    echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
} else {
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
