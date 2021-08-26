<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
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
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <script type="text/javascript" src="./MyMenu1/MyMenu1.js"></script>
    </head>
    <body>

        <div class="wrapper">

            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle" align='center'>
                <?php
                include 'template/menu.php';
                ?>
                <br>

                <h2>Supplier's Price</h2>
                <br>
                <a href="pricing.php">SUPPLIERS</a> | <a href="update_all_prices.php">UPDATE ALL</a>
                <br>
                <iframe src="iframe/update_all_prices.php"  width="600" height="500" scrolling="yes"></iframe>
                <br>
                <br>
                <!--<font color="red">Note: Only suppliers that have price information saved on database will be affected. </font>-->
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>