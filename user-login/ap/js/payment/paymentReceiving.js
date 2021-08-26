// form 1 functions
function advances(str) {
    var id_data = str.split("_");
    var dedType = id_data[0];
    var id = id_data[1];
    if (dedType === 'a' || dedType === 'd') {
        $('#' + id).each(function () {
            var ac_no = $(this).find("#ac_no").html();
            var voucher_no = $(this).find("#fdvoucher_no").html();
            var type = $(this).find("#type").html();
            var amount = $(this).find("#amount").html();
            var counter = 1;
            while (counter <= 5) {
                if ($("#adj_" + counter).val() === '') {
                    $("#adj_" + counter).html('');
                    if (dedType === 'a') {
                        $("#adj_" + counter).append(new Option('ADD', 'add'));
                        $("#ac_id_" + counter).val(id);
                        $("#adj_" + counter).val('add');
                        $("#desc_" + counter).val(type + ' (CV#' + ac_no + ')');
                        $("#amount_" + counter).val(amount).prop("readonly", true);
                        $("#limit_amount_" + counter).val(amount);
                        $("#" + str).prop('value', 'Remove')
                                .prop('id', 'r_' + id);
                    } else if (dedType === 'd') {
                        $("#adj_" + counter).append(new Option('DEDUCT', 'deduct'))
                                .val('deduct');
                        $("#ac_id_" + counter).val(id);
                        $("#desc_" + counter).val(type + ' (CV#' + voucher_no + ')');
                        $("#amount_" + counter).val(amount);
                        $("#limit_amount_" + counter).val(amount).prop("readonly", false);
                        $("#" + str).prop('value', 'Remove')
                                .prop('id', 'r_' + id);
                    }
                    adj();
                    return false;
                }
                counter++;
            }
        });
    } else {
        var counter = 1;
        while (counter <= 5) {
            if ($("#ac_id_" + counter).val() === id) {
                if ($("#adj_" + counter).val() === 'add') {
                    $("#" + str).prop('value', 'Add')
                            .prop('id', 'a_' + id);
                } else {
                    $("#" + str).prop('value', 'Deduct')
                            .prop('id', 'd_' + id);
                }

                $("#adj_" + counter).html('')
                        .append(new Option('', ''))
                        .append(new Option('ADD', 'add'))
                        .append(new Option('DEDUCT', 'deduct'))
                        .val('');
                $("#ac_id_" + counter).val('');
                $("#desc_" + counter).val('');
                $("#amount_" + counter).val('').prop("readonly", false);
                $("#limit_amount_" + counter).val('');
                adj();
                return false;
            }
            counter++;
        }
    }
}

function truckRental(str) {
    var id_data = str.split("_");
    var ded = id_data[0];
    var id = id_data[1];
    if (ded === 'd') {
        $("#" + id).each(function () {
            var tData = $(this).find("#data").html();
            var type = $(this).find("#type").html();
            var amount = $(this).find("#amount").html();
            var counter = 1;
            while (counter <= 5) {
                if ($("#adj_" + counter).val() === '') {
                    $("#adj_" + counter).html('');
                    $("#adj_" + counter).append(new Option('DEDUCT', 'deduct'))
                            .val('deduct');
                    $("#tp_id_" + counter).val(id);
                    $("#desc_" + counter).val(tData);
                    $("#amount_" + counter).val(amount).prop("readonly", false);
                    $("#limit_amount_" + counter).val(amount);
                    $("#d_" + id).prop('value', 'Remove')
                            .prop('id', 'r_' + id);
                    adj();
                    return false;
                }
                counter++;
            }
        });
    } else {
        var counter = 1;
        while (counter <= 5) {
            if ($("#tp_id_" + counter).val() === id) {
                if ($("#adj_" + counter).val() === 'add') {
                    $("#" + str).prop('value', 'Add')
                            .prop('id', 'a_' + id);
                } else {
                    $("#" + str).prop('value', 'Deduct')
                            .prop('id', 'd_' + id);
                }

                $("#adj_" + counter).html('')
                        .append(new Option('', ''))
                        .append(new Option('ADD', 'add'))
                        .append(new Option('DEDUCT', 'deduct'))
                        .val('');
                $("#tp_id_" + counter).val('');
                $("#desc_" + counter).val('');
                $("#amount_" + counter).val('').prop("readonly", false);
                $("#limit_amount_" + counter).val('');
                adj();
                return false;
            }
            counter++;
        }
    }

}

function adj() {
    var sub_total = $("#sub_total").val();
    var ts_fee = $("#ts_fee").val();
    var grand_total = Number(sub_total - ts_fee);
    var del_adj_co = $("#del_adj_co").val();
    var del_adj_counter = 1;
    var adjustments = 0;
    while (del_adj_counter < del_adj_co) {
        var adj_qty = $("#adj_qty" + del_adj_counter).val();
        var adj_price = $("#adj_price" + del_adj_counter).val();
        if (adj_price === '.' || adj_price === '-') {
            adj_price = 0;
        }
        var adj_amount = Number(adj_qty * adj_price);
        $("#adj_amount" + del_adj_counter).val(Math.round(adj_amount * 100) / 100);
        adjustments = adjustments + adj_amount;
        grand_total = Number(grand_total + adj_amount);
        $("#grand_total").val(Math.round(grand_total * 100) / 100);
        del_adj_counter++;
    }

    var counter = 1;
    while (counter <= 5) {
        var adj = $("#adj_" + counter).val();
        var amount = Number($("#amount_" + counter).val());
        if (amount === '.' || amount === '-' || amount === '0') {
            amount = 0;
        }
        if (adj === 'add') {
            adjustments = adjustments + amount;
            grand_total = Number(grand_total + amount);
            $("#grand_total").val(Math.round(grand_total * 100) / 100);
        }
        if (adj === 'deduct') {
            adjustments = adjustments - amount;
            grand_total = Number(grand_total - amount);
            $("#grand_total").val(Math.round(grand_total * 100) / 100);
        }
        counter++;
    }
    $("#adjustments").val(Math.round(adjustments * 100) / 100);
}

function checkLimit(i) {
    var amount = Number($("#amount_" + i).val());
    var limit = Number($("#limit_amount_" + i).val());
    if (limit !== 0) {
        if (amount > limit) {
            alert('The amount exceed to amount to be deducted.');
            $("#amount_" + i).val(limit);
        }
    }
    adj();
}

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




// end form 1 functions

// <<--------->>

//form 2 functions
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

function save() {
    var payment_id = $("[name=payment_id]").val(), needtosplit = "", bank_code = "", cheque_no = "", voucher_no = "", account_number = "", cancelled_cheque = "", charge_to = "", remarks = "", url = "";
    if (payment_id === "") {
        if ($("[name=operation]:eq(0)").is(':checked') === true) {
            needtosplit = $("#cheque_name").val();
            bank_code = $("#bank_code").val();
            cheque_no = $("#cheque_no").val();
            voucher_no = $("#voucher_no").val();
        } else if ($("[name=operation]:eq(1)").is(':checked') === true) {
            needtosplit = $("#account_name").val();
            bank_code = "SBC";
            cheque_no = "SBC - Digibanker";
            voucher_no = $("#digi_voucher_no").val();
        }
        account_number = $("#account_number").val();
        url = "exec/payment/paymentReceiving.php?payment=save";
    } else {
        needtosplit = $("#cheque_name").val();
        var check = $('#new_cheque').is(":checked");
        if (check === true) {
            bank_code = $("#bank_code").val();
            cheque_no = $("#cheque_no").val();
//            cancelled_cheque = $("#old_cheque_no").val();
            voucher_no = $("#voucher_no").val();
            url = "exec/payment/paymentReceiving.php?payment=save";
        } else {
            bank_code = $("#old_bank_code").val();
            cheque_no = $("#old_cheque_no").val();
            cancelled_cheque = $("#old_cheque_no").val();
            voucher_no = $("#old_voucher_no").val();
            url = "exec/payment/paymentReceiving.php?payment=update";
        }
        charge_to = $("#charge_to").val();
        remarks = $("#remarks").val();
    }

    var datasplited = needtosplit.split("_");
    var cheque_name = datasplited[0];
    var list = {
        payment_id: payment_id,
        bank_code: bank_code,
        cheque_no: cheque_no,
        voucher_no: voucher_no,
        cheque_name: cheque_name,
        supplier_id: $("#supplier_id").val(),
        sub_total: $("#sub_total").val(),
        ts_fee: $("#ts_fee").val(),
        adjustments: $("#adjustments").val(),
        grand_total: $("#grand_total").val(),
        account_name: cheque_name,
        account_number: account_number,
        type: "supplier",
        pay_type: "Receiving",
        cancelled_cheque: cancelled_cheque,
        remarks: remarks,
        charge_to: charge_to,
        cheque_date: $("#cheque_date").val(),
        trans_array: $("#trans_array").val()
    };
    var payment_adjustments = [];
    var c = 1;
    while (c <= 5) {
//        if ($("#adj_" + c).val() !== "") {
        payment_adjustments.push({
            adj_id: $("#adj_id_" + c).val(),
            ac_id: $("#ac_id_" + c).val(),
            tp_id: $("#tp_id_" + c).val(),
            adj_type: $("#adj_" + c).val(),
            desc: utf8_encode($("#desc_" + c).val()),
            amount: $("#amount_" + c).val()
        });
//        }
        c++;
    }
    list.payment_adjustments = (payment_adjustments.length === 0 ? "" : payment_adjustments);
    var delivery_adjustments = [];
    var del_adj_co = $("#del_adj_co").val();
    var c = 1;
    while (c < del_adj_co) {
//        if ($("#adj_price" + c).val() !== "") {
        delivery_adjustments.push({
            detail_id: $("#detail_id" + c).val(),
            material_id: $("#material_id" + c).val(),
            net_weight: $("#adj_qty" + c).val(),
            cost: $("#adj_price" + c).val(),
            amount: $("#adj_amount" + c).val()
        });
//        }
        c++;
    }
    list.delivery_adjustments = (delivery_adjustments.length === 0 ? "" : delivery_adjustments);
    console.log(list);
    if (cheque_name === "" || bank_code === "" || cheque_no === "") {
        alert("Please fill up required fields.");
    } else {
        $.ajax({
            type: "POST",
            url: url,
            data: {data: list},
            async: false

        }).done(function (e) {
            if (e !== 0) {
                $("#payment_id").val(e);
                $("input").prop("readonly", true);
                $("input").prop("readonly", true);
                $("select").prop("disabled", true);
                $("#err").html("<font color = 'red'>Payment saved.</font>");
                if ($("[name=operation]").val() !== undefined) {
                    if ($("[name=operation]:eq(0)").is(':checked') === true) {
                        $("#print_voucher").show();
                        $("#print_cheque").show();
                        $("#finish1").html("<a href='index.php'><button class='large-submit'>Finish</button></a>");
                        $("#finish1").show();
                        $("#back").prop("disabled", true);
                        $("#save").prop("disabled", true);
                    } else if ($("[name=operation]:eq(1)").is(':checked') === true) {
                        $("#finish2").html("<a href='send_payments.php?pay_id=" + $("#payment_id").val() + "'><button class='large-submit'>Finish</button></a>");
                        $("#finish2").show();
                        $("#back2").prop("disabled", true);
                        $("#save2").prop("disabled", true);
                    }
                } else {
                    $("#print_voucher").show();
                    $("#print_cheque").show();
                    $("#finish1").html("<a href='index.php'><button class='large-submit'>Finish</button></a>");
                    $("#finish1").show();
                    $("#back").prop("disabled", true);
                    $("#save").prop("disabled", true);
                }

            } else {
                $("#err").html("<font color = 'red'>Some data not save correctly in database, Please contact your system admin immediately.</font>");
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
        var cheque_name = datasplited[0];
        window.open("print_details.php?trans_id=" + trans_array + "&cheque_name=" + cheque_name, 'mywindow', 'width=1200,height=600,left=50,top=50');
    }
}

function print_cheque() {
    var payment_id = $("#payment_id").val();
    window.open("print_cheque.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
}

// end form 2 functions

// <<--------->>

//start when page ready
$(document).ready(function () {
//form 1 init
    $('#mark_paid').click(function () {
        var con = confirm('Are you sure you want to mark as paid?');
        if (con === true) {
            var mark_paid = prompt("Please enter the check/sbc voucher number where this transaction paid. When you click ok you cannot undo this action. Thanks!");
            if (mark_paid != null) {
                var trans_ids = $('#trans_array').val();
                location.replace("mark_as_paid.php?trans_id=" + trans_ids + "&ref=" + mark_paid);
            }
        }
    });
    $(".number").on("keypress", function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
                && (charCode < 48 || charCode > 57))
            return false;
        return true;
    });
    $("#form2").hide();
    $("#form3").hide();
    //form 2 init
    $("#print_details").hide();
    $("#print_voucher_digi").hide();
    $("#print_voucher").hide();
    $("#print_cheque").hide();
    $("#finish1").hide();
    $("#finish2").hide();
    $("#cheque_name").select2({tags: true});
    $("#account_name").select2({tags: true});
    $("#cheque_no").select2();
    if ($("#payment_id").val() !== "") {
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
function nextForm() {
    if ($("#payment_id").val() === "") {
        if ($("[name=operation]:eq(0)").is(':checked') === true) {
            $("#form1").hide();
            $("#form2").show();
        } else if ($("[name=operation]:eq(1)").is(':checked') === true) {
            $("#form1").hide();
            $("#form3").show();
        }
    } else {
        $("#form1").hide();
        $("#form2").show();
    }
}
function backForm() {
    if ($("#payment_id").val() === "") {
        if ($("[name=operation]:eq(0)").is(':checked') === true) {
            $("#form2").hide();
            $("#form1").show();
        } else if ($("[name=operation]:eq(1)").is(':checked') === true) {
            $("#form3").hide();
            $("#form1").show();
        }
    } else {
        $("#form2").hide();
        $("#form1").show();
    }
}
//end when page ready