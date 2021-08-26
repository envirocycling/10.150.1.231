<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}

function voucherNo() {
    $voucher_date = date("md");
    $date = date("Y/m/d");
    $sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE date='$date' and bank_code!='SBC' and status!='deleted'");
    $rs_voucher = mysql_fetch_array($sql_voucher);
    if ($rs_voucher['count(voucher_no)'] == '0') {
        $voucher_number = "01";
    } else {
        $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);
        $voucher_number = $details[1] + 1;
        if ($voucher_number < 10) {
            $voucher_number = "0" . $voucher_number;
        }
    }
    $voucher_no = $voucher_date . "-" . $voucher_number;
    return $voucher_no;
}

$sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_pay = mysql_fetch_array($sql_pay);

$sql_pay_others = mysql_query("SELECT * FROM payment_others WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_pay_others = mysql_fetch_array($sql_pay_others);

$sql_ac = mysql_query("SELECT * FROM adv WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link href="css/adv_form.css" rel="stylesheet">
        <script src="js/payment/adv3.js"></script>
        <style>
            .table{
                font-size: 18px;
            }
            .table td{
                padding: 3px;
            }
            #new_cheque{
                padding: 10px;
                height: 20px;
                width: 20px;
            }
        </style>
        <script>
            $.acpty = '<?php echo $rs_ac['acpty_id']; ?>';
            $.ac_id = '<?php echo $rs_ac['ac_id']; ?>';
            $.others_id = '<?php echo $rs_pay_others['id']; ?>';

            $.user_id = "<?php echo $_SESSION['user_id']; ?>";
            $.verifier = "<?php echo $_SESSION['verifier']; ?>";
            $.signatory = "<?php echo $_SESSION['signatory']; ?>";

            $(document).ready(function () {
                var date_now = "<?php echo date("Y/m/d"); ?>";
                var date_plus8d = "<?php echo date("Y/m/d", strtotime("+8 days", strtotime($rs_pay['date']))); ?>";
                if (date_now > date_plus8d) {
                    $("input").prop("readonly", true);
                    $("textarea").prop("disabled", true);
                    $(".submit").prop("disabled", true);
                    $("select").prop("disabled", true);
                    $("button").prop("disabled", true);
                    $("#err").html("<font color='red'>You can't edit this transaction.</font>");
                }
            });
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
                        <h2>ADVANCE - CHECK FORM</h2>
                        <br>
<!--                        <input id='save' type='hidden' name='save' value='0'>

                        <input type='hidden' id='click' name='click' value='0'>
                        <input type='hidden' id='checker' name='checker' value='0'>-->
                        <input type='hidden' id='payment_id' name='payment_id' value='<?php echo $_GET['payment_id']; ?>'>
                        <table class="table">
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ac_no" class="medium-input" type="text" name="ac_no" value="<?php echo $rs_ac['ac_no']; ?>" readonly>
                            </tr>
                            <tr>
                                <td>Supplier Name:</td>
                                <td><select id="supplier_id" class="medium-select-2" name="" readonly>

                                        <?php
                                        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_ac['supplier_id'] . "'");
                                        $rs_sup = mysql_fetch_array($sql_sup);
                                        echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                        ?>
                                    </select></td>
                                <td>Account.: </td>
                                <td><input id="old_bank_code" class="medium-input" type="text" name="old_bank_code" value="<?php echo $rs_pay['bank_code']; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>" readonly></td>
                                <td>Cheque No.: </td>
                                <td><input id="old_cheque_no" class="medium-input" type="text" name="old_cheque_no" value="<?php echo $rs_pay['cheque_no']; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td>Cheque Name: </td>
                                <td><select id="cheque_name" class="medium-select-2" name="cheque_name" readonly>
                                        <option value="<?php echo $rs_pay['cheque_name']; ?>"><?php echo $rs_pay['cheque_name']; ?></option>
                                        <?php
                                        $sql_name = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $rs_ac['supplier_id'] . "'");
                                        while ($rs_name = mysql_fetch_array($sql_name)) {
                                            echo '<option value="' . $rs_name['name'] . '">' . $rs_name['name'] . '</option>';
                                        }
                                        ?>
                                    </select></td>
                                <td>Voucher No.: </td>
                                <td><input id="old_voucher_no" class="medium-input" type="text" name="old_voucher_no" value="<?php echo $rs_pay['voucher_no']; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td>If New: </td>
                                <td><input id="cheque_name_new" class="medium-select-2" type="text" name="cheque_name_new" value="" required></td>
                                <td>Select Account: </td>
                                <td><select id="bank_code" class="medium-select" name="bank_code" onchange="change(this.value);" disabled>
                                        <option value=""></option>
                                        <?php
                                        if ($rs_ac['acpty_id'] == '2') {
                                            $sql_bank_code = mysql_query("SELECT * FROM bank_accounts WHERE status!='deleted'");
                                            while ($rs_bank_code = mysql_fetch_array($sql_bank_code)) {
                                                echo '<option value="' . $rs_bank_code['bank_code'] . '">' . $rs_bank_code['bank_code'] . ' - ' . $rs_bank_code['location'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select></td>

                            </tr>
                            <tr>
                                <td>Type: </td>
                                <td><select id="acty_id" class="medium-select" name="" readonly>
                                        <?php
                                        $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_ac['acty_id'] . "'");
                                        $rs_type = mysql_fetch_array($sql_type);
                                        echo '<option value="' . $rs_type['acty_id'] . '">' . $rs_type['name'] . '</option>';
                                        ?>
                                    </select></td>
                                <td>New Cheque No: </td>
                                <td><select id="cheque_no" class="medium-input" name="cheque_no" disabled></select></td>
                            </tr>
                            <tr>
                                <td>Payment Type: </td>
                                <td><select id="acpty_id" class="medium-select" name="" readonly>
                                        <?php
                                        $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_ac['acpty_id'] . "'");
                                        $rs_ptype = mysql_fetch_array($sql_ptype);
                                        echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';
                                        ?>
                                    </select></td>
                                <td>New Voucher No.: </td>
                                <td><input id="voucher_no" class="medium-input" type="text" name="voucher_no" value="<?php echo voucherNo(); ?>" readonly disabled></td>
                            </tr>
                            <tr>
                                <td>Cheque Date: </td>
                                <td><input id="cheque_date" class="medium-input" type="date" name="cheque_date" value="<?php echo date("Y-m-d"); ?>"></td>
                                <td>New Cheque: </td>
                                <td><input id='new_cheque' type='checkbox'></td>
                            </tr>
                            <tr>
                                <td>Justification: </td>
                                <td colspan="3"><textarea id="justification" class="medium-textarea-3" readonly><?php echo $rs_ac['justification']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Terms: </td>
                                <td colspan="3"><textarea id="terms" class="medium-textarea-3" readonly><?php echo $rs_ac['terms']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <button id='save' class='large-submit' onclick='save();'>Save</button> 
                                    <button id='print_voucher' class='large-submit' onclick='print_voucher();'>Voucher</button> 
                                    <button id='print_cheque' class='large-submit' onclick='print_cheque();'>Cheque</button> 
                                    <a href='clear_temp.php'><button id="finish" class='large-submit'>Finish</button></a>

                                    <div id='msg'></div>
                                    <div id='err'></div>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <!--                        <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>
                                                <br>-->
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="650" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>

