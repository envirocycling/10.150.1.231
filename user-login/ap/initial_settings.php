<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}
if (isset($_POST['submit'])) {
    if ($_POST['trade_verifier'] == '' || $_POST['trade_signatory'] == '' || $_POST['nontrade_verifier'] == '' || $_POST['nontrade_signatory'] == '') {
        echo "<script>
        alert('Error.');
        </script>";
    } else {
        $_SESSION['trade_verifier'] = $_POST['trade_verifier']; //RJ
        $_SESSION['trade_signatory'] = $_POST['trade_signatory']; //RJ
        $_SESSION['nontrade_verifier'] = $_POST['nontrade_verifier']; //RJ
        $_SESSION['nontrade_signatory'] = $_POST['nontrade_signatory']; //RJ
        echo "<script>
        alert('Successfully Set.');
        </script>";
    }
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
        <?php
        include 'template/layout.php';
        ?>
        <style>
            .button{
                padding: 5px;
                text-align: right;
            }
            .submit{
                height: 20px;
                width: 70px;
            }
            select{
                font-size: 20px;
                width: 150px;
                height: 30px;
            }
            .input{
                font-size: 20px;
                width: 100px;
                height: 30px;
            }
            .input2{
                font-size: 20px;
                width: 200px;
                height: 30px;
            }
            .submit {
                width: 80px;
                height: 30px;
            }
            .sig{
                height: 70px;
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
                        <br><br>
                        <h1>Settings</h1>
                        <br>
                        <!---RJ start-->
                        <form action="initial_settings.php" method="POST">
                            <table width="80%">
                                <tr>
                                    <td colspan="2" align="center"><u><i><h2>Trade</h2></u></i><br></td>
                                    <td colspan="2" align="center"><u><i><h2>Non-Trade</h2></u></i><br></td>
                                </tr>
                                <tr>
                                    <td><h2>Verifier:</h2></td>
                                    <td>
                                        <?php
                                        echo "<select name='trade_verifier'>";
                                        if (isset($_SESSION['trade_verifier'])) {
                                            $sql_sic = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['trade_verifier'] . "' and status!='deleted' and usertype!='1'");
                                            $rs_sic = mysql_fetch_array($sql_sic);
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        } else {
                                            echo "<option value='50'>ACRivera</option>";
                                            $tv_def = "<i><font size='-1'>default</font></i>";
                                        }
                                        $sql_sic = mysql_query("SELECT * FROM users WHERE user_id!='" . $_SESSION['user_id'] . "' and status!='deleted' and usertype!='1'");
                                        while ($rs_sic = mysql_fetch_array($sql_sic)) {
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        }
                                        echo "</select>".$tv_def;
                                        ?>
                                     </td>
                                     <td><h2>Verifier:</h2></td>
                                    <td>
                                        <?php
                                        echo "<select name='nontrade_verifier'>";
                                        if (isset($_SESSION['nontrade_verifier'])) {
                                            $sql_sic = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['nontrade_verifier'] . "' and status!='deleted' and usertype!='1'");
                                            $rs_sic = mysql_fetch_array($sql_sic);
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        } else {
                                            echo "<option value='50'>ACRivera</option>";
                                            $ntv_def = "<i><font size='-1'>default</font></i>";
                                        }
                                        $sql_sic = mysql_query("SELECT * FROM users WHERE user_id!='" . $_SESSION['user_id'] . "' and status!='deleted' and usertype!='1'");
                                        while ($rs_sic = mysql_fetch_array($sql_sic)) {
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        }
                                        echo "</select>".$ntv_def;
                                        ?>
                                     </td>
                                </tr>
                                <tr>
                                    <td><h2>Approver: </h2></td>
                                    <td>
                                        <?php
                                        echo "<select name='trade_signatory'>";
                                        if (isset($_SESSION['trade_signatory'])) {
                                            $sql_sic = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['trade_signatory'] . "' and status!='deleted' and usertype!='1'");
                                            $rs_sic = mysql_fetch_array($sql_sic);
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        } else {
                                            echo "<option value='1'>EAAguinza</option>";
                                            $ta_def = "<i><font size='-1'>default</font></i>";
                                        }
                                        $sql_sic = mysql_query("SELECT * FROM users WHERE user_id!='" . $_SESSION['user_id'] . "' and status!='deleted' and usertype!='1'");
                                        while ($rs_sic = mysql_fetch_array($sql_sic)) {
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        }
                                        echo "</select>".$ta_def;
                                        ?>
                                    </td>
                                    <td><h2>Approver: </h2></td>
                                    <td>
                                        <?php
                                        echo "<select name='nontrade_signatory'>";
                                        if (isset($_SESSION['nontrade_signatory'])) {
                                            $sql_sic = mysql_query("SELECT * FROM users WHERE user_id='" . $_SESSION['nontrade_signatory'] . "' and status!='deleted' and usertype!='1'");
                                            $rs_sic = mysql_fetch_array($sql_sic);
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        } else {
                                            echo "<option value='2'>LLRegala</option>";
                                            $nta_def = "<i><font size='-1'>default</font></i>";
                                        }
                                        $sql_sic = mysql_query("SELECT * FROM users WHERE user_id!='" . $_SESSION['user_id'] . "' and status!='deleted' and usertype!='1'");
                                        while ($rs_sic = mysql_fetch_array($sql_sic)) {
                                            echo "<option value='" . $rs_sic['user_id'] . "'>" . strtoupper($rs_sic['initial']) . "" . strtolower(substr($rs_sic['lastname'], 1)) . "</option>";
                                        }
                                        echo "</select>".$nta_def;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" align="center"><br>
                                        <input type="submit" class="input" name="submit" value="Enter">
                                    </td>
                            </table>
                            <!---RJ end-->
                        </form>
                        <br>
                        <br>
                        <h2>SIGNATURE </h2>
                        <!--<img src="../../signatures/JAS.jpg">-->
                        <?php
                        $file = '../../signatures_pamp/' . $_SESSION['initial'] . '.jpg';
                        if (file_exists($file)) {
                            echo '<img class="sig" src="' . $file . '" height="80">';
                        } else {
                            echo "No Signature Uploaded.";
                        }
                        ?>
                        <form action="update_signature_exec.php" method="POST" enctype='multipart/form-data'>
                            <input type="file" name="attachment">
                            <br><br>
                            <input class="input" type="submit" name="submit3" value="Update">
                        </form>
                        <br>
                        <br>
                        <h2>CHANGE PASSWORD</h2>
                        <form action="initial_settings.php" method="POST">
                            <table>
                                <tr>
                                    <td>Old Password: </td>
                                    <td><input class="input2" type="password" name="old_pass" value=""></td>
                                </tr>
                                <tr>
                                    <td>New Password: </td>
                                    <td><input class="input2" type="password" name="new_pass" value=""></td>
                                </tr>
                                <tr>
                                    <td>Re-enter Password: </td>
                                    <td><input class="input2" type="password" name="new_pass2" value=""></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="center"><input class="input" type="submit" name="submit2" value="Submit"></td>
                                </tr>
                            </table>
                        </form>
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe src="template/pending2.php" width="367" height="630" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->

            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
