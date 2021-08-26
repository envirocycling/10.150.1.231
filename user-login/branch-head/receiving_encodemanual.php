<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['bh_id'])) {
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
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <link href="css/select2.min.css" rel="stylesheet">
        <script type="text/javascript" src="js/select2.min.js"></script>
        <script type="text/javascript" src="js/receiving_manual2.js"></script>
        <script>
            $(document).ready(function () {
                $('#delivered_by').select2();
            });
            
                
                
        </script>
        <style>
            .tcal{
                border-radius: 4px;
                height: 30px;
                width: 200px;
                font-size: 18px;
                text-transform: uppercase;
            }
            .input-small{
                border-radius: 4px;
                height: 30px;
                width: 200px;
                font-size: 18px;
                text-transform: uppercase;
            }
            #input{
                border-radius: 4px;
                height: 30px;
                width: 100px;
                font-size: 18px;
                text-transform: uppercase;
            }
            .input{
                border-radius: 4px;
                height: 30px;
                width: 100px;
                font-size: 18px;
                text-transform: uppercase;
            }

            table{
                margin-left: 60px;
                font-size: 16px;
            }
            select{
                width: 75%;
            }
            #table,.tr{
                border-collapse: collapse;
                border: groove;
                border-width: 3px;
            }
            .button{
                border-radius: 4px;
                font-size: 18px;
                height: 30px;
                width: 80px;
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
                <br>
                <center>
                <h2>Encode Manual Receiving</h2>
                </center>
                <br>
                <br>
                    <input type="hidden" value="1" id="ctrl">
                    <table>
                        <tr>
                            <td>Date Delivered: </td>
                            <td><input class="tcal" type="text" id="date" name="date_delivered"  size="20" required readonly autocomplete="off"></td>
                        </tr>
                        <tr>
                            <td>Str No: </td>
                            <td><input class="input-small" id="str_no" size="20" required></td>
                        </tr>
                        <tr>
                            <td>TR No: </td>
                            <td><input class="input-small" id="tr_no" size="20" placeholder="optional"></td>
                        </tr>
                        <tr>
                            <td>Delivered By: </td>
                            <td><select id="delivered_by" required>
                                    <option value="" selected disabled>Please Select</option>
                                        <?php
                                        $sql_sup = mysql_query("SELECT * FROM supplier");
                                        while ($rs_sup = mysql_fetch_array($sql_sup)) {
                                            echo '<option value="' . $rs_sup['supplier_id'].'-'.$rs_sup['supplier_name']. '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Plate No: </td>
                            <td><input class="input-small" id="plate_no" size="20" required></td>
                        </tr>
                        <tr>
                            <td>Delivered To: </td>
                            <td><select class="input-small" id="delivered_to" required>
                                    <option value="" selected disabled>Please Select</option>
                                    <option value="TIPCO">TIPCO</option>
                                    <option value="MULTIPLY">MULTIPLY</option>
                                    <option value="FSI">FSI</option>
                                    <option value="INTER BRANCH">INTER BRANCH</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br><br>
                    <center>
                    <table id="table">
                        <tr style="text-align: center;">
                            <td>WP GRADE</td>
                            <td>BALES</td>
                            <td>GROSS</td>
                            <td>TARE</td>
                            <td>WEIGHT</td>
                            <td>MC</td>
                            <td>DIRT</td>
                            <td>NET WEIGHT</td>
                        </tr>
                     <?php
                        $num = 1;
                        $ctr = 10;
                        while($num <= $ctr){
                            if($num > 1){
                                $attrib = "hidden";
                            }
                     ?>
                        <tr height="60px" class="tr" id="tr_<?php echo $num;?>" <?php echo $attrib;?>>
                            <td><select class="input-small" id="wpgrade_<?php echo $num;?>" >
                                    
                                   <option value="" selected disabled>Please Select</option>
                                <?php
                                    $sql_material = mysql_query("SELECT * from material WHERE status = '' ") or die(mysql_error()); 
                                    while($row_material = mysql_fetch_array($sql_material)){
                                        
                                        echo '<option value="'.$row_material['code'].'">'.$row_material['code'].'</option>';
                                    }
                                ?>
                                </select>
                            </td>
                            <td><input type="text" class="input" id="bales_<?php echo $num;?>"></td>
                            <td><input type="number" class="input" id="gross_<?php echo $num;?>"></td>
                            <td><input type="number" class="input" id="tare_<?php echo $num;?>"></td>
                            <td><input type="number" class="input" id="weight_<?php echo $num;?>"></td>
                            <td><input type="number" class="input" id="mc_<?php echo $num;?>"></td>
                            <td><input type="number" class="input" id="dirt_<?php echo $num;?>"></td>
                            <td><input type="number" class="input" id="netweight_<?php echo $num;?>" readonly></td>
                        </tr>
                        <?php
                        $num++;
                        }
                        ?>
                    </table>
                        <br>
                        <div align="right" style="width:70%;"><input type="button" value="+" id="add" class="button">&nbsp;&nbsp;&nbsp;<input type="button" id="minus" value="-" class="button"></div>;
                        <div align="right" style="width:70%;"><br><br><br><input type="button" id="submit" class="button" value="Submit"></div>
                <br>
                <br>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>