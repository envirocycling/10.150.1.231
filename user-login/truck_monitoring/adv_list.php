<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';
if (!isset($_SESSION['trck_reg_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link href="css/adv_form.css" rel="stylesheet">

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

        <style>
            .table {
                border: 1px solid black;
                height: 500px;
                overflow: auto;
                width: 810px;
            }
            button{
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

              
                        <div width="1200">
                            <?php
                            include 'template/menu.php';
                            ?>
                        </div>
                        <br>
                        <h2>TRUCK MONITORING ADVANCES LIST</h2>
                        <br>
                        <form method="POST">
                            <?php
                            $year_start = '2015';
                            $year_end = date('Y', strtotime('+1 year' , strtotime(date('Y'))));
                            echo '&nbsp;&nbsp;&nbsp;&nbsp; Year: &nbsp;';
                            echo '<select name="year">';
                            if (isset($_POST['submit'])) {
                                echo '<option value="'.$_POST['year'].'">'.$_POST['year'].'</option>';
                            }
                                while($year_start <= $year_end){
                                    echo '<option value="'.$year_start.'">'.$year_start.'</option>';
                                    $year_start++;
                                }
                            echo '</select>';
                            echo ' &nbsp;&nbsp;&nbsp;<input type="submit" name="submit">';
                            ?>
                        </form>
                        <br>
                        <div class="table" style="width:99%;">
                            <table class="data display datatable" id="example">
                                <thead>
                                    <tr class="data">
                                        <th class="data">Date</th>
                                        <th class="data">Branch</th>
                                        <th class="data">Ref No.</th>
                                        <th class="data">Supplier Name</th>
                                        <th class="data">Amount</th>
                                        <th class="data">Status</th>
                                        <th class="data">Type</th>
                                        <th class="data">Justification</th>
                                        <th class="data">Terms</th>
                                        <th class="data">Remarks</th>
                                    </tr>
                                </thead>
                                <?php
                                    $sql_adv = mysql_query("SELECT * from adv WHERE class != '' and date LIKE '".$_POST['year']."%'") or die(mysql_error());
                                        while($row_adv = mysql_fetch_array($sql_adv)){
                                            $sql_brach = mysql_query("SELECT * from branches WHERE branch_id = '".$row_adv['branch_id']."'") or die(mysql_error());
                                            $row_branch = mysql_fetch_array($sql_brach);
                                            
                                            $sql_supplier = mysql_query("SELECT * from supplier WHERE id='".$row_adv['supplier_id']."'") or die(mysql_error());
                                            $row_supplier = mysql_fetch_array($sql_supplier);
                                            
                                            $sql_ptyp = mysql_query("SELECT * from adv_paytype WHERE acpty_id  = '".$row_adv['acpty_id']."'") or die (mysql_error());
                                            $row_ptype = mysql_fetch_array($sql_ptyp);      
                                            
                                            $sql_payment = mysql_query("SELECT * from adv_payment WHERE ac_id='".$row_adv['ac_id']."'") or die(mysql_error());
                                            $row_payment = mysql_fetch_array($sql_payment);
                                            if(mysql_num_rows($sql_payment) > 0){
                                                $date_paid = date('Y/m/d', strtotime($row_payment['date_paid']));
                                            }else{
                                                $date_paid = '';
                                            }
                                            echo '<tr>';
                                                    echo '<td>'.date('Y/m/d', strtotime($row_adv['date'])).'</td>';
                                                    echo '<td>'.$row_branch['branch_name'].'</td>';
                                                    echo '<td>'.$row_adv['ac_no'].'</td>';
                                                    echo '<td>'.$row_supplier['supplier_id'].'_'.$row_supplier['supplier_name'].'</td>';
                                                    echo '<td>'.$row_adv['amount'].'</td>';
                                                    echo '<td>'.$row_adv['status'].'</td>';
                                                    echo '<td>Cash Advance</td>';
                                                    echo '<td>'.$row_adv['justification'].'</td>';
                                                    echo '<td>'.$row_adv['terms'].'</td>';
                                                    echo '<td>'.$row_payment['remarks'].'</td>';
                                            echo '</tr>';
                                        }
                                ?>
                            </table>
                        </div>
                        <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>