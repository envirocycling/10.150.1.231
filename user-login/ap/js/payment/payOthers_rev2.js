$(document).ready(function () {
    $("#print_voucher").hide();
    $("#print_cheque").hide();
    $("#finish").hide();
    $("#payee").select2();
    $("#payee2").select2();
    $("#cheque_no").select2();
    $.ajax({
        type: "POST",
        url: "clear_temp.php",
        data: {type: 'clear'}
    });
    var c = Number($.row_count) + 1;
    while (c <= 20) {
        $("#row_" + c).hide();
        c++;
    }

    $("#plus").click(function () {
        compute();
        var row_count = $("#row_show").val();
        if (row_count < 20) {
            row_count++;
            $("#row_" + row_count).show();
            $("#row_show").val(row_count);
        } else {
            alert("Limit is 20 items only.");
        }
    });
    $("#minus").click(function () {
        var row_count = $('#row_show').val();
        if (row_count > 1) {
            $("#particular_" + row_count).val("");
            $("#quantity_" + row_count).val("");
            $("#unit_price_" + row_count).val("");
            $("#amount_" + row_count).val("");
            $("#row_" + row_count).hide();
            row_count--;
            $("#row_show").val(row_count);
        }
        compute();
    });
    $(window).unload(function () {
        $.ajax({
            type: "POST",
            url: "clear_temp.php",
            data: {type: 'clear'}
        });
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
    var cnfrm = confirm("Are you sure you want to save this transaction?");
    if (cnfrm === true) {
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

        
        var type = $("#type").val();
        var cheque_date = $("#cheque_date").val();
        var description = escape($("#description").val());
        var payee = ($("#payee_new").val()).replace(/&/g,"[ampersand]");
        if (payee === '') {
            var cheque_name = (utf8_encode($("#payee").val())).replace(/&/g,"[ampersand]");
        } else {
            var cheque_name = (utf8_encode($("#payee_new").val())).replace(/&/g,"[ampersand]");
        }
        //non trade new payee cheque start
        var chk_nontrade = Number($("#chk_nontrade").val());
        if(chk_nontrade === 1){
        var payee2 = ($("#payee_new2").val()).replace(/&/g,"[ampersand]");
            if (payee2 === '') {
                var cheque_name2 = (utf8_encode($("#payee2").val())).replace(/&/g,"[ampersand]");
            } else {
                var cheque_name2 = (utf8_encode($("#payee_new2").val())).replace(/&/g,"[ampersand]");
            }
            if($('[name=chk]').is(':checked')){
                var ft = 1;
            }else{
                var ft = 0;
            }
        }else{
            var cheque_name2 = '';
            var ft = 0;
        }
        //non trade new payee cheque end
        var grand_total = $("#grand_total").val();

        var user_id = $("#user_id").val();
        var verifier = $("#verifier").val();
        var signatory = $("#signatory").val();

        var particular_1 = escape($("#particular_1").val());
        var particular_2 = escape($("#particular_2").val());
        var particular_3 = escape($("#particular_3").val());
        var particular_4 = escape($("#particular_4").val());
        var particular_5 = escape($("#particular_5").val());
        var particular_6 = escape($("#particular_6").val());
        var particular_7 = escape($("#particular_7").val());
        var particular_8 = escape($("#particular_8").val());
        var particular_9 = escape($("#particular_9").val());
        var particular_10 = escape($("#particular_10").val());
        var particular_11 = escape($("#particular_11").val());
        var particular_12 = escape($("#particular_12").val());
        var particular_13 = escape($("#particular_13").val());
        var particular_14 = escape($("#particular_14").val());
        var particular_15 = escape($("#particular_15").val());
        var particular_16 = escape($("#particular_16").val());
        var particular_17 = escape($("#particular_17").val());
        var particular_18 = escape($("#particular_18").val());
        var particular_19 = escape($("#particular_19").val());
        var particular_20 = escape($("#particular_20").val());
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
        
        if(chk_nontrade === 1){
            if (cheque_name2 === ''){
                alert('Please Input Name Appear on Cheque Name.');
            }
        }if (cheque_name === '') {
            alert('Please Input Name Appear on Cheque.');
        } else if (bank_code === '') {
            alert('Please Choose Account.');
        } else if (cheque_no === '' || cheque_no === null) {
            alert('Please Select Cheque Number.');
        } else if (cheque_no === 'Range Error') {
            alert('Please Input New Cheque Range.');
        } else if (amount_1 === '') {
            alert('Please Input Atleast one.');
        } else {
            var dataString = 'bank_code=' + bank_code + '&cheque_no=' + cheque_no + '&old_cheque_no=' + old_cheque_no + '&cheque_date=' + cheque_date + '&voucher_no=' + voucher_no + '&cheque_name=' + cheque_name + '&grand_total=' + grand_total + '&type=' + type + '&description=' + description
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
                    + '&others_id_20=' + others_id_20
                    + '&cheque_name2=' + cheque_name2
                    + '&ft=' + ft
                    + '&chk_nontrade=' + chk_nontrade;
            $.ajax({
                type: "POST",
                url: "exec/othPay.php?payment=submitInitial",
                data: dataString,
                cache: false
            }).done(function (e) {
                $("#err").html(e);
                if (e === '') {
                    var updateCheck = '0';
                    if ($("#new_cheque") !== undefined) {
                        var check = $('#new_cheque').is(":checked");
                        if (check === true) {
                            updateCheck = '1';
                        }
                        $("#new_cheque").prop("disabled", true);
                    }

                    var url = '';
                    var payment_id = $("#payment_id").val();
                    if (payment_id !== '') {
                        if (updateCheck === '0') {
                            url = "exec/othPay.php?payment=submitFinalEditUpdate&payment_id=" + payment_id;
                        } else {
                            url = "exec/othPay.php?payment=submitFinalEditSave&payment_id=" + payment_id;
                        }
                    } else {
                        url = "exec/othPay.php?payment=submitFinal";
                    }


                    $.ajax({
                        url: url
                    }).done(function (e) {
                        $("#err").html(e);
                        if (payment_id === '' || updateCheck === '1') {
                            $.ajax({
                                url: "exec/othPay.php?payment=getIdSaved"
                            }).done(function (id) {
                                $("#payment_id").val(id);
                            });
                        }
                    });
                    var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
                    $("textarea").prop("readonly", true);
                    $("input").prop("readonly", true);
                    $("select").prop("disabled", true);
                    $("#plus").prop("disabled", true);
                    $("#minus").prop("disabled", true);
                    $("#save").prop("disabled", true);
                    $("#print_voucher").show();
                    $("#print_cheque").show();
                    $("#finish").show();
                    $("#msg").html(msg);
                }
            });
        }
    }
}

function print_voucher() {
    var payment_id = $("#payment_id").val();
    window.open("print_voucher_others.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
}

function print_cheque() {
//    var checker = $("#checker").val();
//    if (checker == 0) {
//        alert('Please Print the voucher first.');
//    } else {
//        var dataString = 'checker=' + checker;
//        $.ajax({
//            type: "POST",
//            url: "exec/othPay.php?payment=submitFinal",
//            data: dataString,
//            cache: false
//        }).done(function (msg) {
//            $("#err").html(msg);
//        });
//        var finish = "<a href='clear_temp.php'><button class='large-submit'>Finish</button></a>";
//        var msg = "<font color='red'>Please click the finish button to clear the temporary payment data.</font>";
//        document.getElementById("finish").innerHTML = finish;
//        document.getElementById("msg").innerHTML = msg;
    var payment_id = $("#payment_id").val();
    window.open("print_cheque.php?payment_id=" + payment_id, 'mywindow', 'width=1200,height=600,left=50,top=50');
//    }
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

function verifierSignatory(val) {
    if (val === 'supplier') {
        $("#verifier").val($.verifier);
        $("#signatory").val($.signatory);
        $("#verifier_name").val($.verifier_name);
        $("#signatory_name").val($.signatory_name);
    } else if (val === 'others') {
        $("#verifier").val($.verifier2);
        $("#signatory").val($.signatory2);
        $("#verifier_name").val($.verifier_name2);
        $("#signatory_name").val($.signatory_name2);
    }
}
