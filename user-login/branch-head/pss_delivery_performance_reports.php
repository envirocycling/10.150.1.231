<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['bh_id'])) {
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
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php
                include 'template/menu.php';
                ?>
                <br>
                <h2>Delivery Performance Reports(PSS)</h2>

                <br>
                <form action="pss_delivery_performance_reports.php" method="POST">

                    <?php if (isset($_POST['submit'])): ?>
                    From: <input class="tcal" type="text" name="from" value="<?php echo $_POST['from']; ?>" size="10" required>
                    To: <input type="text" class="tcal" name="to" value="<?php echo $_POST['to']; ?>" size="10" required>
                    <?php else: ?>
                    From: <input class="tcal" type="text" name="from" value="<?php echo date('Y/m/d'); ?>" size="10" required> 
                    To: <input type="text" class="tcal" name="to" value="<?php echo date('Y/m/d'); ?>" size="10" required>
                    <?php endif; ?>

                    <input type="submit" name='submit' value="Generate Report">

                </form>
                <br>

                <?php if (isset($_POST['submit'])): ?>
                    <iframe src="iframe/pss_query_delivery_performance.php?from=<?php echo $_POST['from']; ?>&to=<?php echo $_POST['to']; ?>" width="1160" height="500" scrolling="yes"></iframe>
                <?php else: ?>
                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                <?php endif; ?>

            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
