<?php
include 'config.php';
if (isset($_POST['submit'])) {
    mysql_query("UPDATE bank_accounts SET bank_code='" . $_POST['bank'] . "',description='" . $_POST['desc'] . "',location='" . $_POST['location'] . "' WHERE id='" . $_POST['id'] . "'");

    mysql_query("UPDATE payment SET bank_code='" . $_POST['bank'] . "' WHERE bank_code='" . $_POST['prev_bank'] . "'");
    
    mysql_query("UPDATE cheque_range SET bank_code='" . $_POST['bank'] . "' WHERE bank_code='" . $_POST['prev_bank'] . "'");
    echo "<script>
        location.replace('bank_accounts.php');
        </script>";
}
$sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE id='" . $_GET['id'] . "'");
$rs_bank = mysql_fetch_array($sql_bank);
?>
<center>
    <h2>Edit Bank Account</h2>
    <form action="../edit_bank.php" method="POST">
        <table>
            <tr>
                <td align='center'>Bank</td>
                <td align='center'>Description</td>
                <td align='center'>Location</td>
            </tr>
            <tr>
            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
            <td><input type='hidden' name='prev_bank' value='<?php echo $rs_bank['bank_code']; ?>' required><input type='text' name='bank' value='<?php echo $rs_bank['bank_code']; ?>' required></td>
            <td><input type='text' name='desc' value='<?php echo $rs_bank['description']; ?>' required></td>
            <td><input type='text' name='location' value='<?php echo $rs_bank['location']; ?>' required></td>
            </tr>
            <tr>
                <td colspan="3" align='center'><input type='submit' name='submit' value='Submit'></td>
            </tr>
        </table>
    </form>
</center>