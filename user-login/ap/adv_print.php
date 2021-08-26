<script>
    print();
</script>
<?php
include 'config.php';
$sql_com = mysql_query("SELECT * FROM company WHERE id='1'");
$rs_com = mysql_fetch_array($sql_com);

$sql_adv = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
$rs_adv = mysql_fetch_array($sql_adv);

$sql_acty = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_adv['acty_id'] . "'");
$rs_acty = mysql_fetch_array($sql_acty);

$sql_acpty = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_adv['acpty_id'] . "'");
$rs_acpty = mysql_fetch_array($sql_acpty);

function getInitial($user_id) {
    $sql = mysql_query("SELECT * FROM users WHERE user_id='$user_id'");
    $rs = mysql_fetch_array($sql);
    return $rs['initial'];
}

function getSig($initial, $status) {

    if ($initial != '') {
        $file = '../../signatures_pamp/' . $initial . '.jpg';

        if (file_exists($file)) {
            return '<img src="' . $file . '">';
        } else {
            return "$status But No Signature <br>Uploaded.";
        }
    }
}

function getSigBranch($initial, $status, $branch_id) {

    $sql_code = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
    $rs_code = mysql_fetch_array($sql_code);

    if ($initial != '') {
        $file = '../../signatures_branch/' . $rs_code['code'] . '_' . $initial . '.jpg';

        if (file_exists($file)) {
            return '<img src="' . $file . '">';
        } else {
            return "$status But No Signature <br>Uploaded.";
        }
    }
}

function convertDate($date) {
    if ($date != "0000-00-00 00:00:00") {
        return date("Y-m-d h:i a", strtotime($date));
    }
}
?>
<style>
    div{
        letter-spacing: 2px;
        font-family: Arial;
        font-weight: bold;
    }
    table {
        letter-spacing: 2px;
        font-size: 15px;
        width: 600px;
        padding-top: 10px;
        padding-bottom: 10px;
    }
    table .undeline{
        border-bottom: 1px solid black;
    }
    table .undelineBig{
        border-bottom: 1px solid black;
        padding-bottom: 5px;
    }
    img{
        height: 70px;
    }
    hr{
        border-width: 1px;
        border-color: black;
    }

</style>
<div align="center">    
    <?php echo $rs_com['company']; 
	$sql_supp = mysql_query("SELECT * from supplier WHERE id='".$rs_adv['supplier_id']."'") or die(mysql_error());
	$row_supp = mysql_fetch_array($sql_supp);
?>
    <br>
    SUPPLIER ADVANCES
    <hr>
    <table>
        <tr>
            <td>Date:</td>
            <td class="undeline"><?php echo date("Y-m-d", strtotime($rs_adv['date'])); ?></td>
            <td>Ref No:</td>
            <td class="undeline"><?php echo $rs_adv['ac_no']; ?></td>
        </tr>
        <tr>
            <td>Supplier Name:</td>
            <td class="undeline"><?php echo $row_supp['supplier_name']; ?></td>
            <td>Type:</td>
            <td class="undeline"><?php echo $rs_acty['name']; ?></td>
        </tr>
        <tr>
            <td>Amount:</td>
            <td class="undeline"><?php echo number_format($rs_adv['amount'], 2); ?></td>
            <td>Issuance Type:</td>
            <td class="undeline"><?php echo $rs_acpty['name']; ?></td>
        </tr>
        <tr>
            <td>Justification</td>
            <td colspan="3" class="undelineBig"><?php echo $rs_adv['justification']; ?></td>
        </tr>
        <tr>
            <td>Terms</td>
            <td colspan="3" class="undelineBig"><?php echo $rs_adv['terms']; ?></td>
        </tr>
    </table>

    <hr>
    <?php
    if ($rs_adv['branch_id'] == '7') {
        ?>
        <table>
            <tr>
                <td>
                    <?php echo getSig(getInitial($rs_adv['user_id']), 'Prepared'); ?>
                    <br>

                </td>
                <td>
                    <?php echo getSig(getInitial($rs_adv['verified_id']), 'Verified'); ?>
                    <br>

                </td>
                <td>
                    <?php echo getSig(getInitial($rs_adv['approved_id']), 'Approved'); ?>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    Prepared By: <?php echo getInitial($rs_adv['user_id']); ?>
                    <br>
                    <?php echo convertDate($rs_adv['date']); ?>
                </td>
                <td>
                    Verified By: <?php echo getInitial($rs_adv['verified_id']); ?>
                    <br>
                    <?php echo convertDate($rs_adv['verified_date']); ?>
                </td>
                <td>
                    Approved By: <?php echo getInitial($rs_adv['approved_id']); ?>
                    <br>
                    <?php echo convertDate($rs_adv['approved_date']); ?>
                </td>
            </tr>
        </table>
        <?php
    } else {
        ?>
        <table>
            <tr>
                <td>
                    <?php echo getSigBranch($rs_adv['branch_user'], 'Prepared', $rs_adv['branch_id']); ?>
                    <br>

                </td>
                <td>
                    <?php echo getSigBranch($rs_adv['branch_verifier'], 'Verified', $rs_adv['branch_id']); ?>
                    <br>

                </td>
                <td>
                    <?php echo getSig(getInitial($rs_adv['approved_id']), 'Approved'); ?>
                    <br>
                </td>
            </tr>
            <tr>
                <td>
                    Prepared By: <?php echo $rs_adv['branch_user']; ?>
                    <br>
                    <?php echo convertDate($rs_adv['date']); ?>
                </td>
                <td>
                    Verified By: <?php echo $rs_adv['branch_verifier']; ?>
                    <br>
                    <?php echo convertDate($rs_adv['verified_date']); ?>
                </td>
                <td>
                    Approved By: <?php echo getInitial($rs_adv['approved_id']); ?>
                    <br>
                    <?php echo convertDate($rs_adv['approved_date']); ?>
                </td>
            </tr>
        </table>
        <?php
    }
    ?>
</div>
