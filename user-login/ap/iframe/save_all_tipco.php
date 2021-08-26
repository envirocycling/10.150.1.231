
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
        window.open("../view_tipco.php?scale_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');

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
include '../configTPTS.php';

$sql_rec = mysql_query("SELECT *,scale.status as status1,supplier.status as status2 FROM scale INNER JOIN supplier ON scale.supplier_id=supplier.supplier_id WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') and scale.status!='DELETED' and scale.date>='" . $_GET['from'] . "' and scale.date<='" . $_GET['to'] . "' and supplier.owner='EFI'");

while ($rs_rec = mysql_fetch_array($sql_rec)) {

    $id = $rs_rec['scale_id'];

    // ftp to efi
    $sql_scale = mysql_query("SELECT * FROM scale WHERE scale_id='" . $id . "'");
    $rs_scale = mysql_fetch_array($sql_scale);

    $sstr_no = str_replace("/", "&", $rs_scale['str_no']);

    $sql_sup = mysql_query("SELECT * FROM supplier WHERE supplier_id='" . $rs_scale['supplier_id'] . "'");
    $rs_sup = mysql_fetch_array($sql_sup);


    $sql_com = mysql_query("SELECT * FROM company WHERE company_id='" . $rs_scale['company_id'] . "'");
    $rs_com = mysql_fetch_array($sql_com);

    $file_date = date('YmdHis') . "-" . $rs_sup['name'] . "-" . strtoupper($rs_scale['plate_no']) . "-" . $sstr_no;
    $file_name = "./../../../ftp_data/" . $file_date;
    $myfile = fopen($file_name . ".txt", "w") or die("Unable to open file!");

    $time_out = 0;

    $timeArr = array_reverse(explode(":", date("H:i:s", strtotime($rs_scale['time_out']))));
    $start_seconds = 0;

    foreach ($timeArr as $key => $value) {
        if ($key > 2){
            break;
            $time_out += pow(60, $key) * $value;
        }

        //$date_time = $time_out - 25200;

        if ($time_out < 25200) {
            $date_out = date('Y/m/d', strtotime("-1 day", strtotime($rs_scale['date_out'])));
        } else {
            $date_out = date("Y/m/d", strtotime($rs_scale['date_out']));
        }

        $first_txt = $date_out . "\r\n" . $rs_scale['str_no'] . "\r\n" . $rs_scale['tr_no'] . "\r\n" . $rs_sup['name'] . "\r\n" . $rs_scale['plate_no'] . "\r\n" . $rs_com['name'] . "\r\n";
        $com_txt = '';

        $sql_details = mysql_query("SELECT * FROM scale_details WHERE scale_id='" . $id . "'");
                        
        while ($rs_details = mysql_fetch_array($sql_details)) {

            $sql_comm = mysql_query("SELECT * FROM commodity WHERE com_id='" . $rs_details['com_id'] . "'");
            $rs_comm = mysql_fetch_array($sql_comm);

            $com_txt.= $rs_comm['name'] . "\r\n" . $rs_details['date_in'] . "\r\n" . $rs_details['weigh_in'] . "\r\n" . $rs_details['date_out'] . "\r\n" . $rs_details['weigh_out'] . "\r\n" . $rs_details['gross'] . "\r\n" . $rs_details['tare'] . "\r\n" . $rs_details['net_weight'] . "\r\n" . $rs_details['moisture'] . "\r\n" . $rs_details['bales'] . "\r\n" . $rs_details['moisture_bales'] . "\r\n" . $rs_details['reject_perct'] . "\r\n" . $rs_details['reject_kg'] . "\r\n" . $rs_details['com_remarks'] . "\r\n";
        }

        $txt = $first_txt . "" . $com_txt;
        fwrite($myfile, $txt);
        fclose($myfile);
    }

}
?>