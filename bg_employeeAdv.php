<?php
include 'config.php';

$page2 = "bg_employeeAdv.php";
$sec2 = "900";
header("Refresh: $sec2; url=$page2");

$sql_branches = mysql_query("SELECT * from branches WHERE status=''") or die(mysql_error());
while ($row_branches = mysql_fetch_array($sql_branches)) {

    @$sqli_conn = mysqli_connect($row_branches['ip_address'], 'efi', 'enviro101', 'truck_scale');

    if (!mysqli_connect_errno($sqli_conn)) {
        $sql_eadvBranch = mysqli_query($sqli_conn, "SELECT * from employee_advances WHERE send='0'");
        $row_eadvBranch = mysqli_fetch_array($sql_eadvBranch);
        
        $sql_eadvBranch2 = mysqli_query($sqli_conn, "SELECT * from employee_advances WHERE send='0'");
        while ($row_eadvBranch = mysqli_fetch_array($sql_eadvBranch2)) {
            $branch_eaid = $row_eadvBranch['ea_id'];
            $emp_id = $row_eadvBranch['emp_id'];
            $ref_no = $row_eadvBranch['ref_no'];
            $amount = $row_eadvBranch['amount'];
            $purpose = mysql_real_escape_string($row_eadvBranch['purpose']);
            $date = $row_eadvBranch['date'];
            $approver = $row_eadvBranch['approver'];
            $date_time_approved = $row_eadvBranch['date_time_approved'];
            $status = $row_eadvBranch['status'];
            $date_received = $row_eadvBranch['date_received'];
            $date_liquidated = $row_eadvBranch['date_liquidated'];
            $total_expense = $row_eadvBranch['total_expense'];
            $excess_cash = $row_eadvBranch['excess_cash'];
            $returned_excess_cash = $row_eadvBranch['returned_excess_cash'];
            $pcv_no = $row_eadvBranch['pcv_no'];
            $prepared_by = $row_eadvBranch['prepared_by'];
            $type = $row_eadvBranch['type'];

            $sql_eadvPamp = mysql_query("SELECT * from employee_advances WHERE branch_id='" . $row_branches['branch_id'] . "' and branch_eaid='$branch_eaid' and branch_id!='0'");
            $row_eadvPamp = mysql_fetch_array($sql_eadvPamp);

            if ($type == 'external') {
                $sql_approver = mysql_query("SELECT * from users WHERE position = 'General Manager' ");
                $row_approver = mysql_fetch_array($sql_approver);
                $approver = $row_approver['user_id'];

                if (mysql_query("UPDATE employee_advances SET status='" . $row_eadvPamp['status'] . "', date_time_approved='" . $row_eadvPamp['date_time_approved'] . "' WHERE ea_id='$branch_eaid'") or die(mysql_error())) {
                    mysqli_query($sqli_conn, "UPDATE employee_advances SET send='1' WHERE ea_id = '$branch_eaid'");
                    mysql_query("UPDATE employee_advances SET send='1' WHERE branch_eaid='$branch_eaid'");
                }
            } else {
                $sql_approver = mysqli_query($sqli_conn, "SELECT * from users WHERE user_id = '$approver' ");
                $row_approver = mysqli_fetch_array($sql_approver);
                $approver = $approver . '-' . $row_approver['firstname'] . ', ' . $row_approver['lastname'];
            }

            $sql_preparedby = mysqli_query($sqli_conn, "SELECT * from users WHERE user_id = '$prepared_by' ");
            $row_preparedby = mysqli_fetch_array($sql_preparedby);
            $prepared_by = $prepared_by . '-' . $row_preparedby['firstname'] . ', ' . $row_preparedby['lastname'];


            if (mysql_num_rows($sql_eadvPamp) == 1) {
                if (mysql_query("UPDATE employee_advances SET emp_id='$emp_id', ref_no='$ref_no', amount='$amount', purpose='$purpose', date='$date', approver='$approver', date_time_approved='$date_time_approved', status='$status', date_received='$date_received', date_liquidated='$date_liquidated', total_expense='$total_expense', excess_cash='$excess_cash', returned_excess_cash='$returned_excess_cash', pcv_no='$pcv_no', prepared_by='$prepared_by', type='$type', send='0' WHERE  branch_eaid='$branch_eaid' and branch_id='" . $row_branches['branch_id'] . "'")) {
                    if ($status == 'liquidated') {
                        $sql_liquidated = mysqli_query($sqli_conn, "SELECT * from employee_advances_liquidate WHERE ea_id='$branch_eaid'");
                        $row_liquidated = mysqli_fetch_array($sql_liquidated);
                        if (mysql_query("INSERT INTO employee_advances_liquidate (ea_id, details, amount) VALUES('" . $row_eaid['ea_id'] . "', '" . mysql_real_escape_string($row_liquidated['details']) . "',  '" . $row_liquidated['amount'] . "')")) {
                            mysqli_query($sqli_conn, "UPDATE employee_advances SET send='1' WHERE ea_id='$branch_eaid'");
                        }
                    } else {
                        mysqli_query($sqli_conn, "UPDATE employee_advances SET send='1' WHERE ea_id='$branch_eaid'");
                    }
                }
            } else {
                if (mysql_query("INSERT INTO employee_advances (branch_eaid, branch_id, emp_id, ref_no, amount, purpose, date, approver, date_time_approved, status, date_received, date_liquidated, total_expense, excess_cash, returned_excess_cash, pcv_no, prepared_by, type)
                         VALUES ('$branch_eaid', '" . $row_branches['branch_id'] . "', '$emp_id', '$ref_no', '$amount', '$purpose', '$date', '$approver', '$date_time_approved', '$status', '$date_received', '$date_liquidated', '$total_expense', '$excess_cash', '$returned_excess_cash', '$pcv_no', '$prepared_by', '$type')")) {
                    mysqli_query($sqli_conn, "UPDATE employee_advances SET send='1' WHERE ea_id='$branch_eaid'");
                }
            }
        }
    } else {
        echo 'error-' . $row_branches['ip_address'], 'efi', 'enviro101', 'truck_scale' . '<br>';
    }
//    echo $row_branches['branch_name'].'<br>';
}
