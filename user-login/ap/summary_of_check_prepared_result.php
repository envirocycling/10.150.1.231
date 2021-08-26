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
                <div style="margin-left: 20px;">
                    <br>
                    <h2>SUMMARY OF CHECK PREPARED</h2>
                    <br>
                    <form action="summary_of_check_prepared_result.php?cheque=<?php echo $_GET['cheque']; ?>" method="POST">
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
//					echo $_GET['cheque'];
//					echo $_POST['from'];
//					echo $_POST['to'];
                    if (isset($_POST['from']) && $_GET['cheque'] == 1) {
                        echo '<iframe src="iframe/query_summary_of_check_prepared.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '&bank=' . $_POST['bank'] . '&type=' . $_POST['type'] . '" width="1160" height="500" scrolling="yes"></iframe>';
                        echo "<br><br>";
                    } else if (isset($_POST['from']) && $_GET['cheque'] == 2) {
                        echo '<iframe src="iframe/query_summary_of_check_prepared_crc.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '&bank=' . $_POST['bank'] . '&type=' . $_POST['type'] . '" width="1160" height="500" scrolling="yes"></iframe>';
                        echo "<br><br>";
                    } else if (isset($_POST['from']) && $_GET['cheque'] == 2.1) {
                        echo '<iframe src="iframe/query_summary_of_check_prepared_crc_1.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '&bank=' . $_POST['bank'] . '&type=' . $_POST['type'] . '" width="1160" height="500" scrolling="yes"></iframe>';
                        echo "<br><br>";
                    } else if (isset($_POST['from']) && $_GET['cheque'] == 3) {
                        echo '<iframe src="iframe/query_summary_of_check_prepared_afr.php?from=' . $_POST['from'] . '&to=' . $_POST['to'] . '&bank=' . $_POST['bank'] . '&type=' . $_POST['type'] . '" width="1160" height="500" scrolling="yes"></iframe>';
                        echo "<br><br>";
                    } else {
                        echo "No date selected.";
                        echo "<br><br><br><br><br><br><br><br><br><br><br><br>";
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