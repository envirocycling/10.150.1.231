<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['admin_id'])) {
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
        <script src="js/pending2.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
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
                <div align="center">
                    <br>
                    <h2>Outgoing</h2>
                    <form action="outgoing.php" method="POST">
                        <?php
                        if (isset($_POST['submit'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>


                    <?php
                    if (isset($_POST['submit'])) {
                        echo '<iframe src="iframe/query_outgoing.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '" width="1250" height="500" scrolling="yes"></iframe>';
                    } else {
                        echo '<iframe src="iframe/query_outgoing.php?from=' . date('Y/m/d') . '&to=' . date('Y/m/d') . '" width="1250" height="500" scrolling="yes"></iframe>';
                    }
                    ?>
                </div>

                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>