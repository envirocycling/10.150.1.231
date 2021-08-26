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

$sql_payment = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_payment = mysql_fetch_array($sql_payment);

$sql_tpc = mysql_query("SELECT * FROM temp_payment WHERE bank_code='" . $rs_payment['bank_code'] . "' and user_id!='" . $_SESSION['user_id'] . "'");
$rs_tpc = mysql_num_rows($sql_tpc);

$sql_count = mysql_query("SELECT count(id) FROM payment_others WHERE payment_id='" . $rs_payment['payment_id'] . "'");
$rs_count = mysql_fetch_array($sql_count);
$row_count = $rs_count['count(id)'];
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
            #new_cheque{
                padding: 10px;
                height: 20px;
                width: 20px;
            }
            .medium-input-4{
                width: 750px;
            }
            .medium-textarea{
                width: 320px;
            }
        </style>
        <script>
            $.row_count = <?php echo $row_count; ?>;
            $.cheque_date = "<?php echo $rs_payment['cheque_date']; ?>";
            $.date_now = "<?php echo date("Y/m/d"); ?>";

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
        <script>

        </script>

        <div class="wrapper">

            <header class="header">

                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <!--                <div class="container">
                                    <main class="content">-->
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <h2>EDIT OTHER PAYMENT</h2>
                <br>

                <input type='hidden' id='click' name='click' value='0'>
                <input type='hidden' id='checker' name='checker' value='0'>
                <input type='hidden' id='payment_id' name='payment_id' value='<?php echo $_GET['payment_id']; ?>'>
                <table width='1000' border='0'>
                    <tr class='head'>
                         <?php
                            if(@$_SESSION['class'] == 'non-trade'){
                                echo "<td><div style='width: 125px;'>Voucher Name: </td>";
                            }else{
                                echo "<td><div style='width: 125px;'>Payee Name: </td>";
                            }
                        ?>
                        <td colspan="3"><select id="payee" class="medium-input-4" name="payee"> 
                                <option value="<?php echo utf8_encode($rs_payment['cheque_name']); ?>"><?php echo utf8_encode($rs_payment['cheque_name']); ?></option>
                                <?php
                                $sql = mysql_query("SELECT * FROM `cheque_name`");
                                while ($rs = mysql_fetch_array($sql)) {
                                    echo '<option value="' . $rs['name'] . '">' . $rs['name'] . '</option>';
                                }
                                ?>
                            </select></td>
                    </tr>
                    <tr class='head'>
                        <td>If New: </td>
                        <td colspan="3"><input id='payee_new' class="medium-input-4" type='text' name='payee_new' value='' autocomplete="off"></td>
                    </tr>
                     <?php
                        if(@$_SESSION['class'] == 'non-trade'){
                            echo '<input type="hidden" value="1" id="chk_nontrade">';
                           echo "<td align='left'>Cheque Name: </td>";
                            echo '<td colspan="3">
                            <select id="payee2" class="medium-input-4" name="payee2"">';
                            if(empty($rs_payment['cheque_name2'])){
                                echo '<option value="'.utf8_encode($rs_payment['cheque_name']).'">'.utf8_encode($rs_payment['cheque_name']).'</option>';                                
                            }else{
                                echo '<option value="'.utf8_encode($rs_payment['cheque_name2']).'">'.utf8_encode($rs_payment['cheque_name2']).'</option>';
                            }
                                $sql = mysql_query("SELECT * FROM `cheque_name`");
                                while ($rs = mysql_fetch_array($sql)) {
//                                            echo "<option value='" . mysql_real_escape_string(utf8_encode($rs['name'])) . "'>" . utf8_encode($rs['name']) . "</option>";
                                    echo '<option value="' . $rs['name'] . '">' . $rs['name'] . '</option>';
                                }
                            echo '</select></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo "<td align='left'>If New: </td>";
                            echo '<td colspan="3"><input id="payee_new2" class="medium-input-4" type="text" name="payee_new2" placeholder="Cheque Name" value="" autocomplete="off" maxlength="200"></td>
                    </tr>';
                        }
                    ?>
                    <tr>
                        <td>Account.: </td>
                        <td><input id='old_bank_code' class='medium-input-3' type="text" name="old_bank_code" value="<?php echo $rs_payment['bank_code']; ?>" readonly></td>
                        <td>Cheque Date: </td>
                        <td><?php echo '<input type="text" class="tcal" id="cheque_date" value="' . $rs_payment['cheque_date'] . '" size="10" required>'; ?></td>
                    </tr>
                    <tr>
                        <td>Voucher No.: </td>
                        <td><input id='old_voucher_no' class='medium-input-3' type='text' name='old_voucher_no' value='<?php echo $rs_payment['voucher_no']; ?>' readonly></td>
                        <td>Type: </td>
                        <td><select id="type" class="tcal" name="type" onchange="verifierSignatory(this.value);">
                                <?php
                                if ($rs_payment['type'] == 'others') {
                                    echo "<option value='" . $rs_payment['type'] . "'>Non Trade</option>";
                                    $veriType = "nontrade_verifier";
                                    $signType = "nontrade_signatory";
                                } else {
                                    echo "<option value='" . $rs_payment['type'] . "'>Trade</option>";
                                    $veriType = "trade_verifier";
                                    $signType = "trade_signatory";
                                }
                                ?>
                                <option value="others">Non Trade</option>
                                <option value="supplier">Trade</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td>Cheque No.: </td>
                        <td><input id='old_cheque_no' class='medium-input-3' type='text' name='old_cheque_no' value='<?php echo $rs_payment['cheque_no']; ?>' readonly></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width='175'>New Account: </td>
                        <td width='175'>
                            <?php
                            echo "<select id='bank_code' class='medium-input-3' name='bank_code' onchange='change(this.value);' disabled>";
                            $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code='" . $rs_payment['bank_code'] . "'");
                            $rs_bank = mysql_fetch_array($sql_bank);
                            echo "<option value='" . $rs_payment['bank_code'] . "'>" . $rs_payment['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                            $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code!='" . $rs_payment['bank_code'] . "' and status!='deleted'");
                            while ($rs_bank = mysql_fetch_array($sql_bank)) {
                                echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                            }
                            echo "</select>";
                            ?>
                        <td width='175'>AP: </td>
                        <td width='175'>
                            <?php
                            echo "<input type = 'hidden' id='user_id' name = 'user_id' value = '" . $_SESSION['user_id'] . "'>
                                    <input type = 'text' class='medium-input-3'  name = 'user_id' value = '" . strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)) . "' readonly>";
                            ?>
                    </tr>
                    <tr>
                        <td>New Voucher No.: </td>
                        <td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value=''  disabled readonly></td>
                        <td>Verifier: </td>
                        <td>
                            <?php
                            echo "<input id='verifier' class='medium-input-3'  type='hidden' name='verifier' value='" . $_SESSION[$veriType] . "' readonly>";
                            echo "<input id='verifier_name' class='medium-input-3'  type='text' name='verifier_name' value='" . getUser($_SESSION[$veriType]) . "' readonly>";
                            ?>
                    </tr>
                    <tr>
                        <td>New Cheque No.: </td>
                        <td><select id='cheque_no' class='medium-input-3' name='cheque_no' disabled>
                            </select></td>
                        <td>Signatory</td>
                        <td>
                            <?php
                            echo "<input id='signatory' class='medium-input-3'  type='hidden' name='verifier' value='" . $_SESSION[$signType] . "' readonly>";
                            echo "<input id='signatory_name' class='medium-input-3'  type='text' name='signatory_name' value='" . getUser($_SESSION[$signType]) . "' readonly>";
                            ?></td>
                    </tr>
                    <tr>
                        <td>New Cheque: </td>
                        <td><input id='new_cheque' type='checkbox'></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Description: </td>
                        <td colspan="3"><textarea id="description" class="medium-textarea" maxlength="200"><?php echo $rs_payment['description']; ?></textarea></td>
                    </tr>
                </table>
                <br>
                <div class="payTable" style="width: 1100px;">
                    <table>
                        <tr class='head'>
                            <td>Particulars</td>
                            <td>Description</td>
                            <td>Quantity</td>
                            <td>Unit Price</td>
                            <td>Amount</td>
                        </tr>
                        <?php
                        $c = 1;
                        $sql_particular = mysql_query("SELECT * FROM payment_others WHERE payment_id='" . $rs_payment['payment_id'] . "'");
                        while ($rs_particular = mysql_fetch_array($sql_particular)) {
                            echo "<input id='others_id_$c' type='hidden' name='others_id_$c' value='" . $rs_particular['id'] . "'>";
                            echo "<tr id='row_$c'>
                                <td><input id='particular_$c' class='medium-input-2' type='text' name='particular$c' value='" . $rs_particular['particulars'] . "' onkeyup='compute();'></td>
                                <td><input id='description_$c' class='medium-input-2' type='text' name='description$c' value='" . $rs_particular['description'] . "' onkeyup='compute();'></td>
                                <td><input id='quantity_$c' class='medium-input' type='text' name='quantity$c' value='" . $rs_particular['quantity'] . "' onkeyup='compute();' onkeypress='return isNumber(event)'></td>
                                <td><input id='unit_price_$c' class='medium-input' type='text' name='unit_price$c' value='" . $rs_particular['unit_price'] . "' onkeyup='compute();' onkeypress='return isNumber(event)'></td>
                                <td><input id='amount_$c' class='medium-input-money' type='text' name='amount$c' value='" . $rs_particular['amount'] . "' readonly></td>
                                </tr>";
                            $c++;
                        }

                        while ($c <= 20) {
                            echo "<input id='others_id_$c' type='hidden' name='others_id_$c' value=''>";
                            echo "<tr id='row_$c'>
                                <td><input id='particular_$c' class='medium-input-2' type='text' name='particular$c' value='' onkeyup='compute();'></td>
                                <td><input id='description_$c' class='medium-input-2' type='text' name='description$c' value='' onkeyup='compute();'></td>
                                <td><input id='quantity_$c' class='medium-input' type='text' name='quantity$c' value='' onkeyup='compute();' onkeypress='return isNumber(event)'></td>
                                <td><input id='unit_price_$c' class='medium-input' type='text' name='unit_price$c' value='' onkeyup='compute();' onkeypress='return isNumber(event)'></td>
                                <td><input id='amount_$c' class='medium-input-money' type='text' name='amount$c' value='' readonly></td>
                                </tr>";
                            $c++;
                        }
                        ?>
                        <tr class='head'>
                            <td>Grand Total</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><input id='grand_total' class='medium-input-money' type='text' name='grand_total' value='<?php echo $rs_payment['grand_total']; ?>' readonly></td>
                        </tr>
                    </table>
                </div>
                <br>
                <div align="right" style="width: 1000px;">
                    <input id='row_show' type='hidden' name='row_show' value='<?php echo $row_count; ?>' readonly>
                    <button id="plus" class="plusMinus">+</button> <button id="minus" class="plusMinus">-</button>
                </div>
                <br>
                <table width="1000">
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


                <!--                    </main> .content 
                                </div> .container
                
                                <aside class="left-sidebar">
                                    <iframe id="pending" src="template/pending2.php" width="367" height="800" scrolling="yes"></iframe>
                                </aside> .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>