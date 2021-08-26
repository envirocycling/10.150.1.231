<script>
    print();
</script>

<style>
    body{
        font-family: Calibri;
        font-weight: 800;
        height: 50%;
        width: 97%;
        font-size: 11px;
    }
    #company{
        position: absolute;
        top: 14%;
        left: 30%;
    }
    #amount{
        position: absolute;
        top: 18.3%;
        left: 30%;
    }
    #date{
        position: absolute;
        top: 18.2%;
        right: 8%;
    }
    #ref_no{
        position: absolute;
        top: 22%;
        left: 41%;
    }
    #purpose{
        position: absolute;
        top: 25.5%;
        left: 10.3%;
        text-indent: 16%;
        width: 84%;
        line-height: 10px;
        text-align: left;
    }
    #approved{
        position: absolute;
        top: 28.1%;
        left: 35%;
        font-size: 9px;
    }
    #received{
        position: absolute;
        top: 45%;
        left: 35%;
        font-size: 9px;
    }
    #date_time{
        font-size: 9px;
    }
</style>
<?php
date_default_timezone_set("Asia/Singapore");
include("config.php");

$ea_id = $_GET['ea_id'];

$sql_ea = mysql_query("SELECT * from employee_advances WHERE ea_id='$ea_id'") or die(mysql_error());
$row_ea = mysql_fetch_array($sql_ea);

$sql_approver = mysql_query("SELECT * from users WHERE user_id='" . $row_ea['approver'] . "'") or die(mysql_error());
$row_approver = mysql_fetch_array($sql_approver);

$sql_prepare = mysql_query("SELECT * from users WHERE user_id='" . $row_ea['prepared_by'] . "'") or die(mysql_error());
$row_prepare = mysql_fetch_array($sql_prepare);

$sql_emp = mysql_query("SELECT * from employee WHERE emp_id ='" . $row_ea['emp_id'] . "'") or die(mysql_error());
$row_emp = mysql_fetch_array($sql_emp);
?>

<center>
    <body>
        <div id="company"><?php echo strtoupper($row_ea['comp_id']); ?></div>
        <div id="amount"><?php echo strtoupper($row_ea['amount']); ?></div>
        <div id="date"><?php echo date('M d, Y', strtotime($row_ea['date'])); ?></div>
        <div id="ref_no"><?php echo 'REF# ' . strtoupper($row_ea['ref_no']); ?></div>
        <div id="purpose"><?php echo strtoupper($row_ea['purpose']); ?></div>
        <div id="approved"><br/><br/><?php
            echo strtoupper($row_approver['firstname'] . ' ' . $row_approver['lastname']);
            if ($row_ea['status'] == 'approved' || $row_ea['status'] == 'issued' || $row_ea['status'] == 'liquidated') {
                echo ' /<img src="../../signatures_pamp/' . $row_approver['initial'] . '.png" height="25px">';
            } echo '/ &nbsp;<span id="date_time">' . date('Y-m-d h:i A', strtotime($row_ea['date_time_approved'])) . '</span>';
            ?></div>
        <div id="received"><br/><?php echo strtoupper($row_emp['name']) . ' /&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; / <span id="date_time">' . date('Y-m-d h:i A') . '</span>'; ?></div>
</body>
</center>