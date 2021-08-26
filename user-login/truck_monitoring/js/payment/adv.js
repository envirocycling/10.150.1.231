function reload() {
    location.reload();
}

$(document).ready(function () {
    $("#cheque_name").select2();
    $("#cheque_no").select2();
    if ($.acpty === '1') {
        $("#cheque_name").prop('readonly', 'true');
        $("#bank_code").prop('readonly', 'true');
        $("#cheque_date").prop('readonly', 'true');
    }

    $("#print_voucher").hide();
    $("#print_cheque").hide();
    $("#finish").hide();

    $.ajax({
        type: "POST",
        url: "clear_temp.php",
        data: {type: 'clear'}
    });

    if ($("#old_bank_code").val() !== undefined) {
        $("#new_cheque").click(function () {
            var check = $('#new_cheque').is(":checked");
            if (check === true) {
                $("#old_bank_code").attr('disabled', true);
                $("#old_cheque_no").attr('disabled', true);
                $("#old_voucher_no").attr('disabled', true);
                $("#bank_code").attr('disabled', false);
                $("#cheque_no").attr('disabled', false);
                $("#voucher_no").attr('disabled', false);

                change($("#bank_code").val());
            } else {
                $("#old_bank_code").attr('disabled', false);
                $("#old_cheque_no").attr('disabled', false);
                $("#old_voucher_no").attr('disabled', false);
                $("#bank_code").attr('disabled', true);
                $("#cheque_no").attr('disabled', true);
                $("#voucher_no").attr('disabled', true);
            }
        });
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

function save() {

    if ($("#old_bank_code").val() !== undefined) {
        var check = $('#new_cheque').is(":checked");
        if (check === true) {
            var bank_code = $("#bank_code").val();
            var cheque_no = $("#cheque_no").val();
            var old_cheque_no = $("#old_cheque_no").val();
            var voucher_no = $("#voucher_no").val();
        } else {
            var bank_code = $("#old_bank_code").val();
            var cheque_no = $("#old_cheque_no").val();
            var old_cheque_no = $("#old_cheque_no").val();
            var voucher_no = $("#old_voucher_no").val();
        }
    } else {
        var bank_code = $("#bank_code").val();
        var cheque_no = $("#cheque_no").val();
        var old_cheque_no = '';
        var voucher_no = $("#voucher_no").val();
    }

    var cheque_date = $("#cheque_date").val();
    cheque_date = cheque_date.replace("-", "/");
    cheque_date = cheque_date.replace("-", "/");
    var name = $("#cheque_name").val();
    var name_new = $("#cheque_name_new").val();

    if (name_new !== '') {
        var cheque_name = escape(name_new);
    } else {
        var cheque_name = escape(name);
    }

    var grand_total = $("#amount").val();
    var type = "supplier";


    var particular = "ADVANCES TO SUPPLIER";
    var quantity = $("#amount").val();
    var unit_price = "1";
    var amount = $("#amount").val();

    if (cheque_name === '') {
        alert('Please Input Name Appear on Cheque.');
    } else if (bank_code === '') {
        alert('Please Choose Account.');
    } else if (cheque_no === '') {
        alert('Please Input Cheque Number.');
    } else if (cheque_no === 'Range Error') {
        alert('Please Input New Cheque Range.');
    } else {
        var dataString = 'bank_code=' + bank_code + '&old_cheque_no=' + old_cheque_no + '&cheque_no=' + cheque_no + '&cheque_date=' + cheque_date + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&grand_total=' + grand_total + '&type=' + type
                + '&user_id=' + $.user_id + '&verifier=' + $.verifier + '&signatory=' + $.signatory
                + '&others_id=' + $.others_id + '&particular=' + particular + '&quantity=' + quantity + '&unit_price=' + unit_price + '&amount=' + amount;
        $.ajax({
            type: "POST",
            url: "exec/adv_exec.php?payment=submitInitial",
            data: dataString
        }).done(function (msg) {
            $("#err").html(msg);
            if (msg === '') {
                var supplier_id = $("#supplier_id").val();
                var payment_id = $("#payment_id").val();
                var dataString = 'supplier_id=' + supplier_id;
                var url = '';
                var updateCheck = '0';
                if ($("#new_cheque") !== undefined) {
                    var check = $('#new_cheque').is(":checked");
                    if (check === true) {
                        updateCheck = '1';
                    }
                    $("#new_cheque").prop("disabled", true);
                }

                if (payment_id !== '') {
                    url = "exec/adv_exec.php?payment=submitFinalEdit&ac_id=" + $.ac_id;
                    var dataString = 'supplier_id=' + supplier_id + '&payment_id=' + payment_id;
                } else {
                    url = "exec/adv_exec.php?payment=submitFinal&ac_id=" + $.ac_id;
                    var dataString = 'supplier_id=' + supplier_id;
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: dataString
                }).done(function (msg) {
                    $("#err").html(msg);
                    if (payment_id === '' || updateCheck === '1') {
                        $.ajax({
                            url: "exec/adv_exec.php?payment=getIdSaved"
                        }).done(function (id) {
                            $("#payment_id").val(id);
                        });
                    }
                });
                var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
                $("select").prop("disabled", true);
                $("input").prop("readonly", true);
                $("#save").prop("disabled", true);
                $("#print_voucher").show();
                $("#print_cheque").show();
                $("#finish").show();
                $("#msg").html(msg);
            }
        }).fail(function (msg) {
            $("#err").html(msg);
        });

    }
}

function print_voucher() {
    var payment_id = $("#payment_id").val();
    window.open("print_voucher_others.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
}

function print_cheque() {
    var payment_id = $("#payment_id").val();
    window.open("print_cheque.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
}

function change(val) {
    $("#cheque_no").empty().trigger('change');
    $.ajax({
        type: "POST",
        url: "exec/checkSeries.php?type=cheque",
        data: {bank_code: val}
    }).done(function (a) {
        if ((a === 'Used' || a === 'Range Error') && val !== '') {
            $("button").prop("disabled", true);
            var msg = '';
            if (a === 'Range Error') {
                msg = "<br><a href='check_range.php'>Click here</a> to add new cheque range.";
            } else {
                msg = "<br>Other user is using this account, Please try again later.";
            }
            var msg = "<font color='red'>Bank Account Status: " + a + " " + msg + "</font>";

            $("#msg").html(msg);
        } else {
            var data = $.parseJSON(a);
            $("#cheque_no").select2({data: data});
            $("button").prop("disabled", false);
            voucherNo(val);
            $("#msg").html();

        }
    });
}

function voucherNo(val) {
    $.ajax({
        type: "POST",
        url: "exec/checkSeries.php?type=voucher",
        data: {bank_code: val}
    }).done(function (a) {
        $("#voucher_no").val(a);
    });
}