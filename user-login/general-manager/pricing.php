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
                <form action="pricing.php" method="POST">
                    <b>Branch: </b><select name="branch">
                        <?php
                        include 'config.php';
                        $sql = mysql_query("SELECT * FROM supplier GROUP BY branch");
                        if (isset($_POST['branch'])) {
                            echo '<option value="'.$_POST['branch'].'">'.$_POST['branch'].'</option>';
                        } else {
                            echo '<option value="Pampanga">Pampanga</option>';
                        }
                        echo '<option value="">All Branch</option>';
                        while ($rs = mysql_fetch_array($sql)) {
                            echo '<option value="' . $rs['branch'] . '">' . $rs['branch'] . '</option>';
                        }
                        ?>

                    </select>
                    <input type="submit" name="submit" value="Submit">
                </form>

                <iframe src="iframe/query_sup_prices.php?branch=<?php
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