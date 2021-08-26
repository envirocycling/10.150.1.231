<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
if (!isset($_SESSION['ap_id'])) {
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
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>
        <link rel="stylesheet" type="text/css" href="css/frm_fundtransfer2.css" />
        <!--<script type="text/javascript" src="js/fund_process_news.js"></script>-->

    <body>
        <div class="wrapper">
            <header class="header">
                <?php
                date_default_timezone_set("Asia/Singapore");
                $sql_cutoff = mysql_query("SELECT * from system_settings") or die(mysql_error());
                $row_cutoff = mysql_fetch_array($sql_cutoff);

                if (isset($_POST['save'])) {
                    $sql_branch2 = mysql_query("SELECT * from branches WHERE status!='n/a' Order by branch_name Asc") or die(mysql_error());

                    $date = $_POST['date'];
                    $time = $_POST['cutofftime'];
                    $af = $_POST['af'];
                    while ($row_branch2 = mysql_fetch_array($sql_branch2)) {

                        $branch_id = $row_branch2['branch_id'];
                        $maintaining_balance = $_POST['dMaintaining_' . $branch_id];
                        $remaining = $_POST['dRemaining_' . $branch_id];
                        $request = $_POST['dTotft_' . $branch_id];
                        $allocated = $_POST['dTotallo_' . $branch_id];
                        $sbc = $_POST['sbc_' . $branch_id];
                        $expense = $_POST['expense_' . $branch_id];
                        $additional = $_POST['additional_' . $branch_id];

                        $sql_chk = mysql_query("SELECT * from fund_transfer WHERE branch_id='$branch_id' and date='$date'") or die(mysql_error());
                        if (mysql_num_rows($sql_chk) == 0) {
                            mysql_query("INSERT INTO fund_transfer (branch_id, maintaining_balance, check_expense, additional_fund, total_request, transferred, sbc_budget, user_id, date_transfer, date)
                               VALUES('$branch_id', '$maintaining_balance', '$expense','$additional', '$request', '$allocated', '$sbc','" . $_SESSION['ap_id'] . "','" . date('Y-m-d H:i') . "','$date') ") or die(mysql_error());
                        } else {
                            mysql_query("UPDATE fund_transfer SET branch_id='$branch_id', maintaining_balance='$maintaining_balance', check_expense='$expense', additional_fund='$additional', total_request='$request', transferred='$allocated', sbc_budget='$sbc', user_id='" . $_SESSION['ap_id'] . "', date_transfer='" . date('Y-m-d H:i') . "', date='$date' WHERE branch_id='$branch_id' and date='$date'") or die(mysql_error());
                        }

                        $sql_af = mysql_query("SELECT * from fund_available WHERE amount > 0 and date='$date'") or die(mysql_error());
                        if (mysql_num_rows($sql_af) == 0) {
                            mysql_query("INSERT INTO fund_available (amount, date, user_id, cutoff_time) VALUES('$af','$date','" . $_SESSION['ap_id'] . "', '$time') ") or die(mysql_error());
                        } else {
                            mysql_query("UPDATE fund_available SET amount='$af', date='$date', user_id='" . $_SESSION['ap_id'] . "', cutoff_time='$time' WHERE amount > 0 and date='$date'") or die(mysql_error());
                        }
                    }
                    echo '<script>
                        alert("Successful");
                        location.replace("fund_process.php");
                        </script>';
                }


                include 'template/header.php';
                ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php
                include 'template/menu.php';
                echo '<br /><br /><br />';
                if (!isset($_POST['submit'])) {
                    $date2 = date('F d, Y');
                    $myDate = date('Y/m/d');
                } else {
                    $date2 = date('F d, Y', strtotime($_POST['date']));
                    $myDate = $_POST['date'];
                }

                $chk_day = date('l', strtotime($myDate));
                $cutoff_day = $row_cutoff['fund_cutoff_day'];
                if ($chk_day != $cutoff_day) {
                    $date_end = date('Y/m/d', strtotime('previous ' . $cutoff_day . ' -1 day', strtotime($myDate)));
                } else {
                    $date_end = date('Y/m/d', strtotime('-1 day', strtotime($myDate)));
                }
                $date_start = date('Y/m/d', strtotime('-6 day', strtotime($date_end)));

                $from = $date_start;
                $to = $date_end;
                $arr_branch = array();
                $arr_weekly = array();
                $arr_total = array();

                $sql_branch = mysql_query("SELECT * from branches") or die(mysql_error());
                while ($row_branch = mysql_fetch_array($sql_branch)) {
                    array_push($arr_branch, $row_branch['branch_id']);
                }

                $sql_weekly = mysql_query("SELECT * from fund_transfer WHERE date >= '$from' and date <= '$to'") or die(mysql_error());
                while ($row_weekly = mysql_fetch_array($sql_weekly)) {
					if (!isset($arr_weekly[$row_weekly['branch_id']])){
						$arr_weekly[$row_weekly['branch_id']] = 0;
					}
                    $arr_weekly[$row_weekly['branch_id']] += $row_weekly['check_expense'];
                }
                ?>
                <form action="" method="post">
                    <div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Date: <input class="tcal" type="text" name="date" value="<?php echo $myDate; ?>" size="10" id="myDate" required>&nbsp;&nbsp;&nbsp; <input type="submit" name="submit" style="width:100px; height:25px;"></div>
                </form>
                <br><br>
                <h2>Process Fund Transfer</h2>
                <h4><?php echo $date2; ?></h4>
                <h4><?php echo 'Cut Off - ' . $row_cutoff['fund_cutoff_day'] . ' ' . date('h:i A', strtotime($row_cutoff['fund_cutofftime'])); ?></h4>

                <br>
                <br> <br>
                <div class="frm_limit">
                    <form method="post">
                        <table class="frm_fundtransfer">
                            <tr>
                                <td>Branch</td>
                                <td>Weekly Budget</td>
                                <td>Average (5days)</td>
                                <td>Maintaining Balance</td>
                                <td>Remaining</td>
                                <td>Check Expense</td>
                                <td>Addt'l Request</td>
                                <td>SBC</td>
                                <td>BDO</td>
                                <td>Allocated</td>
                                <!--<td>Weekly Total FT</td>-->
                            </tr>
                            <?php
                            echo '<input type="hidden" value="' . $myDate . '" name="date">';
                            echo '<input type="hidden" value="' . date('H:i', strtotime($row_cutoff['fund_cutofftime'])) . '" name="cutofftime">';
                            echo '<input type="hidden" value="0" id="hidden-count1">';
//                            echo '<input type="hidden" value="' . date('H:i', strtotime($row_cutoff['fund_cutofftime'])) . '" >';
                            $ofr = 0;
                            $total_fund3 = 0;
                            $sbc_bud = 0;
                            $weekly_tot = 0;
                            $sumAdd = 0;
                            $sumRemain = 0;
							$arr_fundadd = array();

//$myDate = date('Y/m/d');
                            $sql_av = mysql_query("SELECT * from fund_available WHERE date='$myDate' and amount > 0") or die(mysql_error());
                            $row_av = mysql_fetch_array($sql_av);
                            $sql_branch = mysql_query("SELECT * from branches WHERE status!='n/a' Order by branch_name Asc") or die(mysql_error());

                            while ($row_branch = mysql_fetch_array($sql_branch)) {
//                                echo    "SELECT * from fund_transfer WHERE branch_id = '" . $row_branch['branch_id'] . "'  and date='$myDate'<br>";
                                $sql_fund = mysql_query("SELECT * from fund_transfer WHERE branch_id = '" . $row_branch['branch_id'] . "'  and date='$myDate'") or die(mysql_error());
                                $row_fund = mysql_fetch_array($sql_fund);

                                $sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='" . $row_branch['branch_id'] . "' and '$myDate' BETWEEN `from` and `to` ") or die(mysql_error());
                                $row_chk = mysql_fetch_array($sql_chk);
                                $f_from = date('Y/m/d', strtotime($row_chk['from']));
                                $f_to = date('Y/m/d', strtotime($row_chk['to']));

                                $sql_ft_total = mysql_query("SELECT sum(`transferred`) as total_ft from fund_transfer WHERE `branch_id`='" . $row_branch['branch_id'] . "' and date >= '" . $f_from . "' and date <= '" . $f_to . "' ") or die(mysql_error());
                                $row_ft_total = mysql_fetch_array($sql_ft_total);
                                // echo "SELECT sum(`transferred`) as total_ft from fund_transfer WHERE `branch_id`='".$row_branch['branch_id']."' and date >= '".str_repalce("-","/",$row_chk['from'])."' and date <= '".str_repalce("-","/",$row_chk['to'])."'<br>";
                                $sql_add = mysql_query("SELECT * from fund_adtl_request WHERE branch_id='" . $row_branch['branch_id'] . "' and  date like '$myDate%'") or die(mysql_error());
                                while ($row_add = mysql_fetch_array($sql_add)) {
                                    $arr_fundadd[$row_add['branch_id']] += $row_add['amount'];
                                }
                                if ($row_fund['maintaining_balance'] <= 0) {
                                    $maintaining = $row_branch['maintaining_balance'];
                                    $temp_m1 = $row_branch['maintaining_balance'];
                                    $maintaining = $row_chk['budget'] / 5;
                                } else {
                                    $maintaining = $row_fund['maintaining_balance'];
                                    $temp_m2 = $row_fund['maintaining_balance'];
                                    $maintaining = $row_chk['budget'] / 5;
                                }
                                /* $maintaining2 = $arr_weekly[$row_branch['branch_id']];
                                  $maintaining = $maintaining2 / 6; */
                                //if($row_branch['branch_id'] == 10){
                                // }else{
                                //$maintaining = $row_chk['budget'] / 4;
                                //}
                                //$tot_m = $temp_m2 + $temp_m1;
                                $weekly_tot += round($row_ft_total['total_ft'], 2);
                                $sbc_bud += round($row_branch['sbc_budget'], 2);
                                $tot_m = $maintaining;
								$tot_maintaining = 0;
                                $tot_maintaining += $tot_m;
                                $expense = round($row_fund['check_expense'], 2);
								$tot_expense = 0;
                                $tot_expense += $row_fund['check_expense'];
								$add_fund = 0;
								if (!isset($arr_fundadd[$row_branch['branch_id']])){
									$arr_fundadd[$row_branch['branch_id']] = 0;
								}
                                $add_fund = $arr_fundadd[$row_branch['branch_id']] - $row_fund['urgent_additional'];
                                if (empty($add_fund)) {
                                    $add_fund = $row_fund['additional_fund'] - $row_fund['urgent_additional'];
                                }
								$sumRemain = 0;
								if (!isset($remaining)){
									$remaining = 0;
								}
                                $sumRemain += round($remaining, 2);
								$tot_remaining=0;
                                $tot_remaining += $remaining;
                                $total_fund = $remaining - $add_fund;
                                $total_fund2 = ($expense + $add_fund) - $row_branch['sbc_budget'];
                                $total_fund3 += round($expense + $add_fund, 2);
								$tot_fund=0;
                                $tot_fund += $add_fund;
                                $ofr += $total_fund;
                                $tansferred = $row_fund['transferred'];
								$tot_allo = 0;
                                $tot_allo += $tansferred;
                                $totalFund = $expense + $add_fund;
                                $remainingNew = $remaining - $row_branch['sbc_budget'];
                                $maintaningNew = $maintaining - $row_branch['sbc_budget'];
                                $remaining = $maintaningNew - $expense;
                                //echo $remaining.'~'.$add_fund.'<br>';
                                if (empty($expense)) {
                                    $expense = 0;
                                }
                                if (empty($add_fund)) {
                                    $add_fund = 0;
                                }
                                $sumAdd += round($add_fund, 2);

                                echo '<input type="hidden" name="dMaintaining_' . $row_branch['branch_id'] . '" value="' . $maintaining . '"">';
                                echo '<input type="hidden" name="dRemaining_' . $row_branch['branch_id'] . '" value="' . $remainingNew . '"">';
                                echo '<input type="hidden" name="dTotft_' . $row_branch['branch_id'] . '" value="' . $total_fund2 . '"">';
                                echo '<input type="hidden" name="dTotallo_' . $row_branch['branch_id'] . '" value="' . $tansferred . '"">';
                                echo '<input type="hidden" name="sbc_' . $row_branch['branch_id'] . '" value="' . $row_branch['sbc_budget'] . '"">';

                                echo '<tr>
							<td>' . $row_branch['branch_name'] . '</td>
                                                        <td>' . number_format($row_chk['budget'], 2) . '</td>
							<td><div id="dMaintaining_' . $row_branch['branch_id'] . '" class="dMaintaining">' . number_format($maintaining, 2) . '</div></td>
							<td><div id="dRemaining2_' . $row_branch['branch_id'] . '" class="dRemaining2">' . number_format($maintaningNew, 2) . '</div></td>
							<td><div id="dRemaining_' . $row_branch['branch_id'] . '" class="dRemaining">' . number_format($remaining, 2) . '</div></td>
                                                        <td><input type="number" value="' . $expense . '" name="expense_' . $row_branch['branch_id'] . '" step="any" class="txtbox"></td>
                                                        <td><input type="number" class="txtbox2" value="' . $add_fund . '" name="additional_' . $row_branch['branch_id'] . '"  step="any"></td>
                                                        <td><div id="sbc_' . $row_branch['branch_id'] . '" class="sbc">' . number_format($row_branch['sbc_budget'], 2) . '</div></td>
                                                        <td><div id="dTotft_' . $row_branch['branch_id'] . '" class="dTotft">' . number_format($totalFund, 2) . '</div></td>
							<td><div id="dTotallo_' . $row_branch['branch_id'] . '" class="dTotallo">' . number_format($tansferred, 2) . '</div></td>
						</tr>';
                            }
                            echo '<input type="hidden" value="' . $ofr . '" id="ofr">';
                            ?>	
                            <tr>
                                <td style="background-color:#FFFF00;">Total</td>
                                <td style="background-color:#FFFF00;"></td>
                                <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($tot_maintaining, 2); ?><div></td>
                                            <td style="background-color:#FFFF00;"><div id="t_remain"><?php echo number_format($sumRemain, 2); ?><div></td>
                                                        <td style="background-color:#FFFF00;"><div id="t_sbc"><?php echo number_format($sbc_bud, 2); ?></div></td>
                                                        <td style="background-color:#FFFF00;"><div id="t_reval"><?php echo number_format($tot_expense, 2); ?></div></td>
                                                        <td style="background-color:#FFFF00;"><div id="t_ex"><?php echo number_format($sumAdd, 2); ?></td>
                                                        <td style="background-color:#FFFF00;"></div></td>
                                                        <td style="background-color:#FFFF00;"><div id="t_fundadd"><?php echo number_format($total_fund3, 2); ?></div></td>
                                            <td style="background-color:#FFFF00;"><div id="t_ft"><?php echo number_format($tot_allo, 2); ?></div></td>
                                            <!--<td style="background-color:#FFFF00;"><div id="t_sbc"><?php // echo number_format($sbc_bud, 2);  ?></div></td>-->
                                            <!--<td style="background-color:#FFFF00;"><div id="t_weekly"><?php // echo number_format($weekly_tot, 2);  ?></div></td>-->
                                            </tr>
                                            <tr>
                                                <td colspan="10"><center>Available Fund: <input type="number" class="fund_available" id="avbl_fund" value="<?php echo $row_av['amount']; ?>" name="af" step="any" required>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Submit" class="submit" id="fund_available1" name="save"></center></td>
                                            </tr>

                                            </table>
                                            </form>
                                            <br />
                                            <br />
                                        </div>


                                    </div><!--.middle-->

                                    <footer class = "footer">
                                        <?php include 'template/footer.php';
                                        ?>
                                    </footer><!-- .footer -->

                                    </div><!-- .wrapper -->

                                    </body>
                                    </html>

                                    <script>
                                        $(document).ready(function () {
                                            $("input[type='number']").keyup(function () {
                                                var data = $(this).attr('name');
                                                var splitData = data.split('_');
                                                var _name = splitData[0];
                                                var _id = splitData[1];
                                                var remaining = Number($('#dRemaining_' + _id).html().replace(/,/g, ''));
                                                var maintaining2 = Number($('#dRemaining2_' + _id).html().replace(/,/g, ''));
                                                var maintaining = Number($('#dMaintaining_' + _id).html().replace(/,/g, ''));
                                                var expense = Number($("[name='expense_" + _id + "']").val());
                                                var add = Number($("[name='additional_" + _id + "']").val());
                                                var totft = Number($('#dTotft_' + _id).html().replace(/,/g, ''));
                                                var totallo = Number($('#dTotallo_' + _id).html().replace(/,/g, ''));
                                                var sbc = Number($('#sbc_' + _id).html().replace(/,/g, ''));
                                                var _cRemaining = (maintaining2 - expense).toFixed(2);
                                                var _cTotalft = (expense + add).toFixed(2);
                                                var _totDMaintaining = 0;
                                                var _totDMaintaining2 = 0;
                                                var _totDRemaining = 0;
                                                var _totExpense = 0;
                                                var _totAdd = 0;
                                                var _totAllo = 0;
                                                var _avf = Number($('#avbl_fund').val());

                                                $('#dRemaining_' + _id).html(_cRemaining);
                                                $('[name="dRemaining_' + _id + '"]').val(_cRemaining);
                                                $('#dTotft_' + _id).html(_cTotalft);
                                                $('[name="dTotft_' + _id + '"]').val(_cTotalft);

                                                $.each($('.dMaintaining'), function () {
                                                    _totDMaintaining += Number($(this).html().replace(/,/g, ''));
                                                });
                                                $.each($('.dRemaining'), function () {
                                                    _totDRemaining += Number($(this).html().replace(/,/g, ''));
                                                });
                                                $.each($('.txtbox'), function () {
                                                    _totExpense += Number($(this).val());
                                                });
                                                $.each($('.txtbox2'), function () {
                                                    _totAdd += Number($(this).val());
                                                });
                                                $.each($('.dTotft'), function () {
                                                    _totDMaintaining2 += Number($(this).html().replace(/,/g, ''));
                                                });

                                                $('#t_remain').html(_totDRemaining.toFixed(2));
                                                $('#t_reval').html(_totExpense.toFixed(2));
                                                $('#t_ex').html(_totAdd.toFixed(2));
                                                $('#t_fundadd').html(_totDMaintaining2.toFixed(2));

                                                if (_avf > 0) {
                                                    $.each($('.dTotft'), function () {
                                                        var _eachData = $(this).attr('id');
                                                        var _eachSplit = _eachData.split('_');
                                                        var _eachId = _eachSplit[1];
                                                        var _eachTotft = Number($('#' + _eachData).html().replace(/,/g, ''));
                                                        var _cAllo = (_avf * (_eachTotft / _totDMaintaining2)).toFixed(2);
                                                        $('#dTotallo_' + _eachId).html(_cAllo);
                                                        $('[name="dTotallo_' + _eachId + '"]').val(_cAllo);
                                                    });

                                                    $.each($('.dTotallo'), function () {
                                                        _totAllo += Number($(this).html().replace(/,/g, ''));
                                                    });
                                                    $('#t_ft').html(_totAllo.toFixed(2));
                                                }

                                            });

                                        });

                                        $('#avbl_fund').keyup(function () {
                                            var _totAllo = 0;
                                            var _avf = Number($('#avbl_fund').val());
                                            var _totDMaintaining2 = Number($('#t_fundadd').html().replace(/,/g, ''));
                                            $.each($('.dTotft'), function () {
                                                var _eachData = $(this).attr('id');
                                                var _eachSplit = _eachData.split('_');
                                                var _eachId = _eachSplit[1];
                                                var _eachTotft = Number($('#' + _eachData).html().replace(/,/g, ''));
                                                var _cAllo = (_avf * (_eachTotft / _totDMaintaining2)).toFixed(2);
                                                $('#dTotallo_' + _eachId).html(_cAllo);
                                                $('[name="dTotallo_' + _eachId + '"]').val(_cAllo);
                                            });

                                            $.each($('.dTotallo'), function () {
                                                _totAllo += Number($(this).html().replace(/,/g, ''));
                                            });
                                            $('#t_ft').html(_totAllo.toFixed(2));
                                        });
                                    </script>
