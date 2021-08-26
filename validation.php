<?php
@session_start();
include 'config.php';
$username = $_POST['username'];
$password = $_POST['password'];
$sql = mysql_query("SELECT * FROM users WHERE username='$username' and password='$password'");
$check = mysql_num_rows($sql);
$rs = mysql_fetch_array($sql);
if ($check < 1) {
    echo "<script>";
    echo "location.replace('index.php?error=1');";
    echo "</script>";
} else if ($rs['status'] == 'deleted') {
    echo "<script>";
    echo "location.replace('index.php?error=1');";
    echo "</script>";
} else {
    $_SESSION['user_id'] = $rs['user_id'];
    $_SESSION['username'] = $rs['username'];
    $_SESSION['password'] = $rs['password'];
    $_SESSION['initial'] = $rs['initial'];
    $_SESSION['firstname'] = $rs['firstname'];
    $_SESSION['lastname'] = $rs['lastname'];
    $_SESSION['branch'] = $rs['branch'];
    $_SESSION['user_type'] = $rs['usertype'];
    if ($_SESSION['user_type'] == '1') {
        $_SESSION['admin_id'] = $rs['user_id'];
        echo "<script>";
        echo "location.replace('user-login/admin/');";
        echo "</script>";
    }
    if ($_SESSION['user_type'] == '2') {
        $_SESSION['bh_id'] = $rs['user_id'];
        echo "<script>";
        echo "location.replace('user-login/branch-head/');";
        echo "</script>";
    }
    if ($_SESSION['user_type'] == '3') {
        $_SESSION['ic_id'] = $rs['user_id'];
        $_SESSION['class'] = $rs['class'];
        echo "<script>";
        echo "location.replace('user-login/ic/index.php');";
        echo "</script>";
    }
    if ($_SESSION['user_type'] == '4') {
        $_SESSION['ap_id'] = $rs['user_id'];
        $_SESSION['class'] = $rs['class'];
        echo "<script>";
        echo "location.replace('user-login/ap/initial_settings.php');";
        echo "</script>";
    }
    if ($_SESSION['user_type'] == '5') {
        $_SESSION['rpt_viewer_id'] = $rs['user_id'];
        echo "<script>";
        echo "location.replace('user-login/rpt_viewer/index.php');";
        echo "</script>";
    }
    if ($_SESSION['user_type'] == '6') {
        $_SESSION['gm_id'] = $rs['user_id'];
        echo "<script>";
        echo "location.replace('user-login/general-manager/index.php');";
        echo "</script>";
    }if ($_SESSION['user_type'] == '7') {
        $_SESSION['trck_reg_id'] = $rs['user_id'];
        echo "<script>";
        echo "location.replace('user-login/truck_monitoring/index.php');";
        echo "</script>";
    }
    if ($_SESSION['user_type'] == '8') {
        $_SESSION['coop_id'] = $rs['user_id'];
        echo "<script>";
        echo "location.replace('user-login/coop/index.php');";
        echo "</script>";
    }
}
?>