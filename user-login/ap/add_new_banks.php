<?php
include 'config.php';
if (isset ($_POST['submit'])) {
    $sql_check = mysql_query("SELECT count(id) FROM bank_accounts WHERE bank_code='".$_POST['bank']."'");
    $rs_check = mysql_fetch_array($sql_check);
    if ($rs_check['count(id)']==0) {
        mysql_query("INSERT INTO `bank_accounts`(`bank_code`, `description`, `location`, `date`) VALUES ('".$_POST['bank']."','".$_POST['desc']."','".$_POST['location']."','".date("Y/m/d")."')");
        echo "<script>
        location.replace('bank_accounts.php');
        </script>";
    } else {
        echo "<script>
            alert('This Bank already inserted.');
        location.replace('bank_accounts.php');
        </script>";
    }
}
?>
<center>
    <h2>Add new Bank Account</h2>
    <form action="add_new_banks.php" method="POST">
        <table>
            <tr>
                <td align='center'>Bank</td>
                <td align='center'>Description</td>
                <td align='center'>Location</td>
            </tr>
            <tr>
                <td><input type='text' name='bank' value='' required></td>
                <td><input type='text' name='desc' value='' required></td>
                <td><input type='text' name='location' value='' required></td>
            </tr>
            <tr>
                <td colspan="3" align='center'><input type='submit' name='submit' value='Submit'></td>
            </tr>
        </table>
    </form>
</center>