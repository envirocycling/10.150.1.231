<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
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
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
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
                <h2>Delivery Performance Reports</h2>

                <br>
                <form action="delivery_performance_reports.php" method="POST">
                    Deliveries To: <?php
                    echo "<select id='delivered_to' name='delivered_to'>";
                    if ($_POST['submit'] && $_POST['delivered_to'] == 'BOTH') {
                        echo "<option value='BOTH'>TIPCO & MULTIPLY</option>";
                    } else if ($_POST['submit'] && $_POST['delivered_to'] != '') {
                        $sql_dt = mysql_query("SELECT * FROM delivered_to WHERE dt_id LIKE '%" . $_POST['delivered_to'] . "%'");
                        $rs_dt = mysql_fetch_array($sql_dt);
                        echo "<option value='" . $rs_dt['dt_id'] . "'>" . $rs_dt['name'] . "</option>";
                    }
                    echo "<option value=''>All</option>";
                    echo "<option value='BOTH'>TIPCO & MULTIPLY</option>";
                    $sql_dt = mysql_query("SELECT * FROM delivered_to");
                    while ($rs_dt = mysql_fetch_array($sql_dt)) {
                        echo "<option value='" . $rs_dt['dt_id'] . "'>" . $rs_dt['name'] . "</option>";
                    }
                    echo "</select>";
                    ?>
                    Branch: <?php
                    include 'config.php';
                    echo "<select id='branch' name='branch'>";
                    if (isset($_POST['submit']) && $_POST['branch'] != '') {
                        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $_POST['branch'] . "'");
                        $rs_branch = mysql_fetch_array($sql_branch);
                        echo "<option value='" . $rs_branch['branch_id'] . "'>" . $rs_branch['branch_name'] . "</option>";
                    }
                    echo "<option value=''>All</option>";
                    $sql_branch = mysql_query("SELECT * FROM branches");
                    while ($rs_branch = mysql_fetch_array($sql_branch)) {
                        echo "<option value='" . $rs_branch['branch_id'] . "'>" . $rs_branch['branch_name'] . "</option>";
                    }
                    echo "</select>";
                    if (isset($_POST['submit'])) {
                        echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required>';
                    } else {
                        echo 'From: <input class="tcal" type="text" name="from" value="' . date("Y/m/d") . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date("Y/m/d") . '" size="10" required>';
                    }
                    ?>
                    <input type="submit" name='submit' value="Generate Report">
                </form>
                <br>
                <?php
                if (isset($_POST['submit'])) {
                    echo '<iframe src="iframe/query_delivery_performance.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '&branch=' . $_POST['branch'] . '&delivered_to=' . $_POST['delivered_to'] . '" width="1160" height="500" scrolling="yes"></iframe>';
                } else {
                    echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
                }
                ?>
            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>