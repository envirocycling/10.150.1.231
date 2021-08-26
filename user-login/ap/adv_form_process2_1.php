<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}

$sql_ac = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);


$date = date("Y/m/d");

$sql_voucher = mysql_query("SELECT MAX( CONVERT( SUBSTRING_INDEX( voucher_no,  '-' , -1 ) , UNSIGNED INTEGER ) ) as max FROM payment WHERE bank_code =  'SBC'");
$rs_voucher = mysql_fetch_array($sql_voucher);
$voucher_no = $rs_voucher['max'] + 1;
if ($voucher_no < 10) {
    $voucher_no = "0" . $voucher_no;
}
$sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_code = mysql_fetch_array($sql_code);

$sbc_vn = 'SBC_' . $rs_code['code'] . '' . $voucher_no;
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
        </style>
        <script>
            function reload() {
                location.reload();
            }

            $(document).ready(function () {
                $("#cheque_name").select2();
                var acpty = '<?php echo $rs_ac['acpty_id']; ?>';
                if (acpty === '1') {
                    $("#cheque_name").prop('readonly', 'true');
                    $("#bank_code").prop('readonly', 'true');
                    $("#cheque_date").prop('readonly', 'true');
                }
            });

            function markProccess(id) {
                var r = confirm("Are you sure you want to process this request?");
                if (r === true) {
                    var data = 'ac_id=' + id;
                    $.ajax({
                        url: "exec/adv_exec.php?action=processCash",
                        type: 'POST',
                        data: data
                    }).done(function () {
                        alert('Successfully Process');
                        location.replace('adv_list.php');
                    });
                }
            }

            function print_voucher() {
                var account = $("#bank_code").val();
                var voucher_no = $("#voucher_no").val();
                var cheque_date = $("#cheque_date").val();
                cheque_date = cheque_date.replace("-", "/");
                cheque_date = cheque_date.replace("-", "/");
                var val = $("#acct_name").val();
                var splits = val.split("_");
                var name = splits[0];
                var name_new = $("#acct_name_new").val();
                var num = $("#acct_num").val();
                var num_new = $("#acct_num_new").val();
                if (name_new !== '') {
                    var cheque_name = escape(name_new);
                    var acct_num = num_new;
                } else {
                    var cheque_name = escape(name);
                    var acct_num = num;
                }
                var grand_total = $("#amount").val();
                var type = "supplier";
                var user_id = "<? echo $_SESSION['user_id']; ?>";
                var verifier = "<? echo $_SESSION['verifier']; ?>";
                var signatory = "<? echo $_SESSION['signatory']; ?>";

                var particular = "ADVANCES TO SUPPLIER";
                var quantity = $("#amount").val();
                var unit_price = "1";
                var amount = $("#amount").val();
                var click = $("#click").val();


                if (cheque_name === '') {
                    alert('Please Input Account Name.');
                } else if (account === '') {
                    alert('Please Choose Account.');
                } else {
                    var dataString = 'account=' + account + '&cheque_date=' + cheque_date + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account_number=' + acct_num + '&grand_total=' + grand_total + '&type=' + type
                            + '&user_id=' + user_id + '&verifier=' + verifier + '&signatory=' + signatory
                            + '&particular=' + particular + '&quantity=' + quantity + '&unit_price=' + unit_price + '&amount=' + amount;

                    $.ajax({
                        type: "POST",
                        url: "exec/adv_exec.php?payment=submitDigiInitial",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    if (click == 1) {
                        window.open("print_adv_digibanker.php", 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("checker").value = 1;
                    document.getElementById("click").value = 1;
                }
            }

            function save_online() {
                var checker = $("#checker").val();
                var supplier_id = $("#supplier_id").val();

                if (checker === 0) {
                    alert('Please Print the voucher first.');
                } else {
                    var dataString = 'checker=' + checker + '&supplier_id=' + supplier_id;
                    $.ajax({
                        type: "POST",
                        url: "exec/adv_exec.php?payment=submitDigiFinal&ac_id=<?php echo $_GET['ac_id']; ?>",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    var finish = "<a href='clear_temp.php'><button class='large-submit'>Finish</button></a>";
                    var msg = "<font color='red'>Please click the finish button to clear the temporary data and send to online payment system.</font>";
                    document.getElementById("finish").innerHTML = finish;
                    document.getElementById("msg").innerHTML = msg;
                }
            }

            function show(val) {
                var splits = val.split("_");
                document.getElementById("acct_num").value = splits[1];
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
                        <h2>ADVANCE - CHECK FORM</h2>
                        <br>
                        <input type='hidden' id='click' name='click' value='0'>
                        <input type='hidden' id='checker' name='checker' value='0'>

                        <table class="table">
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ac_no" class="medium-input" type="text" name="ac_no" value="<?php echo $rs_ac['ac_no']; ?>" readonly>
                            </tr>
                            <tr>
                                <?php
                                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_ac['supplier_id'] . "'");
                                $rs_sup = mysql_fetch_array($sql_sup);
                                ?>
                                <td>Supplier Name:</td>
                                <td><input id="supplier_id" type="hidden"  name="supplier_id" value="<?php echo $rs_ac['supplier_id']; ?>" readonly>
                                    <input id="supplier_name" type="text" class="medium-select-2" name="supplier_name" value="<?php echo $rs_sup['supplier_name']; ?>" readonly></td>
                                <?php
                                $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_ac['acpty_id'] . "'");
                                $rs_ptype = mysql_fetch_array($sql_ptype);
                                ?>
                                <td>Payment Type: </td>
                                <td><input id="acpty_id" type="text" class="medium-select" name="acpty_id" value="<?php echo $rs_ptype['name']; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>"  readonly></td>
                                <td>Bank Account: </td>
                                <td><input id="bank_code" type="text" class="medium-select" name="bank_code" value="SBC" readonly></td>
                            </tr>
                            <tr>
                                <td>Account Name: </td>
                                <td><select id="acct_name" class="medium-select-2" name="acct_name"  onchange='show(this.value);'>
                                        <option value=""></option>
                                        <?php
                                        $sql = mysql_query("SELECT * FROM sup_bank_accounts WHERE supplier_id='" . $rs_ac['supplier_id'] . "'");
                                        while ($rs = mysql_fetch_array($sql)) {
                                            echo "<option value='" . $rs['account_name'] . "_" . $rs['account_number'] . "'>" . $rs['account_name'] . "</option>";
                                        }
                                        ?>
                                    </select></td>
                                <td>Voucher No.: </td>
                                <td><input id='voucher_no' type='hidden' name='voucher_no' value='<?php echo $voucher_no; ?>'>
                                    <input id="voucher_no" class="medium-input" type="text" name="voucher_no" value="<?php echo $sbc_vn; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td>Account No.: </td>
                                <td><input id="acct_num" class="medium-select-2" type="text" name="acct_num" value="" readonly></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>If New: </td>
                                <td><input id="acct_name_new" class="medium-select-2" type="text" name="acct_name_new" value="" required></td>
                                <td>Cheque Dates: </td>
                                <td><input id="cheque_date" class="medium-input" type="date" name="cheque_date" value="<?php echo date("Y-m-d"); ?>"></td>
                            </tr>
                            <?php
                            $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_ac['acty_id'] . "'");
                            $rs_type = mysql_fetch_array($sql_type);
                            ?>
                            <tr>
                                <td>Account No.: </td>
                                <td><input id="acct_num_new" class="medium-select-2" type="text" name="acct_num_new" value="" required></td>
                                <td>Type: </td>
                                <td><input type="text" id="acty_id" class="medium-select" name="acty_id" value="<?php echo $rs_type['name']; ?>" readonly></td>
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
                                        echo "<button id='voucher' class='large-submit' onclick='print_voucher();'>Voucher</button> ";
                                        echo "<button id='cheque' class='large-submit' onclick='print_cheque();'>Cheque</button>";
                                    } else {
                                        echo "<button class='large-submit' onclick='print_voucher();'>Voucher</button> ";
                                        echo "<button class='large-submit' onclick='save_online();'>Process</button>";
                                    }
                                    ?>
                                    <div id="finish"></div>
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
