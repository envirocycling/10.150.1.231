<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}
if (isset($_POST['submit2'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $new_pass2 = $_POST['new_pass2'];
    if ($_SESSION['password'] == $new_pass) {
        echo "<script>
            alert('Please choose another password.');
            </script>";
    } else {
        if ($_SESSION['password'] == $old_pass) {
            if ($new_pass == $new_pass2) {
                mysql_query("UPDATE users SET password='$new_pass' WHERE user_id='" . $_SESSION['user_id'] . "'");
                $_SESSION['password'] = $new_pass;
                echo "<script>
            alert('Successfully Updated.');
            </script>";
            } else {
                echo "<script>
            alert('Password didn't match.);
            </script>";
            }
        } else {
            echo "<script>
        alert('Password didn't match.);
        </script>";
        }
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
        <style>
            input{
                font-size: 20px;
                width: 200px;
                height: 30px;
            }
            .submit {
                width: 80px;
                height: 30px;
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
                <div style="margin-left: 30px;">
                    <br>
                    <br>
                    <h2>CHANGE PASSWORD</h2>
                    <form action="initial_settings.php" method="POST">
                        <table>
                            <tr>
                                <td>Old Password: </td>
                                <td><input type="password" name="old_pass" value=""></td>
                            </tr>
                            <tr>
                                <td>New Password: </td>
                                <td><input type="password" name="new_pass" value=""></td>
                            </tr>
                            <tr>
                                <td>Re-enter Password: </td>
                                <td><input type="password" name="new_pass2" value=""></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"><input class="submit" type="submit" name="submit2" value="Submit"></td>
                            </tr>
                        </table>
                    </form>
                    <br>
                </div>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>