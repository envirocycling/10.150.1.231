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
        <link rel="shortcut icon" href="images/tipco.png" />
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
    <body >
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
                <br><br><br><center>
                    <h2>System Restore</h2>
                </center>
                <div>
                    <center>
                        <iframe src="http://ims.efi.net.ph/restore/data_restore_ims.php?branch=" frameborder="0" height="500px" width="100%"></iframe>
                    </center>
                </div>

                <br>
            </div><!-- .middle-->
        </center>
        <footer class="footer">
            <?php include 'template/footer.php'; ?>
        </footer><!-- .footer -->

    </div><!-- .wrapper -->

</body>
</html>