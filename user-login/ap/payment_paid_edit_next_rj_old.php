<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
if (!isset($_SESSION['verifier'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
        history.back();

    </script>";
}
if (!isset($_SESSION['signatory'])) {
    echo "<script>
    alert('Verifier is not set Please go to settings and update the setup.');
    history.back();
    </script>";
}

$sql_che = mysql_query("SELECT max(cheque_no) FROM payment WHERE bank_code='" . $_POST['bank_code'] . "' and cheque_status!='issued' and status!='deleted'");
$rs_che = mysql_fetch_array($sql_che);
$sql_c_r = mysql_query("SELECT * FROM cheque_range WHERE bank_code='" . $_POST['bank_code'] . "' and status=''");
$rs_c_r = mysql_fetch_array($sql_c_r);

if ($rs_che['max(cheque_no)'] < $rs_c_r['from']) {
    $cheque_no = sprintf("%010s", $rs_c_r['from']);
} else if ($rs_che['max(cheque_no)'] >= $rs_c_r['to']) {
    $cheque_no = "Range Exceed";
} else {
    $cheque_no = sprintf("%010s", $rs_che['max(cheque_no)'] + 1);
}

$voucher_date = date("md");
$date = date("Y/m/d");
$sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE bank_code='" . $_POST['bank_code'] . "' and date='$date' and status!='deleted'");
$rs_voucher = mysql_fetch_array($sql_voucher);
if ($rs_voucher['count(voucher_no)'] == '0' || $rs_voucher['count(voucher_no)'] == '') {
    $voucher_numberq = "01";
} else {

    $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);

    $voucher_numberq = $details[1] + 1;
    if ($voucher_numberq < 10) {
        $voucher_numberq = "0" . $voucher_numberq;
    }
}


$sql_tpc = mysql_query("SELECT * FROM temp_payment WHERE bank_code='" . $_POST['bank_code'] . "' and user_id!='" . $_SESSION['user_id'] . "'");
$rs_tpc = mysql_num_rows($sql_tpc);

$sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_POST['payment_id'] . "'");
$rs_pay = mysql_fetch_array($sql_pay);
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/pay_form.css" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <style>
            table{
                font-size: 15px;
                font-weight: bold;
            }
            .table {
                font-weight: bold;
                font-size: 15px;
            }
            .check{
                position: absolute;
                margin-left: -35px;
            }
            #new_cheque{
                padding: 10px;
                height: 20px;
                width: 20px;
            }
        </style>
        <script>
            function reload() {
                location.reload();
            }

            $(document).ready(function () {
                $('#cheque_name_old').select2();

                $("#new_cheque").click(function () {
                    var check = $('#new_cheque').is(":checked");
                    if (check === true) {
                        $("#old_account").attr('disabled', true);
                        $("#old_cheque_no").attr('disabled', true);
                        $("#old_voucher_no").attr('disabled', true);
                        $("#account").attr('disabled', false);
                        $("#cheque_no").attr('disabled', false);
                        $("#voucher_no").attr('disabled', false);
                    } else {
                        $("#old_account").attr('disabled', false);
                        $("#old_cheque_no").attr('disabled', false);
                        $("#old_voucher_no").attr('disabled', false);
                        $("#account").attr('disabled', true);
                        $("#cheque_no").attr('disabled', true);
                        $("#voucher_no").attr('disabled', true);
                    }
                });

                var c = '<?php echo $rs_tpc; ?>';
                if (c >= 1) {
                    document.getElementById("print_voucher").disabled = true;
                    document.getElementById("print_cheque").disabled = true;

                    var finish = "<button class='large-submit' onclick='reload();'>Refresh</button>";
                    var msg = "<font color='red'>Other users is using this Bank Account, Please refresh this page and try again.</font>";
                    document.getElementById("finish").innerHTML = finish;
                    document.getElementById("msg").innerHTML = msg;
                }
            }
            );
            function print_details() {
                var check = $('#new_cheque').is(":checked");
                if (check === true) {
                    var account = $("#account").val();
                    var cheque_no = $("#cheque_no").val();
                    var old_cheque_no = $("#old_cheque_no").val();
                    var voucher_no = $("#voucher_no").val();
                } else {
                    var account = $("#old_account").val();
                    var cheque_no = $("#old_cheque_no").val();
                    var old_cheque_no = $("#old_cheque_no").val();
                    var voucher_no = $("#old_voucher_no").val();
                }

                var cheque_name_new = escape($("#cheque_name_new").val());
                if (cheque_name_new === '') {
                    var cheque_name = escape($("#cheque_name").val());
                } else {
                    var cheque_name = cheque_name_new;
                }


                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();

                if (account == '') {
                    alert('Please Choose Account.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&supplier_id=' + supplier_id;
                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinal",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    window.open("print_details.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                }
            }

            function print_voucher() {
                var check = $('#new_cheque').is(":checked");
                if (check === true) {
                    var account = $("#account").val();
                    var cheque_no = $("#cheque_no").val();
                    var old_cheque_no = $("#old_cheque_no").val();
                    var voucher_no = $("#voucher_no").val();
                } else {
                    var account = $("#old_account").val();
                    var cheque_no = $("#old_cheque_no").val();
                    var old_cheque_no = $("#old_cheque_no").val();
                    var voucher_no = $("#old_voucher_no").val();
                }

                var cheque_name_new = escape($("#cheque_name_new").val());
                if (cheque_name_new === '') {
                    var cheque_name = escape($("#cheque_name").val());
                } else {
                    var cheque_name = cheque_name_new;
                }

                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var click = $("#click").val();
                if (account == '') {
                    alert('Please Choose Account.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_no == 'Range Error') {
                    alert('Please Input New Cheque Range.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    document.getElementById("checker").value = 1;

                    var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&supplier_id=' + supplier_id + '&old_cheque_no=' + old_cheque_no;

                    $.ajax({
                        type: "POST",
                        url: "exec/recPay_exec.php?payment=submitFinal",
                        data: dataString
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    if (click == 1) {
                        window.open("print_voucher.php?trans_id=" + tras_array, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("click").value = 1;
                }
            }
            function print_cheque() {
                var save = $("#save").val();
                var check = $('#new_cheque').is(":checked");
                if (check === true) {
                    var account = $("#account").val();
                    var cheque_no = $("#cheque_no").val();
                    var old_cheque_no = $("#old_cheque_no").val();
                    var voucher_no = $("#voucher_no").val();
                } else {
                    var account = $("#old_account").val();
                    var cheque_no = $("#old_cheque_no").val();
                    var old_cheque_no = $("#old_cheque_no").val();
                    var voucher_no = $("#old_voucher_no").val();
                }

                var cheque_date = $("#cheque_date").val();

                var cheque_name_new = escape($("#cheque_name_new").val());
                if (cheque_name_new === '') {
                    var cheque_name = escape($("#cheque_name").val());
                } else {
                    var cheque_name = cheque_name_new;
                }

                var tras_array = $("#tras_array").val();
                var supplier_id = $("#supplier_id").val();
                var checker = $("#checker").val();
                var payment_id = $("#payment_id").val();
                if (account == '') {
                    alert('Please Choose Account.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else if (cheque_name == '' && cheque_name_new == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else {
                    if (checker == 0) {
                        alert('Please Print the voucher first.');
                    } else {

                        var dataString = 'cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&account=' + account + '&tras_array=' + tras_array + '&supplier_id=' + supplier_id + '&old_cheque_no=' + old_cheque_no + '&payment_id=' + payment_id;

                        if (save === '0') {
                            $.ajax({
                                type: "POST",
                                url: "exec/recPay_exec.php?payment=submitFinalChequeEdit",
                                data: dataString
                            }).done(function (msg) {
                                $("#save").val('1');
                                $("#err").html(msg);
                            });
                            var finish = "<a href='clear_temp.php'><button class='large-submit'>Finish</button></a>";
                            var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
                            document.getElementById("finish").innerHTML = finish;
                            document.getElementById("msg").innerHTML = msg;
                        }
                        window.open("print_cheque.php?trans_id=" + tras_array + "&date=" + cheque_date, 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                }


            }

            function change(val) {

                var bank_code = '<?php echo $rs_pay['bank_code']; ?>';

                if (val == bank_code) {
                    $("#cheque_no").prop('readonly', false);
                    $("#voucher_no").prop('readonly', false);
                } else {
                    $("#cheque_no").prop('readonly', true);
                    $("#voucher_no").prop('readonly', true);
                }
<?php
$sql_b = mysql_query("SELECT * FROM bank_accounts");
while ($rs_b = mysql_fetch_array($sql_b)) {
    $sql_che = mysql_query("SELECT max(cheque_no) FROM payment WHERE bank_code='" . $rs_b['bank_code'] . "' and cheque_status!='issued' and status!='deleted'");
    $rs_che = mysql_fetch_array($sql_che);
    $sql_c_r = mysql_query("SELECT * FROM cheque_range WHERE bank_code='" . $rs_b['bank_code'] . "' and status=''");
    $rs_c_r_c = mysql_num_rows($sql_c_r);
    $rs_c_r = mysql_fetch_array($sql_c_r);
    ?>
                    if (val == '<?php echo $rs_b['bank_code']; ?>') {
                        var value = '<?php
    if ($rs_c_r_c == 0) {
        echo "Range Error";
    } else {
        if ($rs_che['max(cheque_no)'] < $rs_c_r['from']) {
            echo sprintf("%010s", $rs_c_r['from']);
        } else if ($rs_che['max(cheque_no)'] >= $rs_c_r['to']) {
            echo "Range Error";
        } else {
            echo sprintf("%010s", $rs_che['max(cheque_no)'] + 1);
        }
    }
    ?>';
    <?php
    $voucher_date = date("md");
    $date = date("Y/m/d");
    $sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE bank_code='" . $rs_b['bank_code'] . "' and date='$date' and status!='deleted'");
    $rs_voucher = mysql_fetch_array($sql_voucher);
    if ($rs_voucher['count(voucher_no)'] == '0' || $rs_voucher['count(voucher_no)'] == '') {
        $voucher_number = "01";
    } else {
        $details = preg_split("[-]", $rs_voucher['max(voucher_no)']);
        $voucher_number = $details[1] + 1;
        if ($voucher_number < 10) {
            $voucher_number = "0" . $voucher_number;
        }
    }
    ?>
                        var value2 = "<?php echo $voucher_date; ?>-<?php echo $voucher_number; ?>";
                                    document.getElementById("cheque_no").value = value;
                                    document.getElementById("voucher_no").value = value2;

    <?php
    $sql_tpc = mysql_query("SELECT * FROM temp_payment WHERE bank_code='" . $rs_b['bank_code'] . "' and user_id!='" . $_SESSION['user_id'] . "'");
    $rs_tpc = mysql_num_rows($sql_tpc);
    ?>
                                    var c = '<?php echo $rs_tpc; ?>';
                                    if (c >= 1) {
                                        if ($("#account").val() == '1') {
                                            document.getElementById("print_details").disabled = true;
                                        }
                                        document.getElementById("print_voucher").disabled = true;
                                        document.getElementById("print_cheque").disabled = true;

                                        var finish = "<button class='large-submit' onclick='reload();'>Refresh</button>";
                                        var msg = "<font color='red'>Other users is using this Bank Account, Please refresh this page and try again.</font>";
                                        document.getElementById("finish").innerHTML = finish;
                                        document.getElementById("msg").innerHTML = msg;
                                    } else {
                                        if ($("#account").val() == '1') {
                                            document.getElementById("print_details").disabled = false;
                                        }
                                        document.getElementById("print_voucher").disabled = false;
                                        document.getElementById("print_cheque").disabled = false;

                                        var finish = "";
                                        var msg = "";
                                        document.getElementById("finish").innerHTML = finish;
                                        document.getElementById("msg").innerHTML = msg;
                                    }
                                }
    <?php
}
?>
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
                        <?php
                        $details = $_POST['trans_id'];
                        $que = preg_split("[_]", $details);
                        echo "<input id='save' type='hidden' name='save' value='0'>";
                        echo "<input id='click' type='hidden' name='click' value='0'>";
                        echo "<input id='checker' type='hidden' name='checker' value='0'>";
                        echo "<input id='tras_array' type='hidden' name='tras_array' value='$details'>";
                        echo "<input id='payment_id' type='hidden' name='payment_id' value='" . $_POST['payment_id'] . "'>";
                        echo "<input id='supplier_id' type='hidden' name='supplier_id' value='" . $_POST['supplier_id'] . "'>";

                        echo "<h2>PAYMENT FOR THE DELIVERIES OF " . $_POST['sup_name'] . "</h2>";
                        echo "<br>";
                        echo "<table class='table' border='0'>";
                        echo "<tr>";
                        echo "<td>Payee Name:</td>";
                        echo "<td colspan='3'>";
                        echo "<select id='cheque_name' class='medium-input-4' name='cheque_name' required>";
                        echo "<option value='" . $_POST['cheque_name'] . "'>" . $_POST['cheque_name'] . "</opttion>";
                        $sql_cheque = mysql_query("SELECT * FROM cheque_name WHERE supplier_id='" . $_POST['sup_id'] . "' and name!='" . $_POST['cheque_name'] . "'");
                        while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
                            echo "<option value='" . $rs_cheque['name'] . "'>" . $rs_cheque['name'] . "</option>";
                        }
                        echo "</select></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>If New</td>";
                        echo "<td colspan='3'><input id='cheque_name_new' class='medium-input-4' type='text' name='cheque_name_new' value=''></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Account: </td>";
                        echo "<td><input id='old_account' class='medium-select-2' type='text' name='old_account' value='" . $_POST['bank_code'] . "' readonly></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;Cheque Date:</td>";
                        echo "<td><input class='tcal' id='cheque_date' type='text' name='cheque_date' value='' readonly></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Voucher No: </td>";
                        echo "<td><input id='old_voucher_no' class='medium-input-3' type='text' name='voucher_no' value='" . $rs_pay['voucher_no'] . "' readonly></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>Cheque No: </td>";
                        echo "<td><input id='old_cheque_no' class='medium-input-3' type='text' name='cheque_no' value='" . $_POST['c_cheque_no'] . "' readonly></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>New Account: </td>";
                        echo "<td><select id='account' class='medium-select-2' name='account' onchange='change(this.value);' disabled>";
                        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code='" . $_POST['bank_code'] . "'");
                        $rs_bank = mysql_fetch_array($sql_bank);
                        echo "<option value='" . $_POST['bank_code'] . "'>" . $_POST['bank_code'] . "- " . $rs_bank['location'] . "</option>";
                        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code!='" . $_POST['bank_code'] . "'");
                        while ($rs_bank = mysql_fetch_array($sql_bank)) {
                            echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                        }
                        echo "</select></td>";
                        echo "<td>&nbsp;&nbsp;&nbsp;AP:</td>";
                        echo "<td><input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>"
                        . "<input type='text' class='medium-input-3' name='user_id' value='" . strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)) . "'  readonly></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>New Voucher No: </td>";
                        echo "<td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value='" . $voucher_date . "-" . $voucher_numberq . "' disabled readonly></td>";
                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td>&nbsp;&nbsp;&nbsp;Verifier: </td>";
                        echo "<td><input id='verifier' class='medium-input-3' type='text name='verifier' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly></td>";
                        echo "</tr>";
                        echo "<tr height='30'>";
                        echo "<td>New Cheque No: </td>";
                        echo "<td><input id='cheque_no' class='medium-input-3' type='text' name='cheque_no' value='$cheque_no' disabled readonly></td>";

                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
                        $rs_sig = mysql_fetch_array($sql_sig);
                        echo "<td>&nbsp;&nbsp;&nbsp;Signatory: </td>";
                        echo "<td><input id='signatory' class='medium-input-3' type='text name='signatory' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly></td>";
                        echo "</tr>";



//
//
//                        echo "<tr height='30'>";
//                        echo "<td>Select Account: </td>";
//                        echo "<td><select id='account' class='medium-select-2' name='account' onchange='change(this.value);' readonly>";
//                        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code='" . $_POST['bank_code'] . "'");
//                        $rs_bank = mysql_fetch_array($sql_bank);
//                        echo "<option value='" . $_POST['bank_code'] . "'>" . $_POST['bank_code'] . "- " . $rs_bank['location'] . "</option>";
//                        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code!='" . $_POST['bank_code'] . "'");
//                        while ($rs_bank = mysql_fetch_array($sql_bank)) {
//                            echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . " - " . $rs_bank['location'] . "</option>";
//                        }
//                        echo "</select></td>";
//                        echo "<td>&nbsp;&nbsp;&nbsp;Voucher No: </td>";
//                        echo "<td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value='" . $voucher_date . "-" . $voucher_numberq . "'></td>";
//                        echo "</tr>";
//                        echo "<tr height='30'>";
//                        echo "<td>Cheque No: </td>";
//                        echo "<td><input id='old_cheque_no' class='medium-input-3' type='text' name='cheque_no' value='" . $_POST['c_cheque_no'] . "' readonly><img id='check_old' class='check' src='images/check.jpg'></td>";
//                        echo "<td></td>";
//                        echo "<td></td>";
//                        echo "</tr>";
//                        echo "<tr height='30'>";
//                        echo "<td>New Cheque No: </td>";
//                        echo "<td><input id='cheque_no' class='medium-input-3' type='text' name='cheque_no' value='$cheque_no' readonly><img id='check_new' class='check' src='images/check.jpg'></td>";
//                        echo "<td>&nbsp;&nbsp;&nbsp;Recent Name Appear:</td>";
//                        echo "<td><input type='text' class='medium-input-3' id='cheque_name_old' name='cheque_name_old' value='' readonly></td>";
//                        echo "</tr>";
//                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
//                        $rs_sig = mysql_fetch_array($sql_sig);
//                        echo "<tr height='30'>";
//                        echo "<td>AP:</td>";
//                        echo "<td><input type='hidden' name='user_id' value='" . $_SESSION['user_id'] . "'>"
//                        . "<input type='text' class='medium-input-3' name='user_id' value='" . strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)) . "' readonly></td>";
//                        echo "<td>&nbsp;&nbsp;&nbsp;Name Appear: </td>";
//                        echo "<td>";
//                        echo "<select id='cheque_name' class='medium-select-2' name='cheque_name' required>";
//                        echo "</select>";
//                        echo "</td>";
//
//                        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
//                        $rs_sig = mysql_fetch_array($sql_sig);
//                        echo "<tr>";
//                        echo "<td>Verifier: </td>";
//                        echo "<td><input id='verifier' class='medium-input-3' type='text name='verifier' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly></td>";
//                        echo "<td>&nbsp;&nbsp;&nbsp;If New</td>";
//                        echo "<td><input id='cheque_name_new' class='medium-select-2' type='text' name='cheque_name_new' value=''></td>";
//                        echo "</tr>";
//                        echo "<tr>";
//                        echo "<td>Signatory: </td>";
//                        echo "<td><input id='signatory' class='medium-input-3' type='text name='signatory' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly></td>";
//                        echo "<td>&nbsp;&nbsp;&nbsp;Cheque Date:</td>";
//                        echo "<td><input class='tcal' id='cheque_date' type='text' name='cheque_date' value='' readonly></td>";
//                        echo "</tr>";
                        echo "<tr>";
                        echo "<td>New Cheque: </td>";
                        echo "<td><input id='new_cheque' type='checkbox'></td>";
                        echo "<td></td>";
                        echo "<td></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='4'>
                        &nbsp;
                        &nbsp;
                        &nbsp;
                        </td>";
                        echo "<table>";
                        echo "<tr>";
                        echo "<td>";
                        if (!empty($que[1])) {
                            echo "<input id='c_1om' type='hidden' name='c_1om' value='1'><button id='print_details' class='large-submit' onclick='print_details();'>Details</button> ";
                        } else {
                            echo "<input id='c_1om' type='hidden' name='c_1om' value='0'> ";
                        }
                        echo "<button id='print_voucher' class='large-submit' onclick='print_voucher();'>Voucher</button> ";
                        echo "<button id='print_cheque' class='large-submit' onclick='print_cheque();'>Cheque</button> ";
                        echo "<div id='finish'></div></td>";
                        echo "</tr>";
                        echo "<tr>";
                        echo "<td colspan='4'><div id='msg'></div><div id='err'></div></td>";
                        echo "</tr>";
                        echo "</table>";
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                        ?>
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