<?php
session_start();
include 'config.php';

$sql_ac = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
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
        </style>
        <script>
            $.acpty = '<?php echo $rs_ac['acpty_id']; ?>';
            $.ac_id = '<?php echo $_GET['ac_id']; ?>';
            $.others_id = '';
            $.user_id = "<?php echo $_SESSION['user_id']; ?>";
            $.verifier = "<?php echo $_SESSION['verifier']; ?>";
            $.signatory = "<?php echo $_SESSION['signatory']; ?>";
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
<!--                        <input type='hidden' id='click' name='click' value='0'>
                        <input type='hidden' id='checker' name='checker' value='0'>-->
                        <input type='hidden' id='payment_id' name='payment_id' value=''>
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
                                <td>Payment Type: </td>
                                <td><select id="acpty_id" class="medium-select" name="" readonly>
                                        <?php
                                        $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_ac['acpty_id'] . "'");
                                        $rs_ptype = mysql_fetch_array($sql_ptype);
                                        echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>"  readonly></td>
                                <td>Bank Account: </td>
                                <td><select id="bank_code" class="medium-select" name="bank_code" onchange="change(this.value)
                                                ;">
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
                                <td>Cheque Name: </td>
                                <td><select id="cheque_name" class="medium-select-2" name="cheque_name" readonly>
                                        <option value=""></option>
                                        <?php
                                        $sql_name = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $rs_ac['supplier_id'] . "'");
                                        while ($rs_name = mysql_fetch_array($sql_name)) {
                                            echo '<option value="' . $rs_name['name'] . '">' . $rs_name['name'] . '</option>';
                                        }
                                        ?>
                                    </select></td>
                                <td>Cheque No: </td>
                                <td><select id='cheque_no' class='medium-input' name='cheque_no'></select></td>
                            </tr>
                            <tr>
                                <td>If New: </td>
                                <td><input id="cheque_name_new" class="medium-select-2" type="text" name="cheque_name" value="" required></td>
                                <td>Voucher No.: </td>
                                <td><input id="voucher_no" class="medium-input" type="text" name="voucher_no" value="" readonly></td>

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
                                <td>Cheque Dates: </td>
                                <td><input id="cheque_date" class="medium-input" type="date" name="cheque_date" value="<?php echo date("Y-m-d"); ?>"></td>
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
                                    <?php
                                    if ($rs_ac['acpty_id'] == '1') {
                                        echo "<button id='" . $_GET['ac_id'] . "' class='large-submit' onclick='markProccess(this.id);'>Process</button>";
                                    } else if ($rs_ac['acpty_id'] == '2') {
                                        echo "<button id='save' class='large-submit' onclick='save();'>Save</button> ";
                                        echo "<button id='print_voucher' class='large-submit' onclick='print_voucher();'>Voucher</button> ";
                                        echo "<button id='print_cheque' class='large-submit' onclick='print_cheque();'>Cheque</button>";
                                    } else {
                                        echo "<a href='adv_view.php?ac_id=" . $_GET['ac_id'] . "'><button class='large-submit'>Back</button>";
                                    }
                                    ?>
                                    <a href='clear_temp.php'><button id="finish" class='large-submit'>Finish</button></a>
                                    <div id='msg'></div>
                                    <div id='err'></div>
                                </td>
                            </tr>
                        </table>
                        <br>
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
