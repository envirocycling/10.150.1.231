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
                        <h2>SUMMARY OF CHECK PREPARED</h2>
                        <br>
                        <form action="summary_of_check_prepared_result.php?cheque=<?php echo $_GET['cheque']; ?>" method="POST"  target="_blank">
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
											<option value="cancelled">Cancelled</option>
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
											<option value="cancelled">Cancelled</option>
                                        </select>
                                        <input class="submit" type="submit" name="submit" value="Submit">';
                            }
                            ?>
                        </form>
                        <br>
                        <br>
                        <br>
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