<?php 

date_default_timezone_set('Asia/Manila');
session_start();
//require_once '/var/www/html/paymentsystem/config/connection.php';

if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}

$types = array('all', 'paid', 'unpaid');

if(isset($_POST['submit'])) {

    $from = $_POST['from'];
    $to = $_POST['to'];
    $type = $_POST['type'];

} else {

    $from = date('Y/m/d');
    $to = date('Y/m/d');
    $type = 'all';

}

$source = "iframe/paper_buying.php?from={$from}&to={$to}&type={$type}";


?>
<!DOCTYPE html>
<html>

    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php
                include 'template/menu.php';
                ?>
                <br>
                <h2><?php echo $type != 'all' ? strtoupper($type): ''; ?> PAPER BUYING</h2>
                        <br>

                        <form method="POST">

                            <label for="from">From: </label>
                            <input class="tcal" type="text" name="from" value="<?php echo $from; ?>" size="10" required id="from">

                            <label for="to">To: </label>
                            <input class="tcal" type="text" name="to" value="<?php echo $to; ?>" size="10" required id="to">

                            <label for="type">Type: </label>
                            <select name="type">
                                <option value="all">----- Please Select Type -----</option>
                                <?php foreach ($types as $_type): ?>
                                <option 
                                value="<?php echo $_type; ?>"
                                <?php echo $_type === $type ? 'selected': ''; ?> ><?php echo ucwords($_type); ?></option>
                                <?php endforeach ?>
                            </select>

                            <input class="submit" type="submit" name="submit" value="Submit">
                        </form>

                        <br>
                        
                        <iframe src="<?php echo $source; ?>" width="100%" height="500" scrolling="yes"></iframe>
            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>

</html>