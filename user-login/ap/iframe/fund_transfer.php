<link rel="stylesheet" type="text/css" href="../css/frm_fundtransfer2.css" />
<style>
    @media print{
        .printButtonClass{ display : none }
    }
</style>
<?php
date_default_timezone_set("Asia/Singapore");

if (!isset($_GET['date'])) {
    $myDate = date('Y/m/d');
} else {
    $myDate = $_GET['date'];
}
$date2 = date('F d, Y', strtotime($myDate));
include '../config.php';

$date_update = '2017/03/30';
$date_update2 = '2017/11/09';
?>
    <div class="frm_limit2">
        <center>
            <h3 class="heading" >Fund Transfer</h3>
            <h4 class="heading" ><?php echo $date2; ?></h4>
            <table class="frm_fundtransfer" align="center">
                <tr>
                    <td>Branch</td>
                    <td>Weekly Budget</td>
                    <td>Weekly Total FT</td>
                    <td>Average (5days)</td>
                    <td>Maintaining Balance</td>
                    <td>Check Expense</td>
                    <td>Remaining Fund</td>
                    <td>Add't Request</td>
                    <td>SBC</td>
                    <?php
//                                    if($myDate >= $date_update){
//   
//                                                      echo 'SBC Expense';
//                                    }else {
//                                        echo 'Additional Request';
//                                    }                   
                    ?>
                    <td>BDO</td>
                    <td>Allocated</td>
                </tr>
                <?php
                $ofr = 0;
                if ($myDate >= $date_update) {
                    $sql_av = mysql_query("SELECT * from fund_available WHERE date='$myDate' and amount > 0") or die(mysql_error());
                    $row_av = mysql_fetch_array($sql_av);
                    $sql_branch = mysql_query("SELECT * from branches WHERE status!='n/a' Order by branch_name Asc") or die(mysql_error());

					$t_mbal = 0;
						$t_sbc = 0;
						$t_exp = 0;
						$t_remain=0;
						$t_add_fund=0;
						$t_tansferred = 0;
						$t_request = 0;
						$slctd_branch =0;
						
                    while ($row_branch = mysql_fetch_array($sql_branch)) {

                        $sql_fund = mysql_query("SELECT * from fund_transfer WHERE branch_id = '" . $row_branch['branch_id'] . "'  and date='$myDate'") or die(mysql_error());
                        $row_fund = mysql_fetch_array($sql_fund);

                        $sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='" . $row_branch['branch_id'] . "' and '$myDate' BETWEEN `from` and `to` ") or die(mysql_error());
                        $row_chk = mysql_fetch_array($sql_chk);
                        $f_from = date('Y/m/d', strtotime($row_chk['from']));
                        $f_to = date('Y/m/d', strtotime($row_chk['to']));

                        $sql_ft_total = mysql_query("SELECT sum(`transferred`) as total_ft from fund_transfer WHERE `branch_id`='" . $row_branch['branch_id'] . "' and date >= '" . $f_from . "' and date <= '" . $f_to . "' ") or die(mysql_error());
                        $row_ft_total = mysql_fetch_array($sql_ft_total);

                        $maintaining = $row_fund['maintaining_balance'];
                        $expense = $row_fund['check_expense'];
                        $sbc = $row_fund['sbc_budget'];
                        $add_fund = $row_fund['additional_fund'];
                        $request = $add_fund + $expense;
						$remaining = 0;
                        $total_fund = $remaining - $add_fund;
                        $ofr += $total_fund;
                        $tansferred = $row_fund['transferred'];
                        $maintaining2 = $maintaining - $sbc;
                        if($myDate <= '2017/11/09') {
                            $remaining = $maintaining - $expense;
                        }else{
                            $remaining = $maintaining2 - $expense;
                        }
						$totMaintaining2 = 0;
                        $totMaintaining2 += $maintaining2;
                        if (empty($expense)) {
                            $expense = 0;
                        }
                        if (empty($add_fund)) {
                            $add_fund = 0;
                        }
						
                        $t_mbal += $maintaining;
                        $t_sbc += $sbc;
                        $t_exp += $expense;
                        $t_remain += $remaining;
                        $t_add_fund += $add_fund;
                        $t_tansferred += $tansferred;
                        $t_request += $request;
						
						if (!isset($arr_total[$slctd_branch])){
							$arr_total[$slctd_branch] = 0;
						}
                        $variance = number_format(($row_chk['budget'] - $arr_total[$slctd_branch]), 2);
                        if ($variance < 0) {
                            $variance = str_replace("-", "", $variance);
                            $variance = '<font color="red">(' . $variance . ')</font>';
                        }

                        echo '<tr>
							<td>' . $row_branch['branch_name'] . '</td>
							<td>' . number_format($row_chk['budget'], 2) . '</td>
							<td>' . number_format($row_ft_total['total_ft'], 2) . '</td>
							<td>' . number_format($maintaining) . '</td>
							<td>' . number_format($maintaining2) . '</td>
							<td>' . number_format($expense, 2) . '</td>
							<td>' . number_format($remaining, 2) . '</td>
							<td>' . number_format($add_fund, 2) . '</td>
							<td>' . number_format($sbc, 2) . '</td>
							<td>' . number_format($request, 2) . '</td>
							<td>' . number_format($tansferred, 2) . '</td>
						</tr>';
						$total_weekly_budget = 0;
						 $total_weekly_total = 0;
                        $total_weekly_budget += round($row_chk['budget'], 2);
                        $total_weekly_total += round($row_ft_total['total_ft'], 2);
                    }
                    ?>	
                    <tr>
                        <td style="background-color:#FFFF00;">Total</td>
                        <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($total_weekly_budget, 2); ?><div></td>
                                    <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($total_weekly_total, 2); ?><div></td>
                                                <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($t_mbal, 2); ?><div></td>
                                                            <td style="background-color:#FFFF00;"><?php echo number_format($totMaintaining2, 2); ?></td>
                                                            <td style="background-color:#FFFF00;"><div id="t_ex"><?php echo number_format($t_exp, 2); ?></div></td>
                                                            <td style="background-color:#FFFF00;"><div id="t_reval"><?php echo number_format($t_remain, 2); ?></td>
                                                            <td style="background-color:#FFFF00;"><div id="t_fundadd"><?php echo number_format($t_add_fund, 2); ?></div></td>
                                                            <!--<td style="background-color:#FFFF00;"><div id="t_fund"><?php // echo number_format($t_sbc, 2); ?></div></td>-->
                                                            <td style="background-color:#FFFF00;"><div id="t_fund"><?php echo number_format($t_sbc, 2); ?></div></td>
                                                            <td style="background-color:#FFFF00;"><?php echo number_format($t_request, 2); ?></td>
                                                            <td style="background-color:#FFFF00;"><?php echo number_format($t_tansferred, 2); ?></td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            $sql_av = mysql_query("SELECT * from fund_available WHERE date='$myDate'") or die(mysql_error());
                                                            $row_av = mysql_fetch_array($sql_av);
                                                            $sql_branch = mysql_query("SELECT * from branches Order by branch_name Asc") or die(mysql_error());

                                                            while ($row_branch = mysql_fetch_array($sql_branch)) {

                                                                $sql_fund = mysql_query("SELECT * from fund_transfer WHERE branch_id = '" . $row_branch['branch_id'] . "'  and date='$myDate'") or die(mysql_error());
                                                                $row_fund = mysql_fetch_array($sql_fund);

                                                                $sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='" . $row_branch['branch_id'] . "' and '$myDate' BETWEEN `from` and `to` ") or die(mysql_error());
                                                                $row_chk = mysql_fetch_array($sql_chk);
                                                                $f_from = date('Y/m/d', strtotime($row_chk['from']));
                                                                $f_to = date('Y/m/d', strtotime($row_chk['to']));

                                                                $sql_ft_total = mysql_query("SELECT sum(`transferred`) as total_ft from fund_transfer WHERE `branch_id`='" . $row_branch['branch_id'] . "' and date >= '" . $f_from . "' and date <= '" . $f_to . "' ") or die(mysql_error());
                                                                $row_ft_total = mysql_fetch_array($sql_ft_total);

                                                                $maintaining = $row_fund['maintaining_balance'];
                                                                $expense = $row_fund['check_expense'];
                                                                $add_fund = $row_fund['additional_fund'];
                                                                $remaining = $maintaining - $expense;
                                                                $total_fund = $add_fund + $expense;
                                                                $ofr += $total_fund;
                                                                $tansferred = $row_fund['transferred'];

                                                                if (empty($expense)) {
                                                                    $expense = 0;
                                                                }
                                                                if (empty($add_fund)) {
                                                                    $add_fund = 0;
                                                                }

                                                                $t_mbal += $maintaining;
                                                                $t_exp += $expense;
                                                                $t_remain += $remaining;
                                                                $t_add_fund += $add_fund;
                                                                $t_tansferred += $tansferred;

                                                                $variance = number_format(($row_chk['budget'] - $arr_total[$slctd_branch]), 2);
                                                                if ($variance < 0) {
                                                                    $variance = str_replace("-", "", $variance);
                                                                    $variance = '<font color="red">(' . $variance . ')</font>';
                                                                }

                                                                echo '<tr>
							<td>' . $row_branch['branch_name'] . '</td>
							<td>' . number_format($row_chk['budget'], 2) . '</td>
							<td>' . number_format($row_ft_total['total_ft'], 2) . '</td>
							<td>' . number_format($maintaining) . '</td>
							<td>' . number_format($expense, 2) . '</td>
							<td>' . number_format($add_fund, 2) . '</td>
							<td>' . number_format($total_fund, 2) . '</td>
							<td>' . number_format($tansferred, 2) . '</td>
						</tr>';
                                                                $total_weekly_budget += round($row_chk['budget'], 2);
                                                                $total_weekly_total += round($row_ft_total['total_ft'], 2);
                                                            }
                                                            ?>	
                                                            <tr>
                                                                <td style="background-color:#FFFF00;">Total</td>
                                                                <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($total_weekly_budget, 2); ?><div></td>
                                                                            <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($total_weekly_total, 2); ?><div></td>
                                                                                        <td style="background-color:#FFFF00;"><div id="t_mbal"><?php echo number_format($t_mbal, 2); ?><div></td>
                                                                                                    <td style="background-color:#FFFF00;"><div id="t_ex"><?php echo number_format($t_exp, 2); ?></div></td>
                                                                                                    <td style="background-color:#FFFF00;"><div id="t_reval"><?php echo number_format($t_remain, 2); ?></td>
                                                                                                    <td style="background-color:#FFFF00;"><div id="t_fundadd"><?php echo number_format($t_add_fund, 2); ?></div></td>
                                                                                                    <td style="background-color:#FFFF00;"><div id="t_fund"><?php echo number_format($ofr, 2); ?></div></td>
                                                                                                    <td style="background-color:#FFFF00;"><?php echo number_format($t_tansferred, 2); ?></td>
                                                                                                    </tr> 
                                                                                                    <?php
                                                                                                }
                                                                                                ?>

                                                                                                <tr>
                                                                                                    <td colspan="7"><center><h3>Available Fund: <font size="+3"><?php echo number_format($row_av['amount'], 2); ?></font></h3></center></td>
                                                                                                </tr>

                                                                                                </table>
                                                                                                <br />
                                                                                                <input type="button" value=" Print " onclick="print();" class="printButtonClass">
                                                                                                <br />
                                                                                                </center>
                                                                                            </div>
