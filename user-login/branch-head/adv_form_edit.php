<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['bh_id'])) {
    echo "<script>location.replace('../../');</script>";
}

$sql_ac = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);
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
        <link rel="shortcut icon" href="images/efi_ico.png" />
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
                alert('Successfully Updated.');

                $.ajax({
                    url: "exec/adv_exec.php?action=submitEditForm&ac_id=<?php echo $_GET['ac_id']; ?>",
                    type: 'POST',
                    data: data
                }).done(function (e) {
                    if (e === '') {
                        location.replace('adv_form_edit.php?ac_id=<?php echo $_GET['ac_id']; ?>');
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


                <div style="margin-left: 0px;" width="1200">
                    <?php
                    include 'template/menu.php';
                    ?>
                </div>
                <div align="center">

                    <br>
                    <h2>ADVANCES FORM</h2>
                    <br>
                    <!--<form id="advancesForm" onsubmit="return validateForm()">-->
                    <table class="table">
                        <tr>
                            <td>Date: </td>
                            <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                            <td>Ref No: </td>
                            <td><input id="ac_no" class="medium-input" type="text" name="ac_no" value="<?php echo $rs_ac['ac_no']; ?>" readonly>
                        </tr>
                        <tr>
                            <td>Supplier Name:</td>
                            <td><select id="supplier_id" class="medium-select-2" name="" required>

                                    <?php
                                    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_ac['supplier_id'] . "'");
                                    $rs_sup = mysql_fetch_array($sql_sup);
                                    echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                    $sql_sup = mysql_query("SELECT * FROM supplier");
                                    while ($rs_sup = mysql_fetch_array($sql_sup)) {
                                        echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                    }
                                    ?>
                                </select></td>
                            <td>Type: </td>
                            <td><select id="acty_id" class="medium-select" name="" required>
                                    <?php
                                    $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_ac['acty_id'] . "'");
                                    $rs_type = mysql_fetch_array($sql_type);
                                    echo '<option value="' . $rs_type['acty_id'] . '">' . $rs_type['name'] . '</option>';

                                    $sql_type = mysql_query("SELECT * FROM adv_type");
                                    while ($rs_type = mysql_fetch_array($sql_type)) {
                                        echo '<option value="' . $rs_type['acty_id'] . '">' . $rs_type['name'] . '</option>';
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Amount: </td>
                            <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>" required></td>
                            <td>Payment Type: </td>
                            <td><select id="acpty_id" class="medium-select" name="" required>
                                    <?php
                                    $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_ac['acpty_id'] . "'");
                                    $rs_ptype = mysql_fetch_array($sql_ptype);
                                    echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';

                                    $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE status!='deleted'");
                                    while ($rs_ptype = mysql_fetch_array($sql_ptype)) {
                                        echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';
                                    }
                                    ?>
                                </select></td>
                        </tr>
                        <tr>
                            <td>Justification: </td>
                            <td colspan="3"><textarea id="justification" class="medium-textarea-2"><?php echo $rs_ac['justification']; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Terms: </td>
                            <td colspan="3"><textarea id="terms" class="medium-textarea-2"><?php echo $rs_ac['terms']; ?></textarea></td>
                        </tr>
                        <tr>
                                <td>Prepaid: </td>
                                <td colspan="3"><input id="prepaid" type="checkbox" name="prepaid" value="prepaid" <?php
                                    if ($rs_ac['prepaid'] == '1') {
                                        echo "checked";
                                    }
                                    ?>></td>
                            </tr>
                        <tr>
                            <td colspan="4"><input id="submit" class="large-submit" type="submit" name="" onclick="submitForm();"></td>
                        </tr>
                    </table>
                    <!--</form>-->
                    <br>
                    <!--                        <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>-->
                </div>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>