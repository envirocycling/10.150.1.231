<?php
session_start();
?>
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
</script>
<script>
    function openWindow(str) {
        var data = str.split("_");
        var id = data[1];
        if (data[2] == null) {
            document.getElementById("b_" + id).value = "Edit";
            document.getElementById("t_" + id).innerHTML = "GENERATED";
        }
        window.open("../save_receiving.php?trans_id=" + id, 'mywindow', 'width=1020,height=600,left=150,top=20');

    }
    function openWindow2(str) {
        window.open("../edit_rec_transaction.php?trans_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');
    }
    function openWindow3(str) {
        window.open("../view_rec_trans_details.php?trans_id=" + str, 'mywindow', 'width=900,height=500,left=180,top=20');
    }
    function openWindow4(str) {
        window.open("../edit_rec_trans_details.php?trans_id=" + str, 'mywindow', 'width=900,height=500,left=180,top=20');
    }
</script>
<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .submitq {
        height: 20px;
        width: 60px;
    }
    .total {
        background-color: yellow;
        font-weight: bold;
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
<?php
include '../config.php';

if (isset($_GET['cancel_id'])) {
    mysql_query("UPDATE scale_receiving SET status='' WHERE trans_id='" . $_GET['cancel_id'] . "'");
}
$total_weight = 0;
$total_less_weight = 0;
$corrected_weight = 0;
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="80">STR #</th>
            <th class="data" width="80">Series #</th>
            <th class="data" width="80">Supplier Name</th>
            <th class="data">Plate #</th>            
            <th class="data">Delivered To</th>
            <th class="data">Branch</th>
            <th class="data">Status</th>
            <th class="data">Date Paid</th>
            <th class="data">Cheque No.</th>
            <th class="data">Voucher No.</th>
            <th class="data">Action</th>
        </tr>
        </thead>';
if (!isset($_GET['from'])) {
    $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE status='' and date>='2015/10/02'");
} else {
    if ($_GET['status'] == '') {
        $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE status!='void' and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
    } else {
        if ($_GET['status'] == 'pending') {
            $status = '';
        } else {
            $status = $_GET['status'];
        }
        $sql_rec = mysql_query("SELECT * FROM scale_receiving WHERE (status!='void' and status='$status') and date>='" . $_GET['from'] . "' and date<='" . $_GET['to'] . "'");
    }
}
while ($rs_rec = mysql_fetch_array($sql_rec)) {
    $sql_count = mysql_query("SELECT * FROM scale_receiving_details WHERE trans_id='" . $rs_rec['trans_id'] . "'");
    $rs_count = mysql_num_rows($sql_count);

    $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_rec['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);

    $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id='" . $rs_rec['dt_id'] . "'");
    $rs_dt = mysql_fetch_array($sql_dt);
    echo "<tr class='data'>";
    echo "<td class='data'>" . $rs_rec['date'] . "</td>";
    echo "<td class='data'>" . $rs_rec['str_no'] . "</td>";
    echo "<td class='data'>" . $rs_rec['series_no'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "_" . strtoupper($rs_sup['supplier_name']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_rec['plate_number']) . "</td>";
    echo "<td class='data'>" . strtoupper($rs_dt['name']) . "</td>";
    echo "<td class='data'>PAMPANGA</td>";
    if ($rs_rec['status'] == '') {
        echo "<td class='data'><span id='t_" . $rs_rec['trans_id'] . "'>PENDING</span></td>";
    } else {
        echo "<td class='data'>" . strtoupper($rs_rec['status']) . "</td>";
    }

    $sql_payment = mysql_query("SELECT * FROM payment WHERE payment_id='" . $rs_rec['payment_id'] . "'");
    $rs_payment = mysql_fetch_array($sql_payment);
    if ($rs_payment['bank_code'] == 'SBC') {
        $sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
        $rs_code = mysql_fetch_array($sql_code);

        $voucher_no = "SBC_" . $rs_code['code'] . "" . $rs_payment['voucher_no'];
    } else {
        $voucher_no = $rs_payment['voucher_no'];
    }
    echo "<td>" . $rs_payment['date'] . "</td>";
    echo "<td>" . $rs_payment['cheque_no'] . "</td>";
    echo "<td>$voucher_no</td>";

    echo "<td class='data'>";
    if (($rs_rec['status'] == '') && ($_SESSION['user_id'] == 55 || $_SESSION['user_id'] == 40 || $_SESSION['user_id'] == 68 || $_SESSION['user_id'] == 98 || $_SESSION['user_id'] == 102)) {
        echo "<input type='submit' id='b_" . $rs_rec['trans_id'] . "' onclick='openWindow(this.id);' class='button' name='save' value='Save'> ";
    } else if (($rs_rec['status'] == 'generated' || $rs_rec['status'] == 'for_evaluation') &&($_SESSION['user_id'] == 55 || $_SESSION['user_id'] == 40 || $_SESSION['user_id'] == 68 || $_SESSION['user_id'] == 98 || $_SESSION['user_id'] == 102)) {
        echo "<button id='b_" . $rs_rec['trans_id'] . "_1' onclick='openWindow(this.id);' class='button'>Edit</button> ";
    } else if ($_SESSION['user_id'] == 55  || $_SESSION['user_id'] == 40) {
        echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow(this.id);' class='button' disabled>Edit</button> ";
    }
    if ($rs_count > 1 && $rs_rec['status'] == '' && ($_SESSION['user_id'] == 55 || $_SESSION['user_id'] == 40 || $_SESSION['user_id'] == 68 || $_SESSION['user_id'] == 98 || $_SESSION['user_id'] == 102)) {
        echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow2(this.id);' class='button'>New STR</button> ";
    }
    //echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow2(this.id);' class='button'>New STR</button> ";
    if ($rs_rec['status'] == 'generated' && ($_SESSION['user_id'] == 55 || $_SESSION['user_id'] == 40 || $_SESSION['user_id'] == 68 || $_SESSION['user_id'] == 98 || $_SESSION['user_id'] == 102)) {
        echo "<a href='query_receiving.php?cancel_id=" . $rs_rec['trans_id'] . "&from=" . $_GET['from'] . "&to=" . $_GET['to'] . "'><button class='button' onclick=\"return confirm('Are you sure you want to change the status of this transaction to pending?')\">Cancel</button></a> ";
    }
    echo "<button id='" . $rs_rec['trans_id'] . "' onclick='openWindow3(this.id);' class='button'>View</button>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>
