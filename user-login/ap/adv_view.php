<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('../../');</script>";
}

$sql_ac = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);

function branch($branch_id){
    $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='$branch_id'");
    $rs_branch = mysql_fetch_array($sql_branch);
    
    return $rs_branch['branch_name'];
}
?>
<!DOCTYPE html>
<html>
    <head>
        <?php
        include 'template/layout.php';
        ?>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <link rel="stylesheet" type="text/css" href="css/comment.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="js/comment.js"></script>
        <link href="css/adv_form.css" rel="stylesheet">
        <style>
            .table{
                font-size: 18px;
            }
            .table td{
                padding: 3px;
            }
            #button2{
                width: 115px;
            }
            #prepaid{
                height: 20px;
                width: 20px;
            }

        </style>
        <script>
            $.vars = {
                row_id: '<?php echo $_GET['ac_id']; ?>',
                username: '<?php echo strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)); ?>',
                table: 'adv',
                pending: 'true'
            };

            function cancel(id) {
                var r = confirm("Are you sure you want to cancel this request?");
                if (r === true) {
                    var data = 'ac_id=' + id;
                    $.ajax({
                        url: "exec/adv_exec.php?action=cancelAc",
                        type: 'POST',
                        data: data
                    }).done(function () {
                        alert('Successfully Cancelled.');
                        location.replace('adv_list.php');
                    });
                }
            }

            function print(id) {
                window.open("adv_print.php?ac_id=" + id, 'mywindow', 'width=1020,height=600,left=150,top=20');
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
                            ?>
                        </div>
                        <br>

                        <h2><?php echo strtoupper(branch($rs_ac['branch_id']));?> ADVANCES</h2>
                        <br>
                        <table class="table">
                            <tr>
                                <td>Date: </td>
                                <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                                <td>Ref No: </td>
                                <td><input id="ac_no" class="medium-input" type="text" name="ac_no" value="<?php echo $rs_ac['ac_no']; ?>" readonly>
                            </tr>
                            <tr>
                                <td>Supplier Name:</td>
                                <td><select id="supplier_id" class="medium-select-2" name=""  readonly>
                                        <?php
                                        $sql_sup = mysql_query("SELECT * FROM supplier WHERE id='" . $rs_ac['supplier_id'] . "'");
                                        $rs_sup = mysql_fetch_array($sql_sup);
                                        echo '<option value="' . $rs_sup['id'] . '">' . $rs_sup['supplier_id'] . '_' . $rs_sup['supplier_name'] . '</option>';
                                        ?>
                                    </select></td>
                                <td>Type: </td>
                                <td><select id="acty_id" class="medium-select" name=""  readonly>
                                        <?php
                                        $sql_type = mysql_query("SELECT * FROM adv_type WHERE acty_id='" . $rs_ac['acty_id'] . "'");
                                        $rs_type = mysql_fetch_array($sql_type);
                                        echo '<option value="' . $rs_type['acty_id'] . '">' . $rs_type['name'] . '</option>';
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Amount: </td>
                                <td><input id="amount" class="medium-input" type="number" name="amount" value="<?php echo $rs_ac['amount']; ?>"  readonly></td>
                                <td>Payment Type: </td>
                                <td><select id="acpty_id" class="medium-select" name=""  readonly>
                                        <?php
                                        $sql_ptype = mysql_query("SELECT * FROM adv_paytype WHERE acpty_id='" . $rs_ac['acpty_id'] . "'");
                                        $rs_ptype = mysql_fetch_array($sql_ptype);
                                        echo '<option value="' . $rs_ptype['acpty_id'] . '">' . $rs_ptype['name'] . '</option>';
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Justification: </td>
                                <td colspan="3"><textarea id="justification" class="medium-textarea-2" readonly><?php echo $rs_ac['justification']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Terms: </td>
                                <td colspan="3"><textarea id="terms" class="medium-textarea-2" readonly><?php echo $rs_ac['terms']; ?></textarea></td>
                            </tr>
                            <tr>
                                <td>Prepaid: </td>
                                <td colspan="3"><input id="prepaid" type="checkbox" name="prepaid" value="prepaid" <?php
                                    if ($rs_ac['prepaid'] == '1') {
                                        echo "checked";
                                    }
                                    ?> disabled></td>
                            </tr>
                            <tr>
                                <td colspan="4" align="right">

                                    <?php
                                    if ($rs_ac['status'] == '') {
                                        ?>
                                    This Request is pending for verification.
                                        <?php
                                        echo "<a href='adv_form_edit.php?ac_id=" . $rs_ac['ac_id'] . "'><button class='large-submit'>Edit</button></a> <button id='" . $rs_ac['ac_id'] . "' onclick='cancel(this.id);' class='large-submit'>Cancel</button>";
                                    } else if ($rs_ac['status'] == 'verified') {
                                        ?>
                                    This Request is already verified.
                                        <?php
                                    } else if ($rs_ac['status'] == 'disapproved') {
                                        ?>
                                    This Request is disapproved.
                                        <?php
                                    } else if ($rs_ac['status'] == 'approved' && $rs_ac['acpty_id'] == '3') {
                                        ?>
                                    This Request is already approved.
                                        <?php
                                        echo "<a href='adv_form_process2.php?ac_id=" . $rs_ac['ac_id'] . "'><button class='large-submit'>Process</button></a>";
                                    } else if ($rs_ac['status'] == 'approved') {
                                        ?>
                                    This Request is already approved.
                                        <?php
                                        echo "<a href='adv_form_process.php?ac_id=" . $rs_ac['ac_id'] . "'><button class='large-submit'>Process</button></a>";
                                    }
                                    ?>
                                    <button class="large-submit" onclick="print(<?php echo $rs_ac['ac_id']; ?>);">Print</button>
                                    <a href="adv_list.php"><button class="large-submit">Back</button></a>
                                </td>
                            </tr>
                        </table>
                        </form>
                        <br>
                        <div class="comments">
                            Comments: 
                            <table id="tblComment" border="0">
                                <tr id="tdComment" class="hide">
                                    <td class="comment"></td>
                                    <td class="action"></td>
                                </tr>
                            </table>
                            <br>
                            Write a Comment:
                            <br>

                            <textarea id="comment" class="medium-textarea-2-1" maxlength="500"></textarea>
                            <br>
                            <button class="large-submit" id="btn_submit">Submit</button>
                        </div>
                        <br><br>
                    </main><!-- .content -->
                </div><!-- .container-->

                <aside class="left-sidebar">
                    <iframe id="pending" src="template/pending2.php" width="367" height="640" scrolling="yes"></iframe>
                </aside><!-- .left-sidebar -->
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
