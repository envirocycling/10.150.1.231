<?php
@session_start();
include 'config.php';
if (!isset($_SESSION['bh_id'])) {
    echo "<script>location.replace('../../');</script>";
}

if (isset($_GET['approve_id'])) {
    $sql_adv = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['approve_id'] . "'");
    $rs_adv = mysql_fetch_array($sql_adv);
    
    if ($rs_adv['acpty_id'] == '4' && $rs_adv['acty_id'] == '4') {
        mysql_query("UPDATE adv SET status='issued',verified_id='" . $_SESSION['user_id'] . "',verified_date='" . date('Y-m-d H:m:s') . "',approved_id='" . $_SESSION['user_id'] . "',approved_date='" . date('Y-m-d H:m:s') . "' , date_processed='".$rs_adv['date']."' WHERE ac_id='" . $_GET['approve_id'] . "'");
    }else if ($rs_adv['prepaid'] == '1') {
        mysql_query("UPDATE adv SET status='approved',verified_id='" . $_SESSION['user_id'] . "',verified_date='" . date('Y-m-d H:m:s') . "',approved_id='" . $_SESSION['user_id'] . "',approved_date='" . date('Y-m-d H:m:s') . "' WHERE ac_id='" . $_GET['approve_id'] . "'");
    } else {
        mysql_query("UPDATE adv SET status='verified',verified_id='" . $_SESSION['user_id'] . "',verified_date='" . date('Y-m-d H:i:s') . "' WHERE ac_id='" . $_GET['approve_id'] . "'");
    }
    echo "<script>";
    echo "alert('Successfully Approved.');";
    echo "location.replace('adv_view.php?ac_id=" . $_GET['approve_id'] . "');";
    echo "</script>";
}
if (isset($_GET['disapprove_id'])) {
    mysql_query("UPDATE adv SET status='disapproved',verified_id='" . $_SESSION['user_id'] . "',verified_date='" . date('Y-m-d H:i:s') . "' WHERE ac_id='" . $_GET['disapprove_id'] . "'");
    echo "<script>";
    echo "alert('Successfully disapproved.');";
    echo "location.replace('adv_view.php?ac_id=" . $_GET['disapprove_id'] . "');";
    echo "</script>";
}
if (isset($_GET['cancel_id'])) {
    mysql_query("UPDATE adv SET status='',verified_id='',verified_date='' WHERE ac_id='" . $_GET['cancel_id'] . "'");
    echo "<script>";
    echo "alert('Successfully disapproved.');";
    echo "location.replace('adv_view.php?ac_id=" . $_GET['cancel_id'] . "');";
    echo "</script>";
}
if (isset($_GET['cancel_id2'])) {
    mysql_query("UPDATE adv SET status='cancelled' WHERE ac_id='" . $_GET['cancel_id2'] . "'");
    echo "<script>";
    echo "alert('Successfully cancelled.');";
    echo "location.replace('adv_list.php');";
    echo "</script>";
}

$sql_ac = mysql_query("SELECT * FROM adv WHERE ac_id='" . $_GET['ac_id'] . "'");
$rs_ac = mysql_fetch_array($sql_ac);
?>
<!DOCTYPE html>
<html>
    <head>
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
        <link href="css/adv_form.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/comment.css" />
        <script type="text/javascript" src="js/comment.js"></script>
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
            $(document).ready(function () {
                $('#supplier_id').select2();
            });

            $.vars = {
                row_id: '<?php echo $_GET['ac_id']; ?>',
                username: '<?php echo strtoupper($_SESSION['initial']) . "" . strtolower(substr($_SESSION['lastname'], 1)); ?>',
                table: 'adv'
            };

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

                <?php
                include 'template/menu.php';
                ?>
                <br>

                <div align="center">
                    <h2>ADVANCES FORM</h2>
                    <br>
                    <table class="table">
                        <tr>
                            <td>Date: </td>
                            <td><input id="date" class="medium-input" type="text" name="date" value="<?php echo date("m-d-Y", strtotime($rs_ac['date'])); ?>" readonly></td>
                            <td>AC No: </td>
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
                                if ($rs_ac['user_id'] != $_SESSION['user_id']) {
                                    if ($rs_ac['status'] == '') {
                                        ?>
                                        <a href="adv_view.php?approve_id=<?php echo $_GET['ac_id']; ?>"><button class="large-submit" onclick="return confirm('Are you sure you want to do this action?')">Approve</button></a> 
                                        <a href="adv_view.php?disapprove_id=<?php echo $_GET['ac_id']; ?>"><button id="button2" class="large-submit" onclick="return confirm('Are you sure you want to do this action?')">Disapprove</button></a>
                                        <?php
                                    } else if ($rs_ac['status'] == 'verified') {
                                        ?>
                                        This Request is Already Verified.
                                        <a href="adv_view.php?cancel_id=<?php echo $_GET['ac_id']; ?>"><button class="large-submit" onclick="return confirm('Are you sure you want to do this action?')">Cancel</button></a> 
                                        <?php
                                    } else if ($rs_ac['status'] == 'disapproved') {
                                        ?>
                                        This Request is Already disapproved.
                                        <a href="adv_view.php?cancel_id=<?php echo $_GET['ac_id']; ?>"><button class="large-submit" onclick="return confirm('Are you sure you want to do this action?')">Cancel</button></a> 
                                        <?php
                                    } else if ($rs_ac['status'] == 'approved') {
                                        ?>
                                        This Request is Already Approved.
                                        <?php
                                    }
                                } else {
                                    if ($rs_ac['status'] == 'verified') {
                                        ?>
                                        This Request is Already Verified.
                                        <a href='adv_form_edit.php?ac_id=<?php echo $_GET['ac_id']; ?>'><button class='large-submit'>Edit</button></a> 
                                        <a href="adv_view.php?cancel_id2=<?php echo $_GET['ac_id']; ?>"><button class="large-submit" onclick="return confirm(' This Request will mark as cancelled, Are you sure you want to do this action?')">Cancel</button></a> 
                                        <?php
                                    } else if ($rs_ac['status'] == 'approved') {
                                        ?>
                                        This Request is Already Approved.
                                        <?php
                                    }
                                }
                                ?>
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
                </div>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>