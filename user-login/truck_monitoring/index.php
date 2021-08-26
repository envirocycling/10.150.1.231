<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['trck_reg_id'])) {
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
        <link rel="stylesheet" href="cbFilter/cbCss.css" />
        <link rel="stylesheet" href="cbFilter/sup.css" />
        <link rel="stylesheet" href="cbFilter/mat.css" />
        <script src="cbFilter/jquery-1.8.3.js"></script>
        <script src="cbFilter/jquery-ui.js"></script>
        <script src="cbFilter/sup_combo.js"></script>
        <script src="cbFilter/mat_combo.js"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
        <style>
            table{
                font-size: 15px;
            }
            td{
                padding-left: 7px;
                padding-right: 7px;
            }
            .td_bold{
                font-weight: bold;
            }
            .blue{
                font-weight: bold;
                background-color: #8ea9db;
            }
            .yellow{
                font-weight: bold;
                background-color: #ffff00;
            }
            .orange{
                font-weight: bold;
                background-color: #ffc000;
            }
            .peach{
                background-color: #fce4d6;
            }
            .grey{
                font-weight: bold;
                background-color: #dbdbdb
            }
            .grey2{
                background-color: #dbdbdb
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

            <div class="middle" align="center">
                <?php
                include 'template/menu.php';
                ?>
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
               
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>