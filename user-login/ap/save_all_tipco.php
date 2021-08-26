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
            .submit{
                height: 20px;
                width: 80px;
                font-size: 12px;
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
                <div style="margin-left: 30px; padding: 5px;">
                    <h2>Save TIPCO RECEIVING</h2>
                    <br>
                    <form action="save_all_tipco.php" method="POST">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required> <input class="submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required> <input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>
                </div>
                <?php
                if (isset($_POST['from'])) {
                    echo '<iframe src="iframe/save_all_tipco.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '" width="1190" height="500" scrolling="yes"></iframe>';
                }
                ?>


            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>