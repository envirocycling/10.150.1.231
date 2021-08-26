<?php
include 'config.php';
if (isset($_POST['submit'])) {
    mysql_query("UPDATE `cheque_range` SET `bank_code`='" . $_POST['bank'] . "',`from`='" . $_POST['from'] . "',`to`='" . $_POST['to'] . "',`status`='" . $_POST['status'] . "' WHERE id='" . $_POST['id'] . "'");
    echo "<script>
        location.replace('check_range.php');
        </script>";
}
$sql_cheque = mysql_query("SELECT * FROM cheque_range WHERE id='" . $_GET['id'] . "'");
$rs_cheque = mysql_fetch_array($sql_cheque);
?>
<center>
    <h2>Edit Bank Account</h2>
    <form action="../edit_cheque_range.php" method="POST">
        <table>
            <tr>
                <td align='center'>Bank</td>
                <td align='center'>Description</td>
                <td align='center'>Location</td>
                <td align='center'>Status</td>
            </tr>
            <tr>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <td>
                <?php
                $sql_bank = mysql_query("SELECT * FROM bank_accounts");
                echo "<select name='bank' required>";
                echo "<option value='" . $rs_cheque['bank_code'] . "'>" . $rs_cheque['bank_code'] . "</option>";
                while ($rs_bank = mysql_fetch_array($sql_bank)) {
                    echo "<option value='" . $rs_bank['bank_code'] . "'>" . $rs_bank['bank_code'] . "</option>";
                }
                echo "</select>";
                ?>
            </td>
            <td><input type='text' name='from' value='<?php echo $rs_cheque['from']; ?>' required></td>
            <td><input type='text' name='to' value='<?php echo $rs_cheque['to']; ?>' required></td>
            <td><select name="status" required>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                </select></td>
            </tr>
            <tr>
                <td colspan="3" align='center'><input type='submit' name='submit' value='Submit'></td>
            </tr>
        </table>
    </form>
</center>