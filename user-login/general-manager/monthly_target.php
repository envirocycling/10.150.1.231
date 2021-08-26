<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}

if (isset($_POST['submit'])) {
    $sql_mat = mysql_query("SELECT * FROM material");
    while ($rs_mat = mysql_fetch_array($sql_mat)) {

        mysql_query("INSERT INTO `monthly_target`(`month`, `branch_id`, `material_id`, `target`)
               VALUES ('" . $_POST['month'] . "','" . $_POST['branch'] . "','" . $rs_mat['material_id'] . "','" . $_POST['mat_' . $rs_mat['material_id']] . "')");

        echo "<script>";
        echo "alert('Successfully');";
        echo "location.replace('monthly_target.php');";
        echo "</script>";
    }
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
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <script type="text/javascript" src="./MyMenu1/MyMenu1.js"></script>
    </head>
    <style>
        .target_form{
            font-size: 15px;
        }
        select{
            width: 150px;
        }
    </style>
    <body>

        <div class="wrapper">

            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header><!-- .header-->
            <div class="middle" align='center'>
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <h2>ENCODE MONTHLY TARGET</h2>
                <br>
                <form action="monthly_target.php" method="POST">
                    <table class="target_form">
                        <tr>
                            <td>Month :</td>
                            <td>
                                <select name="month">
                                    <?php
                                    $month_now = date("Y/m/d");
                                    $month_prev = date('Y/m/d', strtotime("-1 month", strtotime($month_now)));
                                    $month_12mo = date('Y/m/d', strtotime("+12 months", strtotime($month_now)));
                                    echo "<option value='" . date("Y/m", strtotime($month_now)) . "'>" . date("F Y", strtotime($month_now)) . "</option>";
                                    echo "<option value='" . date("Y/m", strtotime($month_prev)) . "'>" . date("F Y", strtotime($month_prev)) . "</option>";
                                    $month_q = $month_now;
                                    while ($month_q <= $month_12mo) {
                                        echo "<option value='" . date("Y/m", strtotime($month_q)) . "'>" . date("F Y", strtotime($month_q)) . "</option>";
                                        $month_q = date('Y/m/d', strtotime("+1 month", strtotime($month_q)));
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Branch: </td>
                            <td>
                                <select name="branch">
                                    <?php
                                    $sql_branch = mysql_query("SELECT * FROM branches");
                                    while ($rs_branch = mysql_fetch_array($sql_branch)) {
                                        echo "<option value = '" . $rs_branch['branch_id'] . "'>" . $rs_branch['branch_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <?php
                        $sql_mat = mysql_query("SELECT * FROM material");
                        while ($rs_mat = mysql_fetch_array($sql_mat)) {
                            ?>
                            <tr>
                                <td><?php echo $rs_mat['code']; ?>: </td>
                                <td><input type="text" name="mat_<?php echo $rs_mat['material_id']; ?>" value=""></td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td>
                        </tr>
                    </table>
                </form>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>