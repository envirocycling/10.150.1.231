<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
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
        <link rel="shortcut icon" href="images/ts_logo.png" />
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
        <script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
        <script src="js/setup.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                setupLeftMenu();
                $('.datatable').dataTable();
                setSidebarHeight();
            });
            </script>
    </head>
    <style>
        .client{
            font-size: 15px;
            font-weight: 700;
            color: blue;
            font-style: italic;
        }
        .client:hover{
            cursor: pointer;
            color: black;
            font-weight: 900;
            font-size: 16px;
        }
    </style>
    <script>
        function f_client(){
            window.open('price_client_add.php', 'mywindow', 'width=500,height=300,left=400,top=150');
        }
    </script>
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
                    <h2>CLIENTS</h2>
                    <br>
                    <!--<form method="POST">
                       <b>Branch: </b><select name="branch">
                            <?php
                            include 'config.php';
                            $sql_client = mysql_query("SELECT * FROM client");
                            ?>

                        </select>
                        <input type="submit" name="submit" value="Submit">
                    </form>-->
                </div>
                <form action="iframe/query_price_client_view.php" method="POST" target="fr_client">
                        <?php
                        if (isset($_POST['from'])) {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . $_POST['from'] . '" size="10" required> ';
                            echo 'To: <input type="text" class="tcal" name="to" value="' . $_POST['to'] . '" size="10" required> ';
                            echo 'Status: <select name="client">';
                            if ($_POST['status'] != '') {
                                $sql_clientSet = mysql_query("SELECT * FROM client WHERE cid='".$_POST['client']."'");
                                $row_clintSet = mysql_fetch_array($sql_clientSet);
                                echo '<option value="' . $_POST['client'] . '">' . strtoupper($row_clintSet['client_name']) . '</option>';
                            }
                            while($row_client = mysql_fetch_array($sql_client)){
                                 echo '<option value="' . $row_client['cid'] . '">' . strtoupper($row_client['client_name']) . '</option>';
                            }
                        } else {
                            echo 'From: <input class="tcal" type="text" name="from" value="' . date('Y/m/d') . '" size="10" required> ';
                            echo 'To: <input type="text" class="tcal" name="to" value="' . date('Y/m/d') . '" size="10" required> ';
                            echo 'Status: <select name="client">';
                            while($row_client = mysql_fetch_array($sql_client)){
                                 echo '<option value="' . $row_client['cid'] . '">' . strtoupper($row_client['client_name']) . '</option>';
                            }
                            echo '</select>&nbsp;&nbsp;&nbsp;';
                            echo '<input class="submit" type="submit" name="submit" value="Submit">';
                        }
                        ?>
                    </form>
                <br>
                <iframe src="iframe/query_price_client_view.php" width="1190" height="500" scrolling="yes" name="fr_client"></iframe>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>