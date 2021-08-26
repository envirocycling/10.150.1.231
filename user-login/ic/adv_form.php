<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
}

$sql_ac_no = mysql_query("SELECT * FROM adv_sysgen_no");
$rs_ac_no = mysql_fetch_array($sql_ac_no);
$code = $rs_ac_no['supplier_code'];
$no = $rs_ac_no['sup_nx_ctrl_no'];

$sql_com = mysql_query("SELECT * FROM company");
$rs_com = mysql_fetch_array($sql_com);
$tp_code = $rs_com['tipco_code'];

$ac_no = $code . "-" . $tp_code . "-" . sprintf("%06s", $no);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <title>Envirocycling Fiber Inc.</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link href="css/adv_form.css" rel="stylesheet">
        <link href="css/select2.min.css" rel="stylesheet">
        <script type="text/javascript" src="js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
            $('#supplier_id').select2();
            });</script>
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
        <script type="text/javascript" src="js/jquery.mask.min"></script>
        <script>
                                $(document).ready(function () {
                                $('#supplier_id').select2();
                                $('#date').mask('00-00-0000');
                                });
                                function submitForm() {
                                var date = $("#date").val();
                                var ac_no = $("#ac_no").val();
                                var supplier_id = $("#supplier_id").val();
                                var acty_id = $("#acty_id").val();
                                var amount = $("#amount").val();
                                var acpty_id = $("#acpty_id").val();
                                var justification = escape($("#justification").val());
                                var terms = escape($("#terms").val());
                                var prepaid = 0;
                                if (date != '' ){
                                var data = 'date=' + date + '&ac_no=' + ac_no + '&supplier_id=' + supplier_id + '&acty_id=' + acty_id + '&amount=' + amount + '&acpty_id=' + acpty_id + '&justification=' + justification + '&terms=' + terms + '&prepaid=' + prepaid;
                                $.ajax({
                                url: "exec/adv_exec.php?action=submitForm",
                                        type: 'POST',
                                        data: data
                                }).done(function (e) {
                                if (e === '') {
                                    alert('Successful');
                                location.replace('adv_form.php');
                                } else {
                                $("#err").html(e);
                                }
                                });
                                }
                                }
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
                <br>
                <h2>ADVANCES FORM</h2>
                <br>
                <center>
                    <!--<form id="advancesForm" onSubmit="return validateForm()">-->
                    <table class="table">
                        <tr>
                            <td>Date: </td>
                            <td><input id="date" class="medium-input" type="text" name="date" placeholder="MM-DD-YYYY"></td>
                        </tr>
                        <tr>
                            <td>Supplier Name:</td>
                            <td><select id="supplier_id" class="medium-select-2" name="" required>
                                    <option value=""></option>
                                    <?php
                                    $sql_sup = mysql_query("SELECT * FROM supplier");
                                    while ($rs_sup = mysql_fetch_array($sql_sup)) {
                                        echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                    }
                                    ?>
                                </select></td>
                            <td>Type: </td>
                            <td><select id="acty_id" class="medium-select" name="" required>
                                    <?php
                                    $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id='4'");
                                    while ($rs_type = mysql_fetch_array($sql_type)) {
                                        echo '<option value="' . $rs_type['acty_id'] . '">' . $rs_type['name'] . '</option>';
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Amount: </td>
                            <td><input id="amount" class="medium-input" type="number" name="" required></td>
                            <td>Issuance Type: </td>
                            <td><select id="acpty_id" class="medium-select" name="" required>
                                    <?php
                                    $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE status!='deleted' and acpty_id='4'");
                                    while ($rs_ptype = mysql_fetch_array($sql_ptype)) {
                                        echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Justification: </td>
                            <td colspan="3"><textarea id="justification" class="medium-textarea-2"></textarea></td>
                        </tr>
                        <tr>
                            <td>Terms: </td>
                            <td colspan="3"><textarea id="terms" class="medium-textarea-2"></textarea></td>
                        </tr>
                        <tr>
                            <td colspan="4"><br/><input id="submit" class="large-submit" type="submit" name="" onclick="submitForm();"></td>
                        </tr>
                    </table>
                </center>
                <br/><br/>
            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
