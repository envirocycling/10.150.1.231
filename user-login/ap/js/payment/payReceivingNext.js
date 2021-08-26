$(document).ready(function () {
    $("#print_voucher_digi").hide();
    $("#print_voucher").hide();
    $("#print_cheque").hide();
    $("#finish").hide();

    $("#cheque_name").select2();
    $("#cheque_no").select2();

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
                $("#cheque_date").val($.date_now);
                change($("#bank_code").val());
            } else {
                $("#old_bank_code").attr('disabled', false);
                $("#old_cheque_no").attr('disabled', false);
                $("#old_voucher_no").attr('disabled', false);
                $("#bank_code").attr('disabled', true);
                $("#cheque_no").attr('disabled', true);
                $("#voucher_no").attr('disabled', true);
                $("#cheque_date").val($.cheque_date);
            }
        });
    }
});


function utf8_encode(argString) {

    if (argString === null || typeof argString === 'undefined') {
        return '';
    }

    var string = (argString + ''); // .replace(/\r\n/g, "\n").replace(/\r/g, "\n");
    var utftext = '',
            start, end, stringl = 0;
    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;
        if (c1 < 128) {
            end++;
        } else if (c1 > 127 && c1 < 2048) {
            enc = String.fromCharCode(
                    (c1 >> 6) | 192, (c1 & 63) | 128
                    );
        } else if ((c1 & 0xF800) != 0xD800) {
            enc = String.fromCharCode(
                    (c1 >> 12) | 224, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
                    );
        } else { // surrogate pairs
            if ((c1 & 0xFC00) != 0xD800) {
                throw new RangeError('Unmatched trail surrogate at ' + n);
            }
            var c2 = string.charCodeAt(++n);
            if ((c2 & 0xFC00) != 0xDC00) {
                throw new RangeError('Unmatched lead surrogate at ' + (n - 1));
            }
            c1 = ((c1 & 0x3FF) << 10) + (c2 & 0x3FF) + 0x10000;
            enc = String.fromCharCode(
                    (c1 >> 18) | 240, ((c1 >> 12) & 63) | 128, ((c1 >> 6) & 63) | 128, (c1 & 63) | 128
                    );
        }
        if (enc !== null) {
            if (end > start) {
                utftext += string.slice(start, end);
            }
            utftext += enc;
            start = end = n + 1;
        }
    }

    if (end > start) {
        utftext += string.slice(start, stringl);
    }

    return utftext;
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
        if (bank_code === 'SBC') {
            var cheque_no = 'SBC - Digibanker';
        } else {
            var cheque_no = $("#cheque_no").val();
        }
        var old_cheque_no = '';
        var voucher_no = $("#voucher_no").val();
    }

    var cheque_date = $("#cheque_date").val();

    var needtosplit = $("#cheque_name").val();

    var datasplited = needtosplit.split("_");

    var name = datasplited[0];
    var name_new = $("#cheque_name_new").val();
    if (name_new !== '') {
        var cheque_name = escape(utf8_encode(name_new));

        if ($("#account_number_new").val() !== undefined) {
            var account_number = $("#account_number_new").val();
        } else {
            var account_number = '';
        }
    } else {
        var cheque_name = escape(utf8_encode(name));

        if ($("#account_number").val() !== undefined) {
            var account_number = $("#account_number").val();
        } else {
            var account_number = '';
        }
    }
    var trans_array = $("#trans_array").val();
    var cheque_date = $("#cheque_date").val();

    if (bank_code === '') {
        alert('Please Choose Account.');
    } else if (cheque_no === '' || cheque_no === null) {
        alert('Please Select Cheque Number.');
    } else if (cheque_no === 'Range Error') {
        alert('Please Input New Cheque Range.');
    } else if (name === '' && name_new === '') {
        alert('Please Input Name Appear on Cheque.');
    } else {

        var updateCheck = '0';

        if ($("#new_cheque") !== undefined) {
            var check = $('#new_cheque').is(":checked");
            if (check === true) {
                updateCheck = '1';
            }
            $("#new_cheque").prop("disabled", true);
        }

        var dataString = 'cheque_no=' + cheque_no + '&old_cheque_no=' + old_cheque_no + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&bank_code=' + bank_code + '&account_number=' + account_number + '&cheque_date=' + cheque_date;
        $.ajax({
            type: "POST",
            url: "exec/recPay.php?payment=submitFinal",
            data: dataString
        }).done(function (e) {
            $("#err").html(e);
            if (e === '') {
                var url = '';
                var payment_id = $("#payment_id").val();
                if (payment_id !== '') {
                    if (updateCheck === '0') {
                        url = "exec/recPay.php?payment=submitFinalEditUpdate&payment_id=" + payment_id;
                    } else {
                        url = "exec/recPay.php?payment=submitFinalEditSave&payment_id=" + payment_id;
                    }
                } else {
                    url = "exec/recPay.php?payment=submitFinalSave";
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: {cheque_date: cheque_date, trans_array: trans_array}
                }).done(function (e) {
                    $("#err").html(e);

                    if (payment_id === '' || updateCheck === '1') {
                        $.ajax({
                            url: "exec/recPay.php?payment=getIdSaved"
                        }).done(function (id) {
                            $("#payment_id").val(id);
                        });
                    }

                });


                var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
                $("input").prop("readonly", true);
                $("input").prop("readonly", true);
                $("select").prop("disabled", true);
                $("#save").prop("disabled", true);
                $("#print_voucher_digi").show();
                $("#print_voucher").show();
                $("#print_cheque").show();
                $("#finish").show();
                $("#msg").html(msg);
            }
        });


    }
}

function print_voucher(type) {
    var payment_id = $("#payment_id").val();
    var trans_array = $("#trans_array").val();

    if (type === 'print_voucher') {
        window.open("print_voucher.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
    } else if (type === 'print_voucher_digi') {
        window.open("print_po_digibanker.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
    } else {
        var needtosplit = $("#cheque_name").val();
        var datasplited = needtosplit.split("_");
        var name = datasplited[0];
        var name_new = $("#cheque_name_new").val();
        if (name_new !== '') {
            var cheque_name = escape(utf8_encode(name_new));
        } else {
            var cheque_name = escape(utf8_encode(name));
        }
        window.open("print_details.php?trans_id=" + trans_array + "&cheque_name=" + cheque_name, 'mywindow', 'width=1200,height=600,left=50,top=50');
    }
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

function show(val) {
    var splits = val.split("_");
    $("#account_number").val(splits[1]);
}