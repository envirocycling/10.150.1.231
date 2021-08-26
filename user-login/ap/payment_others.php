<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';

function getUser($id) {
    $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='$id'");
    $rs_sig = mysql_fetch_array($sql_sig);
    $val = strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1));
    return $val;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link rel="stylesheet" type="text/css" href="css/pay_form.css" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="js/payment/payOthers_rev2.js"></script>
        <style>
            table{
                font-size: 15px;
                font-weight: bold;
            }
            .plusMinus{
                height: 40px;
                width: 40px;
            }
            .medium-textarea{
                width: 320px;
            }
            .chk{
                padding: 10px;
                height: 20px;
                width: 20px;
            }
        </style>
        <script>
            $.row_count = 1;
            $.verifier = "<?php echo $_SESSION['trade_verifier']; ?>";
            $.signatory = "<?php echo $_SESSION['trade_signatory']; ?>";
            $.verifier_name = "<?php echo getUser($_SESSION['trade_verifier']); ?>";
            $.signatory_name = "<?php echo getUser($_SESSION['trade_signatory']); ?>";

            $.verifier2 = "<?php echo $_SESSION['nontrade_verifier']; ?>";
            $.signatory2 = "<?php echo $_SESSION['nontrade_signatory']; ?>";
            $.verifier_name2 = "<?php echo getUser($_SESSION['nontrade_verifier']); ?>";
            $.signatory_name2 = "<?php echo getUser($_SESSION['nontrade_signatory']); ?>";
        </script>
    </head>

    <body>

        <div class="wrapper">

            <header class="header">

                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">
                <?php
                include 'template/menu.php';
                ?>

                <br>
                <h2>OTHER PAYMENT</h2>
                <br>
                <!--<input type='hidden' id='click' name='click' value='0'>-->
                <!--<input type='hidden' id='checker' name='checker' value='0'>-->
                <input type='hidden' id='payment_id' name='payment_id' value=''>
                <table width='1000' border='0'>
                    <tr class='head'>
                        <?php
                            if(@$_SESSION['class'] == 'non-trade'){
                                echo "<td align='left'>Voucher Name: </td>";
                            }else{
                                echo "<td align='left'>Payee Name: </td>";
                            }
                        ?>
                        <td>
                            <select id="payee" class="medium-input-2" name="payee"> 
                                <option selected value="">--- Select Payee ---</option>
                                <?php
                                $sql = mysql_query("SELECT * FROM `cheque_name`");
                                while ($rs = mysql_fetch_array($sql)) {
//                                            echo "<option value='" . mysql_real_escape_string($rs['name']) . "'>" . $rs['name'] . "</option>";
                                    echo '<option value="' . $rs['name'] . '">' . $rs['name'] . '</option>';
                                }
                                ?>
                            </select></td>
                        <td align='left'>Cheque Date: </td>
                        <td><?php echo '<input type="text" class="tcal" id="cheque_date" value="' . date('Y/m/d') . '" size="10" required>'; ?></td>
                    </tr>
                    <tr class='head'>
                        <td align='left'>If New: </td>
                        <td><input id='payee_new' class="medium-input-2" type='text' name='payee_new' value='' autocomplete="off" maxlength="200"></td>
                        <td>Type: </td>
                        <td><select id="type" class="tcal" name="type" onchange="verifierSignatory(this.value);">
                                <option value="others">Non Trade</option>
                                <option value="supplier">Trade</option>
                            </select></td>
                    </tr>
                    <?php
                        if(@$_SESSION['class'] == 'non-trade'){
                            echo '<input type="hidden" value="1" id="chk_nontrade">';
                           echo "<td align='left'>Cheque Name: </td>";
                            echo '<td>
                            <select id="payee2" class="medium-input-2" name="payee2"> 
                                <option selected value="">--- Select Payee ---</option>';
                                $sql = mysql_query("SELECT * FROM `cheque_name`");
                                while ($rs = mysql_fetch_array($sql)) {
//                                            echo "<option value='" . mysql_real_escape_string($rs['name']) . "'>" . $rs['name'] . "</option>";
                                    echo '<option value="' . $rs['name'] . '">' . $rs['name'] . '</option>';
                                }
                            echo '</select></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo "<td align='left'>If New: </td>";
                            echo '<td><input id="payee_new2" class="medium-input-2" type="text" name="payee_new2" placeholder="Cheque Name" value="" autocomplete="off" maxlength="200"></td>
                    </tr>';
                        }
                    ?>
                    <tr>
                        <td width='175'>Select Account: </td>
                        <td width='175'>
                            <?php
                            echo "<select id='bank_code' class='medium-input-3' name='bank_code' onchange='change(this.value);' required>";
                            echo "<option value=''></option>";
                            $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE status!='deleted'");
                            while ($rs_bank = mysql_fetch_array($sql_bank)) {
                                echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        <td width='175'>AP: </td>
                        <td width='175'>
                            <?php
                            echo "<input type = 'text' class='medium-input-3'   name = 'user_id' value = '" . strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)) . "' readonly>";
                            ?>
                    </tr>
                    <tr>
                        <td>Cheque No.: </td>
                        <td><select id='cheque_no' class='medium-input-3' name='cheque_no'></select></td>
                        <td>Verifier: </td>
                        <td>
                            <?php
                            echo "<input id='verifier' class='medium-input-3'  type='hidden' name='verifier' value='" . $_SESSION['nontrade_verifier'] . "' readonly>";
                            echo "<input id='verifier_name' class='medium-input-3'  type='text' name='verifier_name' value='" . getUser($_SESSION['nontrade_verifier']) . "' readonly>";
                            ?>
                    </tr>
                    <tr>
                        <td>Voucher No.: </td>
                        <td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value='' required readonly></td>
                        <td>Signatory</td>
                        <td><?php
                            echo "<input id='signatory' class='medium-input-3'  type='hidden' name='verifier' value='" . $_SESSION['nontrade_signatory'] . "' readonly>";
                            echo "<input id='signatory_name' class='medium-input-3'  type='text' name='signatory_name' value='" . getUser($_SESSION['nontrade_signatory']) . "' readonly>";
                            ?></td>
                    </tr>

                    <tr>
                        <td>Description: </td>
                        <td colspan="3"><textarea id="description" class="medium-textarea" maxlength="200"></textarea></td>
                    </tr>
                    <td>Excluded on expense <input type="checkbox" class="chk" name="chk" value="fund transfer"> </td>
                </table>
                <br><br>
                <div class="payTable" style="width: 1100px;">
                    <table>
                        <tr>
                            <td>Particulars</td>
                            <td>Description</td>
                            <td>Quantity</td>
                            <td>Unit Price</td>
                            <td>Amount</td>
                        </tr>
                        <?php
                        $c = 1;
                        while ($c <= 20) {
                            echo "<input id='others_id_$c' type='hidden' name='others_id_$c' value=''>";
                            echo "<tr id='row_$c'>
                                <td><input id='particular_$c' class='medium-input-2' type='text' name='particular$c' value='' maxlength='100'></td>
                                <td><input id='description_$c' class='medium-input-2' type='text' name='description$c' value='' maxlength='100'></td>
                                <td><input id='quantity_$c' class='medium-input' type='text' name='quantity$c' value='' onkeyup='compute();' onkeypress='return isNumber(event)'></td>
                                <td><input id='unit_price_$c' class='medium-input' type='text' name='unit_price$c' value='' onkeyup='compute();' onkeypress='return isNumber(event)'></td>
                                <td><input id='amount_$c' class='medium-input-money' type='text' name='amount$c' value='' readonly></td>
                                </tr>";
                            $c++;
                        }
                        ?>
                        <tr>
                            <td>Grand Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><input id='grand_total' class='medium-input-money' type='text' name='grand_total' value='' readonly></td>
                        </tr>
                    </table>

                </div>
                <br>
                <div align="right" style="width: 1000px;">
                    <input id='row_show' type='hidden' name='row_show' value='1' readonly>
                    <button id="plus" class="plusMinus">+</button> <button id="minus" class="plusMinus">-</button>
                </div>

                <!--                        <br>
                                        <table width='700' class='details' border='1'>
                                            
                                        </table>-->
                <br>
                <table border="0" width="1000">
                    <tr>
                        <td colspan="2">
                            <button id="save" class='large-submit' onClick="save();">Save</button>&nbsp;
                            <button id="print_voucher" class='large-submit' onClick="print_voucher();">Voucher</button>&nbsp;
                            <button id="print_cheque" class='large-submit' onClick="print_cheque();">Cheque</button>&nbsp;
                            <a href="clear_temp.php"><button id="finish" class="large-submit">Finish</button></a>&nbsp;
                    </tr>
                    <tr>
                        <td colspan='3'>
                            <div id='msg'></div>
                            <div id="err"></div>
                        </td>
                    </tr>
                </table>
                <br><br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
