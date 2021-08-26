
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });

    function cancel(id) {
        var r = confirm("Are you sure you want to change the status of this transaction to pending?");
        if (r === true) {
            var id_data = id.split("_");
            var pay_id = id_data[0];
            var pay_type = id_data[1];
            var data = 'payment_id=' + pay_id;
            $.ajax({
                url: "../exec/payment_paid_canExec.php?payment=can" + pay_type,
                type: 'POST',
                data: data
            }).done(function (response) {
                if (response === 'successed') {
                    alert('Successfully Cancelled Payment.');
                    $("#" + pay_id).hide();
                } else if (response === 'failed_2') {
                                    alert('Failed to cancel this payment, this transaction is used for issuing advances & already used for deducting supplier . Please call you System Admin.');
                                } else {
                                    alert('Failed to cancel this payment, this transaction is from other branch. System is preventing to do this action it may affect other transaction in other branches, Please call you System Admin.');
                                }
            });
        }
    }

    function openWindow(str) {
        window.open("../view_payments.php?payment_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');
    }
</script>
<style>
    #example{
        border-width:50%;
        font-size: 9px;
    }
    button{
        width: 60px;
    }
</style>
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>
<base target="_parent" />
<?php
date_default_timezone_set("Asia/Singapore");
include '../config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
 <th class="data">Date</th>
            <th class="data">Voucher #</th>
            <th class="data">Payee</th>
            <th class="data">Total Expense</th>
            <th class="data">Supplier</th>
            <th class="data">Action</th>
</tr>
        </thead>';

$date_now = date("Y/m/d");

$sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_code = mysql_fetch_array($sql_code);

$sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code='SBC' and status!='cancelled' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    $date_minus_8d = date("Y/m/d", strtotime("+8 days", strtotime($rs_paid['date'])));

    echo "<tr id='" . $rs_paid['payment_id'] . "'>";
    echo "<td>" . $rs_paid['date'] . "</td>";
    echo "<td>SBC_" . $rs_code['code'] . "" . $rs_paid['voucher_no'] . "</td>";
    echo "<td>" . $rs_paid['cheque_name'] . "</td>";
    echo "<td>" . $rs_paid['grand_total'] . "</td>";
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_paid['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
    echo "<td>";
    //echo "<a rel='facebox' href='../view_payments.php?payment_id=" . $rs_paid['payment_id'] . "'><button>View</button></a>";
    echo "<a href='../view_payments.php?payment_id=" . $rs_paid['payment_id'] . "' target='_blank'><button>View</button></a>";
    echo "<br>";
    echo "<a href='../send_payments.php?pay_id=" . $rs_paid['payment_id'] . "'><button>Resend</button></a>";
    echo "<br>";
    if ($date_now <= $date_minus_8d) {
        if ($rs_paid['pay_type'] == 'Receiving') {
            echo "<button id='" . $rs_paid['payment_id'] . "_Sup' onclick='cancel(this.id);'>Cancel</button>";
        }
        if ($rs_paid['pay_type'] == 'Advances') {
            echo "<button id='" . $rs_paid['payment_id'] . "_Adv' onclick='cancel(this.id);'>Cancel</button>";
        }
    }
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>