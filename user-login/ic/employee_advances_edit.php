<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('../../');</script>";
}
?>
<!DOCTYPE html>
<html>
    <head>
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
        <?php
        include 'template/layout.php';
        ?>
        <link href="css/emp_adv_form.css" rel="stylesheet">
        <style>
            .table{
                font-size: 18px;
            }
            .table td{
                padding: 3px;
            }
            #prepaid{
                height: 20px;
                width: 20px;
            }
            #emp_id{
                width: 310px;
                text-align:center;
            }
            #purpose{
                border-radius: 4px;
                width:450px;
                text-transform:uppercase;
                font-size:15px;
            }
        </style>
        <link href="css/select2.min.css" rel="stylesheet">
        <script type="text/javascript" src="js/select2.min.js"></script>
        <script>
            $(document).ready(function () {
                $('#emp_id').select2();
            });
        </script>
        <script>

            function textAreaAdjust(o) {
                o.style.height = "1px";
                o.style.height = (20 + o.scrollHeight) + "px";
            }

            function isNum(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;

                if (charCode > 44 && charCode < 58) {
                    return true;
                }
                return false;
            }
            
            
            //start here	
            $(document).ready(function () {

                $("select").change(function() {
                    var ID = $(this).attr('id');
                        if(ID == 'emp_id'){
                            var emp_id = $('#' + ID).val();
                            var datax = 'emp_id' + emp_id;
                                 if (emp_id == "") {
                                    $("#ea_balance").html("");
                                    return;
                                } else { 
                                    if (window.XMLHttpRequest) {
                                        // code for IE7+, Firefox, Chrome, Opera, Safari
                                        xmlhttp = new XMLHttpRequest();
                                    } else {
                                        // code for IE6, IE5
                                        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                                    }
                                    xmlhttp.onreadystatechange = function() {
                                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                                            $("#ea_balance").html(xmlhttp.responseText);
                                        }
                                    };
                                    xmlhttp.open("GET","exec/employee_advances_chkbalance.php?q="+emp_id,true);
                                    xmlhttp.send();
                                }
                        }
                });
                
                //start submit
                $("#submit").click(function () {

                    var emp_id = $("#emp_id").val();
                    var ref_no = $("#ref_no").val();
                    var date = $("#date").val();
                    var amount = $("#amount").val();
                    var purpose = escape($("#purpose").val());
                    var approve_id = $("#approve_id").val();
                    var error = "This Fields are Required: ";
                    var message = confirm("Do you want to Proceed?");
                    var ea_id = "<?php echo $_GET['ea_id'];?>";

                    if (message == true) {

                        if (emp_id == "") {
                            var error = error + "(Employee Name) ";
                            var error_num = 1;
                        }
                        if (amount == "" || amount <= 0) {
                            var error = error + "(Amount) ";
                            var error_num = 1;
                        }
                        if (purpose == "" || purpose == " ") {
                            var error = error + "(Purpose) ";
                            var error_num = 1;
                        }
                        if (approve_id == "") {
                            var error = error + "(Approver) ";
                            var error_num = 1;
                        }

                        if (error_num == 1) {
                            alert(error);
                            return false;
                        }

                        var myData = 'emp_id=' + emp_id + '&ref_no=' + ref_no + '&date=' + date + '&amount=' + amount + '&purpose=' + purpose + '&approve_id=' + approve_id + '&edit=1' + '&ea_id=' + ea_id;

                        $.ajax({
                            type: "POST",
                            url: "exec/employee_advances_exec.php",
                            data: myData
                        });
                        alert("Successful.");
                        location.replace("employee_advances_list.php");
                    }

                });
                //end submit

            });
            //end here
            function f_back(){
            window.history.back();
        }
        </script> 

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

                            $sql_ea = mysql_query("SELECT * from employee_advances WHERE ea_id = '".$_GET['ea_id']."'") or die(mysql_error());
                            $row_ea = mysql_fetch_array($sql_ea);
                            
                            $sql_emp = mysql_query("SELECT * from employee WHERE emp_id = '".$row_ea['emp_id']."'") or die(mysql_error());
                            $row_emp = mysql_fetch_array($sql_emp);
                            
                            $sql_app = mysql_query("SELECT * from users WHERE user_id = '".$row_ea['approver']."'") or die(mysql_error());
                            $row_app = mysql_fetch_array($sql_app);

                            ?>
                        <br>
                        <h2>EMPLOYEE ADVANCES FORM</h2>
                        <br>
                        <!--<form id="advancesForm" onSubmit="return validateForm()">-->
                        <table class="table">
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("Y-m-d"); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ref_no" class="medium-input" type="text" name="ref_no" value="<?php echo $row_ea['ref_no']; ?>" readonly></td>
                            </tr>
                            <tr>
                                <td>Employee Name:</td>
                                <td><select id="emp_id" class="medium-select-2" name="emp_name" required>
                                        <option value="<?php echo $row_ea['emp_id'];?>"><?php echo $row_ea['emp_id'].'_'.$row_emp['name'];?></option>
                                        <?php
                                        $sql_emp = mysql_query("SELECT * FROM employee WHERE status='' and emp_id != '".$row_ea['emp_id']."' ORDER by emp_id ASC");
                                        while ($rs_emp = mysql_fetch_array($sql_emp)) {
                                            echo '<option value="' . $rs_emp['emp_id'] . '">' . $rs_emp['emp_id'] . '_' . $rs_emp['name'] .                  '</option>';
                                        }
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" class="medium-input" type="number" value="<?php echo $row_ea['amount'];?>" required></td>
                            </tr>
                            <td>Purpose: </td>
                            <td colspan="3"><textarea onKeyUp="textAreaAdjust(this);" style="overflow:hidden" name="purpose" id="purpose" required><?php echo $row_ea['purpose']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Approver:</td>
                                <td><select id="approve_id" class="medium-select" required>
                                        <option value="<?php echo $row_ea['approver'];?>"><?php echo $row_app['initial'];?></option>
                                        <?php
                                        $sql_user = mysql_query("SELECT * FROM users WHERE user_id != '".$row_ea['approver']."' and (position LIKE '%GENERAL%' or position LIKE '%BH%' or position LIKE '%HR%' or position LIKE '%SUPERVISOR%')");
                                        while ($rs_user = mysql_fetch_array($sql_user)) {
                                            echo '<option value="' . $rs_user['user_id'] . '">' . $rs_user['initial'] . '</option>';
                                        }
                                        ?>
                                    </select></td>
                            </tr>
                            
                            <tr>
                                <td colspan="4"><div id="ea_balance"></div></td>
                            </tr>
                            <tr>
                            <td colspan="3"><input id="submit" class="large-submit" type="submit"></td>
                            <td><input class="large-submit" type="submit" value="Back" onclick="f_back();"></td>
                            </tr>
                        </table>    
                        <!--</form>-->
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