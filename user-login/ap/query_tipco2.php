<?php session_start();

date_default_timezone_set("Asia/Manila");

if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}

if(isset($_POST['submit'])) {

    $from = $_POST['from'];
    $to = $_POST['to'];

} else {

    $from = date('Y/m/d');
    $to = date('Y/m/d');

}

?>

<!DOCTYPE html>

<html>
    <head>
        <?php include 'template/layout.php'; ?>
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
            <?php include 'template/header.php'; ?>
            </header>

            <div class="middle">

                <?php include 'template/menu.php'; ?>

                <div style="margin-left: 30px; padding: 5px;">

                    <h2>TIPCO RECEIVING</h2>

                    <br>

                    <form action="query_tipco2.php" method="POST">

                        <label>From: </label>
                        <input class="tcal" type="text" name="from" value="<?php echo $from?>" size="10" required>

                        <label>To: </label>
                        <input class="tcal" type="text" name="to" value="<?php echo $to?>" size="10" required>

                        <input class="submit" type="submit" name="submit" value="Submit">
                    </form> 

                </div>

                <iframe src=<?php echo "iframe/query_tipco2.php?from={$from}&to={$to}" ?> width="1190" height="500" scrolling="yes"></iframe>


            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>