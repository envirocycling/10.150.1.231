$(document).ready(function () {
    $(window).unload(function () {
        var click = $("#click").val();
        if (click === '0') {
            $.ajax({
                type: "POST",
                url: "clear_temp.php",
                data: {type: 'clear'}
            });
        }
    });
});


function advances(str) {
    var id_data = str.split("_");
    var dedType = id_data[0];
    var id = id_data[1];
    if (dedType === 'a' || dedType === 'd') {
        $('#' + id).each(function () {
            var ac_no = $(this).find("#ac_no").html();
            var voucher_no = $(this).find("#voucher_no").html();
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
                    $("#amount_" + counter).val(amount).prop("readonly", true);
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


function enter() {
    var img = '<img class="load" src="images/load.gif" height="120">';
    $("#err").html(img);
    $("#click").val('1');

    var sub_total = $("#sub_total").val();
    var ts_fee = $("#ts_fee").val();
    var adjustments = $("#adjustments").val();
    var grand_total = $("#grand_total").val();
    var supplier_id = $("#supplier_id").val();

    var adj_id_1 = $("#adj_id_1").val();
    var ac_id_1 = $("#ac_id_1").val();
    var tp_id_1 = $("#tp_id_1").val();
    var adj_1 = $("#adj_1").val();
    var desc_1 = escape(utf8_encode($("#desc_1").val()));
    var amount_1 = $("#amount_1").val();

    var adj_id_2 = $("#adj_id_2").val();
    var ac_id_2 = $("#ac_id_2").val();
    var tp_id_2 = $("#tp_id_2").val();
    var adj_2 = $("#adj_2").val();
    var desc_2 = escape(utf8_encode($("#desc_2").val()));
    var amount_2 = $("#amount_2").val();

    var adj_id_3 = $("#adj_id_3").val();
    var ac_id_3 = $("#ac_id_3").val();
    var tp_id_3 = $("#tp_id_3").val();
    var adj_3 = $("#adj_3").val();
    var desc_3 = escape(utf8_encode($("#desc_3").val()));
    var amount_3 = $("#amount_3").val();

    var adj_id_4 = $("#adj_id_4").val();
    var ac_id_4 = $("#ac_id_4").val();
    var tp_id_4 = $("#tp_id_4").val();
    var adj_4 = $("#adj_4").val();
    var desc_4 = escape(utf8_encode($("#desc_4").val()));
    var amount_4 = $("#amount_4").val();

    var adj_id_5 = $("#adj_id_5").val();
    var ac_id_5 = $("#ac_id_5").val();
    var tp_id_5 = $("#tp_id_5").val();
    var adj_5 = $("#adj_5").val();
    var desc_5 = escape(utf8_encode($("#desc_5").val()));
    var amount_5 = $("#amount_5").val();

    var dataString = 'sub_total=' + sub_total + '&adjustments=' + adjustments + '&ts_fee=' + ts_fee + '&grand_total=' + grand_total + '&supplier_id=' + supplier_id
            + '&adj_id_1=' + adj_id_1 + '&ac_id_1=' + ac_id_1 + '&tp_id_1=' + tp_id_1 + '&adj_1=' + adj_1 + '&desc_1=' + desc_1 + '&amount_1=' + amount_1
            + '&adj_id_2=' + adj_id_2 + '&ac_id_2=' + ac_id_2 + '&tp_id_2=' + tp_id_2 + '&adj_2=' + adj_2 + '&desc_2=' + desc_2 + '&amount_2=' + amount_2
            + '&adj_id_3=' + adj_id_3 + '&ac_id_3=' + ac_id_3 + '&tp_id_3=' + tp_id_3 + '&adj_3=' + adj_3 + '&desc_3=' + desc_3 + '&amount_3=' + amount_3
            + '&adj_id_4=' + adj_id_4 + '&ac_id_4=' + ac_id_4 + '&tp_id_4=' + tp_id_4 + '&adj_4=' + adj_4 + '&desc_4=' + desc_4 + '&amount_4=' + amount_4
            + '&adj_id_5=' + adj_id_5 + '&ac_id_5=' + ac_id_5 + '&tp_id_5=' + tp_id_5 + '&adj_5=' + adj_5 + '&desc_5=' + desc_5 + '&amount_5=' + amount_5;


    var del_adj_co = $("#del_adj_co").val();
    var del_adj_counter = 1;
    while (del_adj_counter < del_adj_co) {
        var detail_id = $("#detail_id" + del_adj_counter).val();
        var material_id = $("#material_id" + del_adj_counter).val();
        var net_weight = $("#adj_qty" + del_adj_counter).val();
        var cost = $("#adj_price" + del_adj_counter).val();
        var amount = $("#adj_amount" + del_adj_counter).val();

        dataString += '&detail_id' + del_adj_counter + '=' + detail_id + '&material_id' + del_adj_counter + '=' + material_id + '&net_weight' + del_adj_counter + '=' + net_weight + '&cost' + del_adj_counter + '=' + cost + '&amount' + del_adj_counter + '=' + amount;
        del_adj_counter++;
    }

    $.ajax({
        type: "POST",
        url: "exec/recPay.php?payment=submitInitial&c=" + del_adj_co,
        data: dataString
    }).done(function (e) {
        if (e.trim() !== '') {
            $("#err").html(e);
        } else {
            if ($("#charge_to").val() !== undefined) {
                var charge_to = $("#charge_to").val();
                var remarks = $("#remarks").val();
                $("#charge_to_s").val(charge_to);
                $("#remarks_s").val(remarks);
            }
            $("form").submit();
        }
    });
}

function OnSubmitForm() {
    if (document.myform.operation[0].checked === true) {
        document.myform.action = "payment_next.php";
    } else if (document.myform.operation[1].checked === true) {
        document.myform.action = "payment_next2.php";
    }
    return true;
}