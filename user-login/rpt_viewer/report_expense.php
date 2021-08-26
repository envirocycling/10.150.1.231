<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['rpt_viewer_id'])) {
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
        <link rel="shortcut icon" href="images/mycomp.png" />
        <script src="js/pending.js" type="text/javascript"></script>
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
                <?php
                include 'template/menu.php';
                ?>
                <div style="margin-left: 20px;">
                    <br>
                    <h2>EXPENSE REPORT</h2>
                    <br>
                    <form action="report_expense.php" method="POST">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required>';
                            $sql = mysql_query("SELECT * FROM bank_accounts");
                            echo 'Bank : <select name="bank">';
                            echo "<option value=''>All</option>";
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['bank_code'] . '">' . $rs['bank_code'] . '</option>';
                            }
                            echo '<option value="SBC">SBC</option>';
                            echo '</select> ';
                            echo 'Type: <select name="type">
                                            <option value="">All</option>
                                            <option value="supplier">Paper Buying</option>
                                            <option value="others">Others</option>
                                        </select>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required>';
                            $sql = mysql_query("SELECT * FROM bank_accounts");
                            echo 'Bank : <select name="bank">';
                            echo "<option value=''>All</option>";
                            while ($rs = mysql_fetch_array($sql)) {
                                echo '<option value="' . $rs['bank_code'] . '">' . $rs['bank_code'] . '</option>';
                            }
                            echo '<option value="SBC">SBC</option>';
                            echo '</select> ';
                            echo 'Type: <select name="type">
                                            <option value="">All</option>
                                            <option value="supplier">Paper Buying</option>
                                            <option value="others">Others</option>
                                        </select>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>
                    <br>
                    <?php
                    if (isset($_POST['from'])) {
                        echo '<iframe src="iframe/query_report_expense.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '&bank=' . $_POST['bank'] . '&type=' . $_POST['type'] . '" width="1270" height="500" scrolling="yes"></iframe>';
                        echo "<br><br>";
                    } else {
                        echo "No date selected.";
                        echo "<br><br><br><br><br><br><br><br><br><br>";
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