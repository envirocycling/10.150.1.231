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
                var ID = $('#emp_id').val().split("_");
                var emp_id = ID[0];
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
                    xmlhttp.onreadystatechange = function () {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                            $("#ea_balance").html(xmlhttp.responseText);
                        }
                    };
                    xmlhttp.open("GET", "exec/employee_advances_chkbalance.php?q=" + emp_id, true);
                    xmlhttp.send();
                }
                
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
                

                //start submit
                $("#submit").click(function () {

                    var emp_id = $("#emp_id").val();
                    var ref_no = $("#ref_no").val();
                    var date = $("#date").val();
                    var amount = $("#amount").val();
                    var purpose = escape($("#purpose").val());
                    var approve_id = $("#approve_id").val();
                    var company = $("#company").val();
                    var error = "This Fields are Required: ";
                    var message = confirm("Do you want to Proceed?");
                    var ea_id = "<?php echo $_GET['ea_id']; ?>";
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

                        var myData = 'emp_id=' + emp_id + '&ref_no=' + ref_no + '&date=' + date + '&amount=' + amount + '&purpose=' + purpose + '&approve_id=' + approve_id + '&company=' + company + '&edit=1' + '&ea_id=' + ea_id;

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
            function f_back() {
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

            <div class="middle">

                <div class="container">
                    <main class="content">
                        <div style="margin-left: -11px;" width="1200">
                            <?php
                            include 'template/menu.php';

                            $sql_ea = mysql_query("SELECT * from employee_advances WHERE ea_id = '" . $_GET['ea_id'] . "'") or die(mysql_error());
                            $row_ea = mysql_fetch_array($sql_ea);
                            ?>
                        </div>
                        <br>
                        <h2>EMPLOYEE ADVANCES FORM</h2>
                        <br>
                        <!--<form id="advancesForm" onSubmit="return validateForm()">-->
                        <table class="table">
                            <tr>
                                <td>Company:</td>
                                <td><select id="company" class="medium-select" required>
                                        <?php
                                        echo '<option value="' . $row_ea['comp_id'] . '" selected>' . strtoupper($row_ea['comp_id']) . '</option>';
                                        $sql_company = mysql_query("SELECT DISTINCT(comp_code) FROM branches WHERE comp_code!='" . $row_ea['comp_id'] . "' ORDER BY comp_code Asc");
                                        while ($rs_company = mysql_fetch_array($sql_company)) {
                                            echo '<option value="' . $rs_company['comp_code'] . '">' . strtoupper($rs_company['comp_code']) . '</option>';
                                        }
                                        ?>
                                    </select></td>
                                <td colspan="2"></td>
                            </tr>
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("Y-m-d"); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ref_no" class="medium-input" type="text" name="ref_no" value="<?php echo $row_ea['ref_no']; ?>"></td>
                            </tr>
                            <tr>
                                <td>Employee Name:</td>
                                <td><select id="emp_id" class="medium-select-2" name="emp_name" required>
                                        <?php
                                        $sql_current_emp = mysql_query("SELECT * from employee WHERE emp_id = '" . $row_ea['emp_id'] . "'") or die(mysql_error());
                                        $row_current_emp = mysql_fetch_array($sql_current_emp);
                                        echo '<option value="' . $row_current_emp['emp_id'] . '">' . $row_current_emp['emp_id'] . '_' . $row_current_emp['name'] . '</option>';
                                        $sql_emp = mysql_query("SELECT * FROM employee WHERE status='' and emp_id != '" . $row_ea['emp_id'] . "' ORDER by emp_id ASC");
                                        while ($rs_emp = mysql_fetch_array($sql_emp)) {
                                            echo '<option value="' . $rs_emp['emp_id'] . '">' . $rs_emp['emp_id'] . '_' . $rs_emp['name'] . '</option>';
                                        }
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" class="medium-input" type="number" value="<?php echo $row_ea['amount']; ?>" name="" required></td>
                            </tr>
                            <td>Purpose: </td>
                            <td colspan="3"><textarea onKeyUp="textAreaAdjust(this);" style="overflow:hidden" name="purpose" id="purpose" required><?php echo $row_ea['purpose']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Approver:</td>
                                <td><select id="approve_id" class="medium-select" required>
                                        <?php
                                        $sql_current_approver = mysql_query("SELECT * from users WHERE user_id = '" . $row_ea['approver'] . "'");
                                        $row_current_approver = mysql_fetch_array($sql_current_approver);

                                        $sql_user = mysql_query("SELECT * FROM users WHERE (position LIKE '%GENERAL%' or position LIKE '%BH%' or position LIKE '%HR%' or position LIKE '%SUPERVISOR%' or position LIKE '%Senior Accountant%') and user_id != '" . $row_ea['approver'] . "'");

                                        echo '<option value="' . $row_current_approver['user_id'] . '">' . $row_current_approver['initial'] . '</option>';

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
                        <div id="err"></div>
                        <!--                                                <br>
                                                                        <br>
                                                                        <br>
                                                                        <br>-->
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