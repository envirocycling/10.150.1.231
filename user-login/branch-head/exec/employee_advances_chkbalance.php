<!DOCTYPE html>
<html>
    <head>
        <style>
            #table {
                width: 100%;
                border-collapse: collapse;
            }

            #table, #td, #th {
                border: 1px solid black;
                padding: 5px;
                font-size: 13px;
            }

            #th {text-align: left;
                 background-color: #bebebe;
            }
            #head{
                color:#fb2a08;
                font-style: italic;
                padding-bottom: 10px;

                font-size: 14px;
            }
        </style>
    </head>
    <body>

        <?php
        $q = intval($_GET['q']);

        $con = mysqli_connect('localhost', 'root', '');
        if (!$con) {
            die('Could not connect: ' . mysqli_error($con));
        }

        mysqli_select_db($con, "efi_pamp");
        $sql = "SELECT * FROM employee_advances WHERE emp_id = '" . $q . "' and status LIKE '%issued' ORDER BY date ASC";
        $result = mysqli_query($con, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo '<br/><br/><span id="head">Employee has existing cash advance.</span><br />';
            echo "<table id='table'>
<tr>
<th id='th'>Date</th>
<th id='th'>Ref No</th>
<th id='th'>Amount</th>
<th id='th'>Purpose</th>
<th id='th'>Date Issued</th>
<th id='th'>Approved By</th>
<th id='th'>Prepared By</th>
</tr>";
            while ($row = mysqli_fetch_array($result)) {

                $data = explode('-', $row['approver']);
                $data2 = explode('-', $row['prepared_by']);

                if (!empty($data[1])) {
                    $approved_by = $data[1];
                } else {
                    $sql_approver = mysqli_query($con, "SELECT * from users WHERE user_id = '" . $row['approver'] . "'") or die(mysqli_error());
                    $row_approver = mysqli_fetch_array($sql_approver);
                    $approved_by = ucfirst($row_approver['firstname'] . ', ' . $row_approver['lastname']);
                }
                if (!empty($data2[1])) {
                    $preparedby = $data[1];
                } else {
                    $sql_preparedby = mysqli_query($con, "SELECT * from users WHERE user_id = '" . $row['prepared_by'] . "'") or die(mysqli_error());
                    $row_preparedby = mysqli_fetch_array($sql_preparedby);
                    $preparedby = ucfirst($row_preparedby['firstname'] . ', ' . $row_preparedby['lastname']);
                }

                $date = date('Y/m/d', strtotime($row['date']));
                $date_issued = date('Y/m/d', strtotime($row['date_received'])) . '<font size="-2"><br/> at ' . date('h:i A', strtotime($row['date_received'])) . '</font>';
                $prepared_by = $row['prepared_by'];
                echo "<tr>";
                echo "<td id='td'>" . $date . "</td>";
                echo "<td id='td'>" . strtoupper($row['ref_no']) . "</td>";
                echo "<td id='td'>" . number_format($row['amount']) . "</td>";
                echo "<td id='td'>" . strtoupper($row['purpose']) . "</td>";
                echo "<td id='td'>" . $date_issued . "</td>";
                echo "<td id='td'>" . $approved_by . "</td>";
                echo "<td id='td'>" . $preparedby . "</td>";
                echo "</tr>";
            }
            echo "</table>";

            echo "<br/><br/>";
        }
        mysqli_close($con);
        ?>
    </body>
</html>
