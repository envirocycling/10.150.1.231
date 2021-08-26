<?php
date_default_timezone_set("Asia/Singapore");
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
                height: 30px;
                width: 100px;
                font-size: 15px;
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
                <div style="margin-left: 10px;">
                    <br>
                    <h2>PAPER BUYING</h2>
                    <br>
                    <form action="paper_buying_result.php" method="POST">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>
                    <br>
                    <?php
                    if (isset($_POST['from'])) {
                        echo '<iframe src="iframe/query_paper_buying.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] .'" width="1180" height="500" scrolling="yes"></iframe>';
                      //  echo "<a href='export/export_paper_buying_to_xls.php?from=" . $_POST['from'] . "&to=" . $_POST['to'] . "'><button class='submit'>Export XLS</button></a>";
                        echo "&nbsp;&nbsp;";
                       // echo "<a href='export/export_paper_buying_to_ims.php?from=" . $_POST['from'] . "&to=" . $_POST['to'] . "'><button class='submit'>Export IMS</button></a>";
                        echo "<br><br>";
                    } else {
                        echo "No date selected.";
                    }
                    ?>
                </div>

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>