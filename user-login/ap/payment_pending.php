<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
        <style>
            .button{
                padding: 5px;
                text-align: right;
            }
            .submit{
                height: 20px;
                width: 70px;
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

                <div class="container">
                    <main class="content">
                        <div style="margin-left: -11px;" width="1200">
                            <?php
                            include 'template/menu.php';
                            ?>
                        </div>
                        <br>
                        <h2>PENDING PAYMENTS</h2>
                        <br>
                        <a href="payment_paid.php">CHEQUE</a> | <a href="payment_paid_digibanker.php">DIGIBANKER</a> | <a href="payment_pending.php">PENDING</a>
                        <br>
                        <br>
                        <iframe src="iframe/pending_to_pay.php" width="750" height="500" scrolling="yes"></iframe>
                        <br>
                        <!-- <iframe src="iframe/paid_payments.php" width="750" height="500" scrolling="yes"></iframe>-->
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="600" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>