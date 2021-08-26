<style>
    .noti
    {
        font-size: 15px;
        color: white;
        margin-left: 20px;
        margin-top: -38px;
        position: absolute;
        background-image: url(images/noti_1.png);
        background-position: center;
        height: 22px;
        width: 23px;
    }
    .font
    {
        font-size: 15px;
        color: white;
        position: absolute;
        margin-top: 0px;
        margin-left: 7px;
    }
    .noti2
    {
        font-size: 15px;
        color: white;
        margin-left: 20px;
        margin-top: -38px;
        position: absolute;
        background-image: url(images/noti_1.png);
        background-position: center;
        height: 22px;
        width: 23px;
    }
    .font2
    {
        font-size: 15px;
        color: white;
        position: absolute;
        margin-top: 0px;
        margin-left: 3px;
    }
</style>
<?php
session_start();
include 'config.php';
$initial = $_SESSION['initial'];
if ($_SESSION['username']!='lorna_regala') {
    if ($_SESSION['username']=='lonlon') {
        $sql = mysql_query("SELECT * FROM  supplier_details WHERE verified = '0'");
    } else {
        $sql = mysql_query("SELECT * FROM  supplier_details WHERE verified = '0' and bh_to_verified='$initial'");
    }
    if ($_SESSION['username']=='lonlon') {
        $sql2 = mysql_query("SELECT * FROM supplier_details INNER JOIN sup_transfer ON supplier_details.supplier_id = sup_transfer.supplier_id WHERE  sup_transfer.confirm = '0'");
    } else {
        $sql2 = mysql_query("SELECT * FROM supplier_details INNER JOIN sup_transfer ON supplier_details.supplier_id = sup_transfer.supplier_id WHERE  sup_transfer.confirm = '0' and sup_transfer.bh_to_verified='$initial'");
    }
    $count = mysql_num_rows($sql);
    $count2 = mysql_num_rows($sql2);
    $total = $count+$count2;
    if ($total > '0') {
        if ($count < '10') {
            echo "<div class='noti'>";
            echo "<div class='font'>";
            echo $total;
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='noti2'>";
            echo "<div class='font2'>";
            echo $total;
            echo "</div>";
            echo "</div>";
        }
    }
} else {
    $sql = mysql_query("SELECT * FROM pricing_against_competitors WHERE approved_status=''");
    $count = mysql_num_rows($sql);

    $sql2 = mysql_query("SELECT * FROM incentive_scheme WHERE confirm='0'");
    $count2 = mysql_num_rows($sql2);

    $total = $count+$count2;
    if ($total > '0') {
        if ($count < '10') {
            echo "<div class='noti2'>";
            echo "<div class='font'>";
            echo $total;
            echo "</div>";
            echo "</div>";
        } else {
            echo "<div class='noti2'>";
            echo "<div class='font2'>";
            echo $total;
            echo "</div>";
            echo "</div>";
        }
    }
}

$sql = mysql_query("SELECT * FROM supplier_details WHERE verified = '0'");
while($rs=mysql_fetch_array($sql)) {
    if ($rs['bh_in_charge'] == $rs['bh_to_verified']){
        mysql_query("UPDATE supplier_details SET verified='1' WHERE supplier_id='".$rs['supplier_id']."'");
    }
}
?>