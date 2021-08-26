<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
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
        <?php
        include 'template/layout.php';
        ?>
        <link href="css/adv_form.css" rel="stylesheet">
        <style>
            .table{
                font-size: 18px;
            }
            .table td{
                padding: 3px;
            }
            #prepaid{
                height: 20px;
                width: 20px;
            }
        </style>
        <script>
            $(document).ready(function () {
                $('#supplier_id').select2();
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
                if ($("#prepaid").is(":checked") === true) {
                    prepaid = 1;
                }
                var data = 'date=' + date + '&ac_no=' + ac_no + '&supplier_id=' + supplier_id + '&acty_id=' + acty_id + '&amount=' + amount + '&acpty_id=' + acpty_id + '&justification=' + justification + '&terms=' + terms + '&prepaid=' + prepaid;
                $.ajax({
                    url: "exec/adv_exec.php?action=submitForm",
                    type: 'POST',
                    data: data
                }).done(function (e) {
                    if (e === '') {
                        location.replace('adv_form.php');
                    } else {
                        $("#err").html(e);
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class="wrapper">

            <header class="header">

                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle">

                <div class="container">
                    <main class="content">
                        <div style="margin-left: -11px;" width="1200">
                            <?php
                            include 'template/menu.php';
                            ?>
                        </div>
                        <br>
                        <h2>ADVANCES FORM</h2>
                        <br>
                        <!--<form id="advancesForm" onSubmit="return validateForm()">-->
                        <table class="table">
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y"); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ac_no" class="medium-input" type="text" name="ac_no" value="<?php echo $ac_no; ?>" readonly>
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
                                        <option value=""></option>
                                        <?php
                                        $sql_type = mysql_query("SELECT * FROM adv_type");
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
                                        <option value=""></option>
                                        <?php
                                        $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE status!='deleted'");
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
                                <td>Prepaid: </td>
                                <td colspan="3"><input id="prepaid" type="checkbox" name="prepaid" value="prepaid"></td>
                            </tr>
                            <tr>
                                <td colspan="4"><input id="submit" class="large-submit" type="submit" name="" onclick="submitForm();"></td>
                            </tr>
                        </table>
                        <!--</form>-->
                        <br>
                        <br>
                        <br>
                        <div id="err"></div>
                        <!--                                                <br>
                                                                        <br>
                                                                        <br>
                                                                        <br>-->
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>