
<div class="refresh">
    <center>PENDING RECEIVING</center>
    <form action="payment.php" method="POST">
        <table border="1" cellspacing="0" width="350">
            <tr>
                <td></td>
                <td>Date</td>
                <td>Priority No.</td>
                <td>Supplier Name</td>
                <!-- <td>Action</td> -->
            </tr>
            <?php
            include '../config.php';
            $trans = "";
            $sql_pending = mysql_query("SELECT * FROM scale_receiving WHERE status='generated'");
            while ($rs_pending = mysql_fetch_array($sql_pending)) {
                $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_pending['supplier_id'] . "'");
                $rs_sup = mysql_fetch_array($sql_sup);
                if (!empty($trans)) {
                    $trans.="_" . $rs_pending['trans_id'];
                } else {
                    $trans.=$rs_pending['trans_id'];
                }
                echo "<tr>";
                echo "<td><input type='checkbox' name='" . $rs_pending['trans_id'] . "' value='pay'></td>";
                echo "<td>" . $rs_pending['date'] . "</td>";
                echo "<td>" . $rs_pending['priority_no'] . "</td>";
                echo "<td>" . $rs_sup['supplier_id'] . "_" . $rs_sup['supplier_name'] . "</td>";
                // echo "<td>&nbsp;<a href='add_receiving.php?trans_id=".$rs_pending['trans_id']."'>Add</a> | <a href='receiving_ticket.php?trans_id=".$rs_pending['trans_id']."'>View</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
</div>
<div class="button">
    <input type="submit" class="submit" name="submit" value="Submit"></button>
</div>
</form>
