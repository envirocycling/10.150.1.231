<?php
include 'config.php';
if (isset ($_POST['submit'])) {
    $c = 0;
    $from = sprintf("%010s",$_POST['from']);
    $to =sprintf("%010s",$_POST['to']);
    $sql_check = mysql_query("SELECT * FROM cheque_range and bank_code='".$_POST['bank']."'");
    while ($rs_check = mysql_fetch_array($sql_check)) {
        if ($from >= $rs_check['from'] && $from <= $rs_check['to']) {
//            echo $rs_check['from']." >= ".$_POST['from']." && ".$rs_check['to']." <= ".$_POST['from'];
            $c++;
        }
        if ($to >= $rs_check['from'] &&  $to <= $rs_check['to']) {
//            echo $rs_check['from']." >= ".$_POST['to']." && ".$rs_check['to']." <= ".$_POST['to'];
            $c++;
        }
    }
    if ($c > 0) {
        echo "<script>
        alert('Invalid Cheque range has been used.');
        location.replace('check_range.php');
        </script>";
    } else {
        mysql_query("UPDATE cheque_range SET status='issued' WHERE bank_code='".$_POST['bank']."'");
        mysql_query("UPDATE payment SET cheque_status='issued' WHERE bank_code='".$_POST['bank']."'");
        mysql_query("INSERT INTO `cheque_range`(`bank_code`, `from`, `to`, `date`) VALUES ('".$_POST['bank']."','$from','$to','".date("Y/m/d")."')");
        echo "<script>
        location.replace('check_range.php');
        </script>";
    }
}
?>
<center>
    <h2>Add new Cheque Range</h2>

    <form action="add_new_cheque_range.php" method="POST">
        <table>
            <tr>
                <td align='center'>Bank</td>
                <td align='center'>From</td>
                <td align='center'>To</td>
            </tr>
            <tr>
                <td>
                    <?php
                    $sql_bank = mysql_query("SELECT * FROM bank_accounts");
                    echo "<select name='bank'>";
                    echo "<option value=''></option>";
                    while ($rs_bank = mysql_fetch_array($sql_bank)) {
                        echo "<option value='".$rs_bank['bank_code']."'>".$rs_bank['bank_code']."</option>";
                    }
                    echo "</select>";
                    ?></td>
                <td><input type='text' name='from' value='' required></td>
                <td><input type='text' name='to' value='' required></td>
            </tr>
            <tr>
                <td colspan="3" align='center'><input type='submit' name='submit' value='Submit'></td>
            </tr>
        </table>
    </form>
</center>