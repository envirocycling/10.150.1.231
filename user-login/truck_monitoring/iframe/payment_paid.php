
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
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

    function deleted(id) {
        var r = confirm("Are you sure you want to delete this transaction?");
        if (r === true) {
            var id_data = id.split("_");
            var pay_id = id_data[0];
            var pay_type = id_data[1];
            var data = 'payment_id=' + pay_id;
            $.ajax({
                url: "../exec/payment_paid_delExec.php?payment=can" + pay_type,
                type: 'POST',
                data: data
            }).done(function (response) {
                if (response === 'successed') {
                    alert('Successfully Deleted Payment.');
                    $("#" + pay_id).hide();
                } else if (response === 'failed_2') {
                    alert('Failed to cancel this payment, this transaction is used for issuing advances & already used for deducting supplier . Please call you System Admin.');
                } else {
                    alert('Failed to delete this payment, this transaction is from other branch. System is preventing to do this action it may affect other transaction in other branches, Please call you System Admin.');
                }
            });
        }
    }
</script>
<base target="_parent" />
<?php
date_default_timezone_set("Asia/Singapore");
include '../config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
 <th class="data">Date</th>
            <th class="data">Cheque #</th>
            <th class="data">Voucher #</th>
            <th class="data">Payee</th>
            <th class="data">Total Expense</th>
            <th class="data">Supplier</th>
            <th class="data">Actions</th>
</tr>
        </thead>';

$date_now = date("Y/m/d");

if (isset($_GET['from'])) {
    $type = $_GET['type'];

    $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code!='SBC' and (status!='cancelled' and status!='deleted') and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "' and type like '%$type%'");
} else {
    $sql_paid = mysql_query("SELECT * FROM payment WHERE bank_code!='SBC' and (status!='cancelled' and status!='deleted') and date>='" . date("Y/m/d") . "' and date<='" . date("Y/m/d") . "'");
}
while ($rs_paid = mysql_fetch_array($sql_paid)) {
    $date_minus_8d = date("Y/m/d", strtotime("+8 days", strtotime($rs_paid['date'])));

    echo "<tr id='" . $rs_paid['payment_id'] . "'>";
    echo "<td>" . $rs_paid['date'] . "</td>";
    echo "<td>" . $rs_paid['cheque_no'] . "</td>";
    echo "<td>" . $rs_paid['voucher_no'] . "</td>";
    echo "<td>" . $rs_paid['cheque_name'] . "</td>";
    echo "<td>" . $rs_paid['grand_total'] . "</td>";
    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_paid['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);
    echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
    echo "<td>";
    if ($rs_paid['pay_type'] == 'Receiving') {
        echo "<a href='../payment_paid_edit.php?payment_id=" . $rs_paid['payment_id'] . "'><button>View</button></a>";
        echo "<br>";
        if ($date_now <= $date_minus_8d) {
            echo "<button id='" . $rs_paid['payment_id'] . "_Sup' onclick='cancel(this.id);'>Cancel</button>";
            echo "<br>";
            echo "<button id='" . $rs_paid['payment_id'] . "_Sup' onclick='deleted(this.id);'>Delete</button>";
        }
    }
    if ($rs_paid['pay_type'] == 'Other Payment') {
        echo "<a href='../payment_paid_others_edit.php?payment_id=" . $rs_paid['payment_id'] . "'><button>View</button></a>";
        echo "<br>";
        if ($date_now <= $date_minus_8d) {
            echo "<button id='" . $rs_paid['payment_id'] . "_Oth' onclick='cancel(this.id);'>Cancel</button>";
            echo "<br>";
            echo "<button id='" . $rs_paid['payment_id'] . "_Oth' onclick='deleted(this.id);'>Delete</button>";
        }
    }
    if ($rs_paid['pay_type'] == 'Advances') {
        echo "<a href='../adv_form_process_edit.php?payment_id=" . $rs_paid['payment_id'] . "'><button>View</button></a>";
        echo "<br>";
        if ($date_now <= $date_minus_8d) {
            echo "<button id='" . $rs_paid['payment_id'] . "_Adv' onclick='cancel(this.id);'>Cancel</button>";
            echo "<br>";
            echo "<button id='" . $rs_paid['payment_id'] . "_Adv' onclick='deleted(this.id);'>Delete</button>";
        }
    }
    echo "</td>";
}
echo "</table>";
?>