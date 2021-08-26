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
        location.replace('initial_settings.php');
    </script>";
}
if (!isset($_SESSION['signatory'])) {
    echo "<script>
    alert('Signatory is not set Please go to settings and update the setup.');
    location.replace('initial_settings.php');
    </script>";
}

$sql_payment = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
$rs_payment = mysql_fetch_array($sql_payment);

$sql_che = mysql_query("SELECT max(cheque_no) FROM payment WHERE bank_code='" . $rs_payment['bank_code'] . "' and cheque_status!='issued' and status!='deleted'");
$rs_che = mysql_fetch_array($sql_che);
$sql_c_r = mysql_query("SELECT * FROM cheque_range WHERE bank_code='" . $rs_payment['bank_code'] . "' and status=''");
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
$sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE bank_code='" . $rs_payment['bank_code'] . "' and date='$date' and status!='deleted'");
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
            $(document).ready(function () {
                $('#payee').select2();
                var r = '<?php echo $row_count; ?>';
                var c = Number(r) + 1;
                while (c <= 20) {
                    $('#row_' + c).hide();
                    c++;
                }

                $("#plus").click(function () {
                    compute();
                    var row_count = $('#row_show').val();
                    if (row_count < 20) {
                        row_count++;
                        $('#row_' + row_count).show();
                        $('#row_show').val(row_count);
//                        var w = Number(row_count - 1) * 50;
//                        var w = w + 800;
//                        $("#pending").attr("height", w);
                    } else {
                        alert('Limit is 20 items only.');
                    }
                });
                $("#minus").click(function () {
                    var row_count = $('#row_show').val();
                    if (row_count > 1) {
                        $('#particular_' + row_count).val('');
                        $('#quantity_' + row_count).val('');
                        $('#unit_price_' + row_count).val('');
                        $('#amount_' + row_count).val('');
                        $('#row_' + row_count).hide();
                        row_count--;
                        $('#row_show').val(row_count);
//                        var w = Number(row_count - 1) * 50;
//                        var w = w + 800;
//                        $("#pending").attr("height", w);
                    }
                    compute();
                });
                var c = '<?php echo $rs_tpc; ?>';
                if (c >= 1) {
                    document.getElementById("print_voucher").disabled = true;
                    document.getElementById("print_cheque").disabled = true;
                    var finish = "<a href=''><button class='large-submit'>Refresh</button></a>";
                    var msg = "<font color='red'>Other users is using this Bank Account, Please refresh this page and try again.</font>";
                    document.getElementById("finish").innerHTML = finish;
                    document.getElementById("msg").innerHTML = msg;
                }

                //new cheque

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

//                var date_now = "<?php // echo date("Y/m/d"); ?>";
//                var date_plus8d = "<?php // echo date("Y/m/d", strtotime("+8 days", strtotime($rs_payment['date']))); ?>";
//                if (date_now > date_plus8d) {
//                    $("input").prop("readonly", true);
//                    $("textarea").prop("disabled", true);
//                    $(".submit").prop("disabled", true);
//                    $("select").prop("disabled", true);
//                    $("button").prop("disabled", true);
//                    $("#err").html("<font color='red'>You can't edit this transaction.</font>");
//                }
            });

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

                var cheque_date2 = $("#cheque_date2").val();
                var payee = $("#payee_new").val();
                if (payee == '') {
                    var cheque_name = escape($("#payee").val());
                } else {
                    var cheque_name = escape($("#payee_new").val());
                }

                var description = $("#description").val();
                var grand_total = $("#grand_total").val();
                var type = $("#type").val();
                var user_id = $("#user_id").val();
                var verifier = $("#verifier").val();
                var signatory = $("#signatory").val();
                var others_id_1 = $("#others_id_1").val();
                var others_id_2 = $("#others_id_2").val();
                var others_id_3 = $("#others_id_3").val();
                var others_id_4 = $("#others_id_4").val();
                var others_id_5 = $("#others_id_5").val();
                var others_id_6 = $("#others_id_6").val();
                var others_id_7 = $("#others_id_7").val();
                var others_id_8 = $("#others_id_8").val();
                var others_id_9 = $("#others_id_9").val();
                var others_id_10 = $("#others_id_10").val();
                var others_id_11 = $("#others_id_11").val();
                var others_id_12 = $("#others_id_12").val();
                var others_id_13 = $("#others_id_13").val();
                var others_id_14 = $("#others_id_14").val();
                var others_id_15 = $("#others_id_15").val();
                var others_id_16 = $("#others_id_16").val();
                var others_id_17 = $("#others_id_17").val();
                var others_id_18 = $("#others_id_18").val();
                var others_id_19 = $("#others_id_19").val();
                var others_id_20 = $("#others_id_20").val();
                var particular_1 = $("#particular_1").val();
                var particular_2 = $("#particular_2").val();
                var particular_3 = $("#particular_3").val();
                var particular_4 = $("#particular_4").val();
                var particular_5 = $("#particular_5").val();
                var particular_6 = $("#particular_6").val();
                var particular_7 = $("#particular_7").val();
                var particular_8 = $("#particular_8").val();
                var particular_9 = $("#particular_9").val();
                var particular_10 = $("#particular_10").val();
                var particular_11 = $("#particular_11").val();
                var particular_12 = $("#particular_12").val();
                var particular_13 = $("#particular_13").val();
                var particular_14 = $("#particular_14").val();
                var particular_15 = $("#particular_15").val();
                var particular_16 = $("#particular_16").val();
                var particular_17 = $("#particular_17").val();
                var particular_18 = $("#particular_18").val();
                var particular_19 = $("#particular_19").val();
                var particular_20 = $("#particular_20").val();

                var description_1 = escape($("#description_1").val());
                var description_2 = escape($("#description_2").val());
                var description_3 = escape($("#description_3").val());
                var description_4 = escape($("#description_4").val());
                var description_5 = escape($("#description_5").val());
                var description_6 = escape($("#description_6").val());
                var description_7 = escape($("#description_7").val());
                var description_8 = escape($("#description_8").val());
                var description_9 = escape($("#description_9").val());
                var description_10 = escape($("#description_10").val());
                var description_11 = escape($("#description_11").val());
                var description_12 = escape($("#description_12").val());
                var description_13 = escape($("#description_13").val());
                var description_14 = escape($("#description_14").val());
                var description_15 = escape($("#description_15").val());
                var description_16 = escape($("#description_16").val());
                var description_17 = escape($("#description_17").val());
                var description_18 = escape($("#description_18").val());
                var description_19 = escape($("#description_19").val());
                var description_20 = escape($("#description_20").val());

                var quantity_1 = $("#quantity_1").val();
                var quantity_2 = $("#quantity_2").val();
                var quantity_3 = $("#quantity_3").val();
                var quantity_4 = $("#quantity_4").val();
                var quantity_5 = $("#quantity_5").val();
                var quantity_6 = $("#quantity_6").val();
                var quantity_7 = $("#quantity_7").val();
                var quantity_8 = $("#quantity_8").val();
                var quantity_9 = $("#quantity_9").val();
                var quantity_10 = $("#quantity_10").val();
                var quantity_11 = $("#quantity_11").val();
                var quantity_12 = $("#quantity_12").val();
                var quantity_13 = $("#quantity_13").val();
                var quantity_14 = $("#quantity_14").val();
                var quantity_15 = $("#quantity_15").val();
                var quantity_16 = $("#quantity_16").val();
                var quantity_17 = $("#quantity_17").val();
                var quantity_18 = $("#quantity_18").val();
                var quantity_19 = $("#quantity_19").val();
                var quantity_20 = $("#quantity_20").val();
                var unit_price_1 = $("#unit_price_1").val();
                var unit_price_2 = $("#unit_price_2").val();
                var unit_price_3 = $("#unit_price_3").val();
                var unit_price_4 = $("#unit_price_4").val();
                var unit_price_5 = $("#unit_price_5").val();
                var unit_price_6 = $("#unit_price_6").val();
                var unit_price_7 = $("#unit_price_7").val();
                var unit_price_8 = $("#unit_price_8").val();
                var unit_price_9 = $("#unit_price_9").val();
                var unit_price_10 = $("#unit_price_10").val();
                var unit_price_11 = $("#unit_price_11").val();
                var unit_price_12 = $("#unit_price_12").val();
                var unit_price_13 = $("#unit_price_13").val();
                var unit_price_14 = $("#unit_price_14").val();
                var unit_price_15 = $("#unit_price_15").val();
                var unit_price_16 = $("#unit_price_16").val();
                var unit_price_17 = $("#unit_price_17").val();
                var unit_price_18 = $("#unit_price_18").val();
                var unit_price_19 = $("#unit_price_19").val();
                var unit_price_20 = $("#unit_price_20").val();
                var amount_1 = $("#amount_1").val();
                var amount_2 = $("#amount_2").val();
                var amount_3 = $("#amount_3").val();
                var amount_4 = $("#amount_4").val();
                var amount_5 = $("#amount_5").val();
                var amount_6 = $("#amount_6").val();
                var amount_7 = $("#amount_7").val();
                var amount_8 = $("#amount_8").val();
                var amount_9 = $("#amount_9").val();
                var amount_10 = $("#amount_10").val();
                var amount_11 = $("#amount_11").val();
                var amount_12 = $("#amount_12").val();
                var amount_13 = $("#amount_13").val();
                var amount_14 = $("#amount_14").val();
                var amount_15 = $("#amount_15").val();
                var amount_16 = $("#amount_16").val();
                var amount_17 = $("#amount_17").val();
                var amount_18 = $("#amount_18").val();
                var amount_19 = $("#amount_19").val();
                var amount_20 = $("#amount_20").val();
                var click = $("#click").val();
                if (cheque_name == '') {
                    alert('Please Input Name Appear on Cheque.');
                } else if (account == '') {
                    alert('Please Choose Account.');
                } else if (cheque_no == '') {
                    alert('Please Input Cheque Number.');
                } else {
                    var dataString = 'account=' + account + '&cheque_no=' + cheque_no + '&voucher_no=' + voucher_no + '&cheque_date=' + cheque_date2 + '&cheque_name=' + cheque_name + '&grand_total=' + grand_total + '&old_cheque_no=' + old_cheque_no + '&type=' + type + '&description=' + description
                            + '&user_id=' + user_id + '&verifier=' + verifier + '&signatory=' + signatory
                            + '&particular_1=' + particular_1 + '&description_1=' + description_1 + '&quantity_1=' + quantity_1 + '&unit_price_1=' + unit_price_1 + '&amount_1=' + amount_1
                            + '&particular_2=' + particular_2 + '&description_2=' + description_2 + '&quantity_2=' + quantity_2 + '&unit_price_2=' + unit_price_2 + '&amount_2=' + amount_2
                            + '&particular_3=' + particular_3 + '&description_3=' + description_3 + '&quantity_3=' + quantity_3 + '&unit_price_3=' + unit_price_3 + '&amount_3=' + amount_3
                            + '&particular_4=' + particular_4 + '&description_4=' + description_4 + '&quantity_4=' + quantity_4 + '&unit_price_4=' + unit_price_4 + '&amount_4=' + amount_4
                            + '&particular_5=' + particular_5 + '&description_5=' + description_5 + '&quantity_5=' + quantity_5 + '&unit_price_5=' + unit_price_5 + '&amount_5=' + amount_5
                            + '&particular_6=' + particular_6 + '&description_6=' + description_6 + '&quantity_6=' + quantity_6 + '&unit_price_6=' + unit_price_6 + '&amount_6=' + amount_6
                            + '&particular_7=' + particular_7 + '&description_7=' + description_7 + '&quantity_7=' + quantity_7 + '&unit_price_7=' + unit_price_7 + '&amount_7=' + amount_7
                            + '&particular_8=' + particular_8 + '&description_8=' + description_8 + '&quantity_8=' + quantity_8 + '&unit_price_8=' + unit_price_8 + '&amount_8=' + amount_8
                            + '&particular_9=' + particular_9 + '&description_9=' + description_9 + '&quantity_9=' + quantity_9 + '&unit_price_9=' + unit_price_9 + '&amount_9=' + amount_9
                            + '&particular_10=' + particular_10 + '&description_10=' + description_10 + '&quantity_10=' + quantity_10 + '&unit_price_10=' + unit_price_10 + '&amount_10=' + amount_10
                            + '&particular_11=' + particular_11 + '&description_11=' + description_11 + '&quantity_11=' + quantity_11 + '&unit_price_11=' + unit_price_11 + '&amount_11=' + amount_11
                            + '&particular_12=' + particular_12 + '&description_12=' + description_12 + '&quantity_12=' + quantity_12 + '&unit_price_12=' + unit_price_12 + '&amount_12=' + amount_12
                            + '&particular_13=' + particular_13 + '&description_13=' + description_13 + '&quantity_13=' + quantity_13 + '&unit_price_13=' + unit_price_13 + '&amount_13=' + amount_13
                            + '&particular_14=' + particular_14 + '&description_14=' + description_14 + '&quantity_14=' + quantity_14 + '&unit_price_14=' + unit_price_14 + '&amount_14=' + amount_14
                            + '&particular_15=' + particular_15 + '&description_15=' + description_15 + '&quantity_15=' + quantity_15 + '&unit_price_15=' + unit_price_15 + '&amount_15=' + amount_15
                            + '&particular_16=' + particular_16 + '&description_16=' + description_16 + '&quantity_16=' + quantity_16 + '&unit_price_16=' + unit_price_16 + '&amount_16=' + amount_16
                            + '&particular_17=' + particular_17 + '&description_17=' + description_17 + '&quantity_17=' + quantity_17 + '&unit_price_17=' + unit_price_17 + '&amount_17=' + amount_17
                            + '&particular_18=' + particular_18 + '&description_18=' + description_18 + '&quantity_18=' + quantity_18 + '&unit_price_18=' + unit_price_18 + '&amount_18=' + amount_18
                            + '&particular_19=' + particular_19 + '&description_19=' + description_19 + '&quantity_19=' + quantity_19 + '&unit_price_19=' + unit_price_19 + '&amount_19=' + amount_19
                            + '&particular_20=' + particular_20 + '&description_20=' + description_20 + '&quantity_20=' + quantity_20 + '&unit_price_20=' + unit_price_20 + '&amount_20=' + amount_20
                            + '&others_id_1=' + others_id_1
                            + '&others_id_2=' + others_id_2
                            + '&others_id_3=' + others_id_3
                            + '&others_id_4=' + others_id_4
                            + '&others_id_5=' + others_id_5
                            + '&others_id_6=' + others_id_6
                            + '&others_id_7=' + others_id_7
                            + '&others_id_8=' + others_id_8
                            + '&others_id_9=' + others_id_9
                            + '&others_id_10=' + others_id_10
                            + '&others_id_11=' + others_id_11
                            + '&others_id_12=' + others_id_12
                            + '&others_id_13=' + others_id_13
                            + '&others_id_14=' + others_id_14
                            + '&others_id_15=' + others_id_15
                            + '&others_id_16=' + others_id_16
                            + '&others_id_17=' + others_id_17
                            + '&others_id_18=' + others_id_18
                            + '&others_id_19=' + others_id_19
                            + '&others_id_20=' + others_id_20;
                    $.ajax({
                        type: "POST",
                        url: "exec/othPay.php?payment=submitInitialEdit",
                        data: dataString,
                        cache: false
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    document.getElementById("checker").value = 1;
                    if (click == 1) {
                        window.open("print_voucher_others.php", 'mywindow', 'width=1200,height=600,left=50,top=50');
                    }
                    document.getElementById("click").value = 1;
                }
            }
            function print_cheque() {
                var checker = $("#checker").val();
                var payment_id = $("#payment_id").val();
                if (checker == 0) {
                    alert('Please Print the voucher first.');
                } else {
                    $("input").prop('readonly', true);
                    $("#payee_new").prop('readonly', false);
                    var dataString = 'checker=' + checker + '&payment_id=' + payment_id;
                    $.ajax({
                        type: "POST",
                        url: "exec/othPay.php?payment=submitFinalEdit",
                        data: dataString,
                        cache: false
                    }).done(function (msg) {
                        $("#err").html(msg);
                    });
                    var finish = "<a href='clear_temp.php'><button class='large-submit'>Finish</button></a>";
                    var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
                    document.getElementById("finish").innerHTML = finish;
                    document.getElementById("msg").innerHTML = msg;
                    window.open("print_cheque.php", 'mywindow', 'width=1200,height=600,left=50,top=50');
                }
            }
            function compute() {
                var c = 1;
                var grand_total = 0;
                while (c <= 20) {
                    var quantity = $("#quantity_" + c).val();
                    var unit_price = $("#unit_price_" + c).val();
                    var amount = Number(quantity * unit_price);
                    grand_total += amount;
                    document.getElementById("amount_" + c).value = amount;
                    c++;
                }
                document.getElementById("grand_total").value = grand_total;
            }
            function change(val) {

                var bank_code = '<?php echo $rs_payment['bank_code']; ?>';
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
    $rs_c_r = mysql_fetch_array($sql_c_r);
    ?>
                    if (val == '<?php echo $rs_b['bank_code']; ?>') {
                        var value = '<?php
    if ($rs_che['max(cheque_no)'] < $rs_c_r['from']) {
        echo sprintf("%010s", $rs_c_r['from']);
    } else if ($rs_che['max(cheque_no)'] > $rs_c_r['to']) {
        echo "Range Exceed";
    } else {
        echo sprintf("%010s", $rs_che['max(cheque_no)'] + 1);
    }
    ?>';
    <?php
    $voucher_date = date("md");
    $date = date("Y/m/d");
    $sql_voucher = mysql_query("SELECT count(voucher_no),max(voucher_no) FROM payment WHERE bank_code='" . $rs_b['bank_code'] . "' and date='$date' and status!='deleted'");
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
                                        document.getElementById("print_voucher").disabled = true;
                                        document.getElementById("print_cheque").disabled = true;
                                        var finish = "<a href=''><button class='large-submit'>Refresh</button></a>";
                                        var msg = "<font color='red'>Other users is using this Bank Account, Please refresh this page and try again.</font>";
                                        document.getElementById("finish").innerHTML = finish;
                                        document.getElementById("msg").innerHTML = msg;
                                    } else {
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

                        function AllowAlphabet(e) {
                            isIE = document.all ? 1 : 0
                            keyEntry = !isIE ? e.which : event.keyCode;
                            if (((keyEntry >= '65') && (keyEntry <= '90')) || ((keyEntry >= '97') && (keyEntry <= '122')) || (keyEntry == '46') || (keyEntry == '32') || keyEntry == '45')
                                return true;
                            else {
                                return false;
                            }
                        }

                        function isNumber(evt) {
                            evt = (evt) ? evt : window.event;
                            var charCode = (evt.which) ? evt.which : evt.keyCode;
                            if (charCode == 45 || charCode == 46 || (charCode > 47 && charCode < 58)) {
                                return true;
                            }
                            return false;
                        }
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
                <input type='hidden' id='payment_id' name='payment_id' value='<?php echo $rs_payment['payment_id']; ?>'>
                <table width='1000' border='0'>
                    <tr class='head'>
                        <td><div style="width: 125px;">Payee Name: </div></td>
                        <td colspan="3"><select id="payee" class="medium-input-4" name="payee"> 
                                <option value="<?php echo $rs_payment['cheque_name']; ?>"><?php echo $rs_payment['cheque_name']; ?></option>
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
                    <tr>
                        <td>Account.: </td>
                        <td><input id='old_account' class='medium-input-3' type="text" name="old_account" value="<?php echo $rs_payment['bank_code']; ?>" readonly></td>
                        <td>Cheque Date: </td>
                        <td><?php echo '<input type="text" class="tcal" id="cheque_date2" value="' . date('Y/m/d') . '" size="10" required>'; ?></td>
                    </tr>
                    <tr>
                        <td>Voucher No.: </td>
                        <td><input id='old_voucher_no' class='medium-input-3' type='text' name='old_voucher_no' value='<?php echo $rs_payment['voucher_no']; ?>' readonly></td>
                        <td>Type: </td>
                        <td><select id="type" class="tcal" name="type">
                                <?php
                                if ($rs_payment['type'] == 'others') {
                                    echo "<option value='" . $rs_payment['type'] . "'>Non Trade</option>";
                                } else {
                                    echo "<option value='" . $rs_payment['type'] . "'>Trade</option>";
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
                            echo "<select id='account' class='medium-input-3' name='account' onchange='change(this.value);' disabled>";
                            $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code='" . $rs_payment['bank_code'] . "'");
                            $rs_bank = mysql_fetch_array($sql_bank);
                            echo "<option value='" . $rs_payment['bank_code'] . "'>" . $rs_payment['bank_code'] . " - " . $rs_bank['location'] . "</option>";
                            $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code!='" . $rs_payment['bank_code'] . "'");
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
                        <td><input id='voucher_no' class='medium-input-3' type='text' name='voucher_no' value='<?php echo $voucher_date . "-" . $voucher_numberq; ?>'  disabled readonly></td>
                        <td>Verifier: </td>
                        <td>
                            <?php
                            $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['verifier'] . "'");
                            $rs_sig = mysql_fetch_array($sql_sig);
                            echo "<input id='verifier' class='medium-input-3' type='text name='verifier' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly>";
                            ?>
                    </tr>
                    <tr>
                        <td>New Cheque No.: </td>
                        <td><input id='cheque_no' class='medium-input-3' type='text' name='cheque_no' value='<?php echo $cheque_no; ?>' disabled readonly></td>
                        <td>Signatory</td>
                        <td>
                            <?php
                            $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['signatory'] . "'");
                            $rs_sig = mysql_fetch_array($sql_sig);
                            echo "<input id='signatory' class='medium-input-3' type='text name='signatory' value='" . strtoupper($rs_sig['initial']) . "" . strtolower(substr($rs_sig['lastname'], 1)) . "' readonly>";
                            ?>
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
                        <td colspan="2"><button id="print_voucher" class='large-submit' onClick="print_voucher();">Voucher</button>&nbsp;
                            <button id="print_cheque" class='large-submit' onClick="print_cheque();">Cheque</button>&nbsp;
                            <div id="finish"></div>&nbsp;
                    </tr>
                    <tr>
                        <td colspan='3'>
                            <div id='msg'></div>
                            <div id="err"></div>
                        </td>
                    </tr>
                </table>

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