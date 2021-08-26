<?php

date_default_timezone_set("Asia/Singapore");
session_start();


require_once './../../config/query_builder.php';


if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}

if(isset($_POST['submit'])) {

    $branch_id = $_POST['branch'];
    $from = $_POST['from'];
    $to = $_POST['to'];

} else {

    $branch_id = 2;
    $from = date('Y/m/d');
    $to = date('Y/m/d');

}

$branches = fetch("SELECT * FROM `branches` WHERE status != 'inactive' and branch_id != 7;", null);


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
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php include 'template/menu.php'; ?>
                <br>

                <h2>Branch Reports</h2>

                <br>

                <form method="POST">

                    <label>Branch: </label>
                    <select name='branch'>
                        <?php foreach ($branches as $branch): ?>
                        <option value="<?php echo $branch->branch_id ?>" <?php echo $branch_id == $branch->branch_id ? 'selected' : '' ?>>
                            <?php echo $branch->branch_name?>
                        </option>
                        <?php endforeach ?>
                    </select>

                    <label>From: </label>
                    <input class="tcal" type="text" name="from" value="<?php echo $from?>" size="10" required>

                    <label>To: </label>
                    <input class="tcal" type="text" name="to" value="<?php echo $to?>" size="10" required>

                    <input type="submit" name='submit' value="Generate Report">
                
                </form>

                <br>

                <?php $params = "from={$from}&to={$to}&branch_id={$branch_id}"; ?>
                <iframe src="branch_reports/branch_receiving.php?<?php echo $params; ?>" width="1190" height="500" scrolling="yes"></iframe>



            </div><!--.middle-->

            <footer class = "footer">
            <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
