<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
    echo "<script>location.replace('../../');</script>";
}
if (isset($_GET['manual'])) {
    mysql_query("UPDATE w_manual SET manual='" . $_GET['manual'] . "' WHERE manual_id='1'");
}
if (isset($_GET['online'])) {
    mysql_query("UPDATE p_online SET online='" . $_GET['online'] . "' WHERE online_id='1'");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
        <title>Envirocycling Fiber Inc.</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <link href="css/style.css" rel="stylesheet">
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <script src="js/pending2.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
        <style>
            button{
                height: 25px;
                width: 80px;
            }
            .button{
                height: 25px;
                width: 80px;
            }
            input{
                height: 20px;
                width: 170px;
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle">
                <?php
                include 'template/menu.php';
                ?>
                <!--                <div id="MyMenu1" style="margin-left: 0px;"></div>-->
                <br>
                <h2>System Settings</h2>
                <div style="padding: 50px;">
                    <?php
                    $sql_manual = mysql_query("SELECT * FROM w_manual WHERE manual_id='1'");
                    $rs_manual = mysql_fetch_array($sql_manual);

                    echo "<font size='3'>The Manual entering weight " . $rs_manual['manual'] . ".</font>";
                    echo "<br><br>";

                    if ($rs_manual['manual'] == 'on') {
                        echo "<a href='system_settings.php?manual=off'><button class='submit'>Turn Off</button></a>";
                    } else {
                        echo "<a href='system_settings.php?manual=on'><button class='submit'>Turn On</button></a>";
                    }
                    ?>
                    <br><br>
                    <h2>PAYMENT</h2>
                    <br>
                    <?php
//
                    $sql_online = mysql_query("SELECT * FROM p_online WHERE online_id='1'");
                    $rs_online = mysql_fetch_array($sql_online);

                    echo "<font size='3'>Default Cheque payment is " . $rs_online['online'] . ".</font>";
                    echo "<br><br>";

                    if ($rs_online['online'] == 'on') {
                        echo "<a href='system_settings.php?online=off'><button class='submit'>Turn Off</button></a>";
                    } else {
                        echo "<a href='system_settings.php?online=on'><button class='submit'>Turn On</button></a>";
                    }
                    ?>
                    <br><br>
                    <h2>Change Supervisor Code</h2>
                    <form method="POST" action="change_supervisor_code.php">
                        <table>
                            <tr>
                                <td><font size='3'>Current: </font></td>
                                <td><input type="password" name="cur_pass" value=""></td>
                            </tr>
                            <tr>
                                <td><font size='3'>New: </font></td>
                                <td><input type="password" name="new_pass" value=""></td>
                            </tr>
                            <tr>
                                <td><font size='3'>Re-Type: </font></td>
                                <td><input type="password" name="new_pass2" value=""></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><input class="button" type="submit" name="submit" value="Update"></td>
                            </tr>
                        </table>
                    </form>
                </div>

                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>