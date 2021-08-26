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
                        <h2>PAID DIGI PAYMENTS</h2>
                        <br>
                        <form action="payment_paid_digibanker.php" method="POST">
                            <?php
                            if (isset($_POST['from'])) {
                                echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                                echo '<iframe src="iframe/payment_paid_digibanker.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '" width="750" height="500" scrolling="yes"></iframe>';
                            } else {
                                echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                                echo '<iframe src="iframe/payment_paid_digibanker.php?from=' . date('Y/m/d') . '&to=' . date('Y/m/d') . '" width="750" height="500" scrolling="yes"></iframe>';
                            }
                            ?>
                        </form>
                        <br>

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