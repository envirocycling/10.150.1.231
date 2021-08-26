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
                        <h2>SUPPLIERS</h2>
                        <br>
                        <form action="suppliers.php" method="POST">
                            <b>Branch: </b><select name="branch">
                                <?php
                                include 'config.php';
                                $sql = mysql_query("SELECT * FROM supplier GROUP BY branch");
                                echo '<option value="">All Branch</option>';
                                while ($rs = mysql_fetch_array($sql)) {
                                    echo '<option value="'.$rs['branch'].'">'.$rs['branch'].'</option>';
                                }
                                ?>

                            </select>
                            <input type="submit" name="submit" value="Submit">
                        </form>
                        <iframe src="iframe/query_suppliers.php?branch=<?php if(isset($_POST['branch'])) {
                            echo $_POST['branch'];
                                } ?>" width="800" height="500" scrolling="yes"></iframe>
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