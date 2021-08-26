<?php
date_default_timezone_set("Asia/Singapore");
session_start();
include 'config.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Envirocycling Fiber Inc.</title>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/pay_table.css" />
        <link rel="stylesheet" type="text/css" href="css/modal.css" />
        <link href="css/adv_form.css" rel="stylesheet">
        <script src="js/bootstrap.min.js"></script>
        <link href="css/select2.min.css" rel="stylesheet">
        <script src="js/select2.min.js"></script>

        <style>
            .table{
                font-size: 18px;
            }
            #supplier_id{
                width: 250px;
            }
            #table{
                width: 1000px;
            }
            #header{
                width: 450px;
            }
            .tcal{
                text-transform: uppercase;
                border-radius: 4px;
                height: 30px;
                width: 180px;
                font-size: 18px;
            }
            #modalTable{

            }
            #modalTable td{
                font-size: 15px;
                vertical-align: top;
            }
        </style>

        <script>
            $(document).ready(function () {
                $('#supplier_id').select2();
            });

            function requestRemovePenalty(id) {
                var tr_id = $("#" + id + "_id").val();
                var penalty = $("#" + id + "_penalty").val();
                var month = $("#" + id + "_month").val();
                var remarks = $("#" + id + "_remarks").val();

                $("#tp_id").val(id);
                $("#tr_id").val(tr_id);
                $("#penalty").val(penalty);
                $("#month").val(month);
                $("#remarks").val(remarks);
            }

            function saveRemovePenalty() {
                var check = confirm("Are you sure to request to waive this penalty?");
                var tp_id = $("#tp_id").val();
                var tr_id = $("#tr_id").val();
                var penalty = $("#penalty").val();
                var month = $("#month").val();
                var remarks = $("#remarks").val();
                if (check === true) {
                    $("#" + tp_id + "_penalty").val(penalty);
                    $("#" + tp_id + "_remarks").val(remarks);
                    $("#tr_id").val("");
                    $("#tp_id").val("");
                    $("#penalty").val("");
                    $("#month").val("");
                    $("#remarks").val("");
                    $.ajax({
                        url: 'exec/truckMonitoring_exec.php?type=saveRequest',
                        data: {tp_id: tp_id, tr_id: tr_id, penalty: penalty, month: month, remarks: remarks},
                        type: 'POST'
                    }).done(function (e) {
                        if (e === '') {
                            $('.close').click();
                            e.stopImmediatePropagation();
                            alert('Request to waive penalty already submitted.');
                        }
                    });
                }
            }

            function cancelRequest(id) {
                var r = confirm("Are you sure you want to cancel this request?");

                var data = id.split("_");
                switch (r) {
                    case true:
                        $.ajax({
                            url: 'exec/truckMonitoring_exec.php?type=cancelRequest',
                            data: {tpr_id: id},
                            type: 'POST'
                        }).done(function (e) {
                            $("#" + id).hide();
                            $("#" + data[1]).attr("title", "Request to waive penalty.").html("Waive");
                        });
                        break;
                }
            }
        </script>
    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle">
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <div style="margin-left: 20px;">
                    <h2>Truck Monitoring</h2>

                    <br>
                    <form action="" method="POST">
                        <table class="table">
                            <tr>
                                <td colspan="2">Supplier Name: <select id="supplier_id" name="supplier_id">
                                        <option value="">All</option>
                                        <?php
                                        $sql_tr = mysql_query("SELECT supplier_id FROM truck_monitoring GROUP BY supplier_id");
                                        while ($rs_tr = mysql_fetch_array($sql_tr)) {
                                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_tr['supplier_id'] . "'");
                                            $rs_sup = mysql_fetch_array($sql_sup);
                                            echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="submit" class="large-submit" name='submit' value="Submit"></td>
                            </tr>
                        </table>
                    </form>
                    <br><br><br>
                    <?php
                    if (isset($_POST['submit'])) {
                        if ($_POST['supplier_id'] == '') {
                            $sql_tr = mysql_query("SELECT * FROM truck_monitoring GROUP BY supplier_id");
                        } else {
                            $sql_tr = mysql_query("SELECT * FROM truck_monitoring WHERE supplier_id='" . $_POST['supplier_id'] . "'");
                        }
                        while ($rs_tr = mysql_fetch_array($sql_tr)) {
                            $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_tr['supplier_id'] . "'");
                            $rs_sup = mysql_fetch_array($sql_sup);
                            echo '<div id="header" class="payTable">';
                            echo '<table>';
                            echo '<tr>';
                            echo '<td colspan="3">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>PLATE NUMBER: </td>';
                            echo '<td>' . $rs_tr['plate_no'] . '</td>';
                            echo '<td></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>PLATE NUMBER: </td>';
                            echo '<td>' . $rs_tr['plate_no'] . '</td>';
                            echo '<td></td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>MO. TRUCK RENTAL: </td>';
                            echo '<td>' . number_format($rs_tr['rental'], 2) . '</td>';
                            $rental_bal = $rs_tr['rental'] * $rs_tr['rental_mo'];
                            echo '<td>' . number_format($rental_bal, 2) . '</td>';
                            echo '</tr>';
                            echo '<tr>';
                            echo '<td>MO. CASHBOND: </td>';
                            echo '<td>' . number_format($rs_tr['cashbond'], 2) . '</td>';
                            $cashbond_bal = $rs_tr['cashbond'] * $rs_tr['cashbond_mo'];
                            echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                            echo '</tr>';
                            echo '</table>';
                            echo '</div>';

                            echo '<br><br>';

                            echo '<div id="table" class="payTable">';
                            echo '<table>';
                            echo '<tr>';
                            echo '<td>DATE</td>';
                            echo '<td>REF. NO.</td>';
                            echo '<td>AMORTIZATION <br>(TRUCK RENTAL)</td>';
                            echo '<td>BALANCE</td>';
                            echo '<td>CASHBOND</td>';
                            echo '<td>BALANCE</td>';
                            echo '<td>PENALTY <br>FAILURE <br>TO MET THE <br>QUOTA</td>';
                            echo '<td>CURRENT <br>VOLUME</td>';
                            echo '<td>QUOTA</td>';
                            echo '<td style="width: 120px;">PENALTY <br> ACTION</td>';
                            echo '</tr>';

                            echo '<tr>';
                            echo '<td>' . date("Y-m-d", strtotime($rs_tr['issuance_date'])) . '</td>';
                            echo '<td>BEG. BAL.</td>';
                            echo '<td></td>';
                            echo '<td>' . number_format($rental_bal, 2) . '</td>';
                            echo '<td></td>';
                            echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '<td></td>';
                            echo '</tr>';

                            $sql_trPayment = mysql_query("SELECT * FROM truck_payment WHERE tr_id='" . $rs_tr['tr_id'] . "' and type!='penalty' ORDER BY month,type ASC");
                            while ($rs_trPayment = mysql_fetch_array($sql_trPayment)) {
                                $sql_ref_no = mysql_query("SELECT voucher_no FROM payment WHERE payment_id='" . $rs_trPayment['payment_id'] . "'");
                                $rs_ref_no = mysql_fetch_array($sql_ref_no);
                                echo '<tr>';

                                if ($rs_trPayment['type'] == 'amortization') {
                                    if ($rs_trPayment['status'] == 'paid') {
                                        $rental_bal-=$rs_trPayment['amount'];
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date_paid'])) . '</td>';
                                        echo '<td>' . $rs_ref_no['voucher_no'] . '</td>';
                                        echo '<td>' . number_format($rs_trPayment['amount'], 2) . '</td>';
                                    } else {
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date'])) . '</td>';
                                        echo '<td></td>';
                                        echo '<td></td>';
                                    }
                                    echo '<td>' . number_format($rental_bal, 2) . '</td>';
                                    echo '<td></td>';
                                    echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                    echo '<td></td>';
                                }

                                if ($rs_trPayment['type'] == 'cashbond') {

                                    if ($rs_trPayment['status'] == 'paid') {
                                        $cashbond_bal-=$rs_trPayment['amount'];
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date_paid'])) . '</td>';
                                        echo '<td>' . $rs_ref_no['voucher_no'] . '</td>';
                                        echo '<td></td>';
                                        echo '<td>' . number_format($rental_bal, 2) . '</td>';
                                        echo '<td>' . number_format($rs_trPayment['amount'], 2) . '</td>';
                                    } else {
                                        echo '<td>' . date("Y-m-d", strtotime($rs_trPayment['date'])) . '</td>';
                                        echo '<td></td>';
                                        echo '<td></td>';
                                        echo '<td>' . number_format($rental_bal, 2) . '</td>';
                                        echo '<td></td>';
                                    }

                                    echo '<td>' . number_format($cashbond_bal, 2) . '</td>';
                                    $sql_trPenalty = mysql_query("SELECT * FROM truck_payment WHERE tr_id='" . $rs_tr['tr_id'] . "' and type='penalty' and month='" . $rs_trPayment['month'] . "'");
                                    $rs_trPenalty = mysql_fetch_array($sql_trPenalty);
                                    echo '<td>' . $rs_trPenalty['amount'] . '</td>';

                                    $sql_sup_del = mysql_query("SELECT sum(scale_receiving_details.corrected_weight) as corrected_weight FROM scale_receiving INNER JOIN scale_receiving_details ON scale_receiving.trans_id=scale_receiving_details.trans_id WHERE scale_receiving.supplier_id='" . $rs_tr['supplier_id'] . "' and scale_receiving.date like '%" . date("Y/m", strtotime($rs_trPayment['date_paid'])) . "%'");
                                    $rs_sup_del = mysql_fetch_array($sql_sup_del);
                                    if ($rs_sup_del['corrected_weight'] < $rs_tr['proposed_volume']) {

                                        $sql_check = mysqli_query($conn, "SELECT * FROM `truck_penalty_reqremove` WHERE tr_id='" . $rs_tr['tr_id'] . "' and month='" . $rs_trPayment['month'] . "' and status!='disapproved'");
                                        $rs_check = mysqli_fetch_array($sql_check);
                                        $rs_count = mysqli_num_rows($sql_check);


                                        echo '<td style="color: red; font-weight: bold;">' . round($rs_sup_del['corrected_weight'], 2) . '</td>';
                                        echo '<td>' . $rs_tr['proposed_volume'] . '</td>';
                                        echo '<td style="text-align: center;">';
                                        if ($rs_trPenalty['amount'] > 0) {
                                            echo '<font color="red"><b>PENALTY</b></font><br>';
                                        }

                                        echo '<input id="' . $rs_trPenalty['tp_id'] . '_id" type="hidden" name="tr_id" value="' . $rs_tr['tr_id'] . '">'
                                        . '<input id="' . $rs_trPenalty['tp_id'] . '_penalty" type="hidden" name="penalty" value="' . $rs_tr['penalty'] . '">'
                                        . '<input id="' . $rs_trPenalty['tp_id'] . '_month" type="hidden" name="month" value="' . $rs_trPayment['month'] . '">'
                                        . '<input id="' . $rs_trPenalty['tp_id'] . '_remarks" type="hidden" name="remarks" value="' . $rs_check['remarks'] . '">';
                                        if ($rs_trPenalty['amount'] > 0 && $rs_trPenalty['status'] == '' && $rs_count <= 0) {
                                            echo '<button id="' . $rs_trPenalty['tp_id'] . '" class="small-long-submit" data-toggle="modal" data-target="#myModal" onclick="requestRemovePenalty(this.id);" title="Request to waive penalty.">Waive</button>';
                                        } else if ($rs_count > 0 && $rs_trPenalty['status'] == '') {
                                            echo '<button id="' . $rs_trPenalty['tp_id'] . '" class="small-long-submit" data-toggle="modal" data-target="#myModal" onclick="requestRemovePenalty(this.id);" title="Click here to edit your request.">Edit</button>';
                                            echo '<button id="' . $rs_check['tpr_id'] . '_' . $rs_trPenalty['tp_id'] . '" class="small-long-submit" onclick="cancelRequest(this.id);" title="Click here to cancel your request.">Cancel</button>';
                                        }

                                        if ($rs_trPenalty['status'] == 'paid') {
                                            echo "(PAID)";
                                        }
                                        echo '</td>';
                                    } else {
                                        echo '<td>' . $rs_sup_del['corrected_weight'] . '</td>';
                                        echo '<td>' . $rs_tr['proposed_volume'] . '</td>';
                                        echo '<td></td>';
                                    }
                                }

                                echo '</tr>';
                            }

                            echo '</table>';
                            echo '</div>';
                            echo '<br><br>';
                        }
                    } else {
                        echo '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                    }
                    ?>
                </div>
                <div id="err"></div>
            </div><!--.middle-->

            <footer class="footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" hidden="hidden">&times;</button>
                <h4 class="modal-title">Request to Waive Penalty</h4>
            </div>
            <div class="modal-body">
                <div align="center">
                    <input type="hidden" id="tr_id" name="tr_id">
                    <input type="hidden" id="tp_id" name="tp_id">
                    <table id="modalTable">
                        <tr>
                            <td>Month: </td>
                            <td><input id="month" class="medium-input" type="medium-input" name="month" readonly></td>
                        </tr>
                        <tr>
                            <td>Penalty: </td>
                            <td><input id="penalty" class="medium-input" type="medium-input" name="penalty"</td>
                        </tr>
                        <tr>
                            <td>Remarks: </td>
                            <td><textarea id="remarks" class="medium-textarea-1"></textarea></td>
                        </tr>
                    </table>
                    <br>
                </div>                                        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal" onclick="savePenalty()">Save</button>
                <button type="button" class="btn" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>