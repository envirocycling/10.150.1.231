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
        <script src="js/pending.js" type="text/javascript"></script>
        <link rel="stylesheet" href="cbFilter/cbCss.css" />
        <link rel="stylesheet" href="cbFilter/sup.css" />
        <link rel="stylesheet" href="cbFilter/mat.css" />
        <link rel="stylesheet" href="cbFilter/wheels.css" />
        <script src="cbFilter/jquery-1.8.3.js"></script>
        <script src="cbFilter/jquery-ui.js"></script>
        <script src="cbFilter/sup_combo.js"></script>
        <script src="cbFilter/mat_combo.js"></script>
        <script src="cbFilter/wheels_combo.js"></script>
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
                <div style="margin-left: 30px; padding: 5px;">
                    <h2>SUPPLIERS</h2>
                    <br>
                    <form action="suppliers.php" method="POST">
                        <b>Branch: </b><select name="branch">
                            <?php
                            include 'config.php';
                            $sql = mysql_query("SELECT * FROM supplier GROUP BY branch");
                            echo '<option value="Pampanga">Pampanga</option>';
                            echo '<option value="">All Branch</option>';
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['branch'] . '">' . $rs['branch'] . '</option>';
                            }
                            ?>

                        </select>
                        <input type="submit" name="submit" value="Submit">
                    </form>
                </div>
                <iframe src="iframe/query_suppliers.php?branch=<?php
                if (isset($_POST['branch'])) {
                    echo $_POST['branch'];
                } else {
                    echo "Pampanga";
                }
                ?>" width="1190" height="500" scrolling="yes"></iframe>

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>