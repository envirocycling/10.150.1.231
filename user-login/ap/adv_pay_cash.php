<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
$sql_ac = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);

$sql_pay = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_ac['payment_id'] . "'");
$rs_pay = mysql_fetch_array($sql_pay);

$sql_adv_less = mysql_query("SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_ac['ac_id'] . "' and payment.status!='cancelled'");
$rs_adv_less = mysql_fetch_array($sql_adv_less);

$sql_adv_pay = mysql_query("SELECT sum(amount) FROM adv_payment WHERE ac_id='" . $rs_ac['ac_id'] . "' and status!='cancelled'");
$rs_adv_pay = mysql_fetch_array($sql_adv_pay);

$total_less = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

$out_bal = round($rs_ac['amount'] - $total_less, 2);
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
            $(document).ready(function () {
                $("#forDeduction").keyup(function () {
                    var oSB = Number($("#outstandingBal").val());
                    var fD = Number($("#forDeduction").val());
                    var nEB = Number(oSB - fD);
                    if (oSB < fD) {
                        alert('For Deduction must not greater that outstanding balance.');
                        $("#forDeduction").val(oSB);
                        $("#newEndingBal").val('0');
                    } else {
                        $("#newEndingBal").val(nEB);
                    }
                });

                $("#submit").click(function () {
                    var amount = $("#forDeduction").val();
                    var remarks = $("#remarks").val();

                    var dataString = 'amount=' + amount + '&remarks=' + remarks;
                    $.ajax({
                        type: "POST",
                        url: "exec/adv_exec.php?payment=payCash&ac_id=<?php echo $_GET['ac_id']; ?>",
                        data: dataString
                    }).done(function (e) {
//                        $("#err").html(e);
                        alert('Successfully Process');
                        location.replace('adv_list.php');
                    });
                });
            });
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
                        <h2>ADVANCES CASH PAYMENT</h2>
                        <br>
                        <table class="table">
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ac_no" class="medium-input" type="text" name="ac_no" value="<?php echo $rs_ac['ac_no']; ?>" readonly>
                            </tr>
                            <tr>
                                <td>Supplier Name:</td>
                                <td><select id="supplier_id" class="medium-select-2" name="" readonly>

                                        <?php
                                        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_ac['supplier_id'] . "'");
                                        $rs_sup = mysql_fetch_array($sql_sup);
                                        echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                        ?>
                                    </select></td>
                                <td>Payment Type: </td>
                                <td><select id="acpty_id" class="medium-select" name="" readonly>
                                        <?php
                                        $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_ac['acpty_id'] . "'");
                                        $rs_ptype = mysql_fetch_array($sql_ptype);
                                        echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Principal: </td>
                                <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>"  readonly></td>
                                <td>Type: </td>
                                <td><select id="acty_id" class="medium-select" name="" readonly>
                                        <?php
                                        $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_ac['acty_id'] . "'");
                                        $rs_type = mysql_fetch_array($sql_type);
                                        echo '<option value="' . $rs_type['acty_id'] . '">' . $rs_type['name'] . '</option>';
                                        ?>
                                    </select></td>
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
                                <td>Outstanding Bal: </td>
                                <td><input id="outstandingBal" class="medium-input" type="text" name="outstandingBal" value="<?php echo $out_bal; ?>" readonly></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Cash Payment: </td>
                                <td><input id="forDeduction" class="medium-input" type="number" name="forDeduction" value="" onkeypress="compute();"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>New Ending Bal: </td>
                                <td><input id="newEndingBal" class="medium-input" type="text" name="newEndingBal" value="" readonly></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Remarks: </td>
                                <td colspan="3"><textarea id="remarks" class="medium-textarea-3" maxlength="200"></textarea></td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <button id="submit" class='large-submit'>Submit</button>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <div id="err"></div>
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