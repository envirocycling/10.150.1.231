<style>
    table {
        margin-top: -3px;
        letter-spacing: 4px;
        font-size: 8px;
        font-weight: 300;
        /*font-weight: Light;*/
        font-family: Copperplate Gothic Light, Arial;
        /*font-family: "Times New Roman";*/
    }
    body{
        font-size: 7px;
        /*font-weight: Light;*/
        font-family: "Times New Roman";
    }
</style>
<script>
    print();
</script>
<body>
    <?php
    @session_start();
    date_default_timezone_set("Asia/Singapore");
    include 'config.php';
    if (!isset($_SESSION['user_id'])) {
        echo "<script>";
        echo "alert('Session is not set, Please refresh your browser and login again.');";
        echo "window.close();";
        echo "</script>";
    } else {

        $sql_payment = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
        $rs_payment = mysql_fetch_array($sql_payment);

// conversion

        function convertNumber($number) {
            list($integer, $fraction) = explode(".", (string) $number);

            $output = "";

            if ($integer{0} == "-") {
                $output = "negative ";
                $integer = ltrim($integer, "-");
            } else if ($integer{0} == "+") {
                $output = "positive ";
                $integer = ltrim($integer, "+");
            }

            if ($integer{0} == "0") {
                $output .= "zero";
            } else {
                $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
                $group = rtrim(chunk_split($integer, 3, " "), " ");
                $groups = explode(" ", $group);

                $groups2 = array();
                foreach ($groups as $g) {
                    $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
                }

                for ($z = 0; $z < count($groups2); $z++) {
                    if ($groups2[$z] != "") {
                        $output .= $groups2[$z] . convertGroup(11 - $z) . (
                                $z < 11 && !array_search('', array_slice($groups2, $z + 1, -1)) && $groups2[11] != '' && $groups[11]{0} == '0' ? " " : ", "
                                );
                    }
                }

                $output = rtrim($output, ", ");
            }

//        if ($fraction > 0) {
//            $output .= " point";
//            for ($i = 0; $i < strlen($fraction); $i++) {
//                $output .= " " . convertDigit($fraction{$i});
//            }
//        }

            return $output;
        }

        function convertGroup($index) {
            switch ($index) {
                case 11:
                    return " decillion";
                case 10:
                    return " nonillion";
                case 9:
                    return " octillion";
                case 8:
                    return " septillion";
                case 7:
                    return " sextillion";
                case 6:
                    return " quintrillion";
                case 5:
                    return " quadrillion";
                case 4:
                    return " trillion";
                case 3:
                    return " billion";
                case 2:
                    return " million";
                case 1:
                    return " thousand";
                case 0:
                    return "";
            }
        }

        function convertThreeDigit($digit1, $digit2, $digit3) {
            $buffer = "";

            if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0") {
                return "";
            }

            if ($digit1 != "0") {
                $buffer .= convertDigit($digit1) . " hundred";
                if ($digit2 != "0" || $digit3 != "0") {
                    $buffer .= " ";
                }
            }

            if ($digit2 != "0") {
                $buffer .= convertTwoDigit($digit2, $digit3);
            } else if ($digit3 != "0") {
                $buffer .= convertDigit($digit3);
            }

            return $buffer;
        }

        function convertTwoDigit($digit1, $digit2) {
            if ($digit2 == "0") {
                switch ($digit1) {
                    case "1":
                        return "ten";
                    case "2":
                        return "twenty";
                    case "3":
                        return "thirty";
                    case "4":
                        return "forty";
                    case "5":
                        return "fifty";
                    case "6":
                        return "sixty";
                    case "7":
                        return "seventy";
                    case "8":
                        return "eighty";
                    case "9":
                        return "ninety";
                }
            } else if ($digit1 == "1") {
                switch ($digit2) {
                    case "1":
                        return "eleven";
                    case "2":
                        return "twelve";
                    case "3":
                        return "thirteen";
                    case "4":
                        return "fourteen";
                    case "5":
                        return "fifteen";
                    case "6":
                        return "sixteen";
                    case "7":
                        return "seventeen";
                    case "8":
                        return "eighteen";
                    case "9":
                        return "nineteen";
                }
            } else {
                $temp = convertDigit($digit2);
                switch ($digit1) {
                    case "2":
                        return "twenty-$temp";
                    case "3":
                        return "thirty-$temp";
                    case "4":
                        return "forty-$temp";
                    case "5":
                        return "fifty-$temp";
                    case "6":
                        return "sixty-$temp";
                    case "7":
                        return "seventy-$temp";
                    case "8":
                        return "eighty-$temp";
                    case "9":
                        return "ninety-$temp";
                }
            }
        }

        function convertDigit($digit) {
            switch ($digit) {
                case "0":
                    return "zero";
                case "1":
                    return "one";
                case "2":
                    return "two";
                case "3":
                    return "three";
                case "4":
                    return "four";
                case "5":
                    return "five";
                case "6":
                    return "six";
                case "7":
                    return "seven";
                case "8":
                    return "eight";
                case "9":
                    return "nine";
            }
        }

        $num = round($rs_payment['grand_total'], 2);
        $paymentsss = $rs_payment['grand_total'] . ".00";
        $check = preg_split("/[.]/", $num);

        $amount = strtoupper(convertNumber($paymentsss));
        if (empty($check[1])) {
            $cents = " and 00/100 only";
            $amount .=strtoupper($cents);
        } else {
            if (strlen($check[1]) == 1) {
                $cents = " and $check[1]0/100 only";
            } else {
                $cents = " and $check[1]/100 only";
            }
            $amount .=strtoupper($cents);
        }

// Conversion

        $total = 0;

        $sql_bank = mysql_query("SELECT * FROM bank_accounts WHERE bank_code='" . $rs_payment['bank_code'] . "'");
        $rs_bank = mysql_fetch_array($sql_bank);

        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $rs_bank['branch_id'] . "'");
        $rs_comp = mysql_fetch_array($sql_branch);
        echo "<center>";
        echo "<div style='margin-left: 70px; margin-top: 0px;'>";
        echo "<table height='350px' border='0' width='750'>";
        echo "<tr>";
        echo "<td style='vertical-align: top;' height='60' align='center'>";
        echo "<table border='0' width='695' cellspacing='1'>";
        echo "<tr>";
        echo "<td height='15' align='center'>";
        if ($rs_bank['id'] != '8' && $rs_bank['id'] != '9') {
            $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_id='" . $rs_bank['branch_id'] . "'");
            $rs_comp = mysql_fetch_array($sql_branch);

            echo $rs_comp['company_name'];
        }
        echo "</td>";
// eto ung date
        echo "<td align='right widht='100'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . date("M", strtotime($rs_payment['cheque_date'])) . "&nbsp;&nbsp;&nbsp;&nbsp;" . date("d", strtotime($rs_payment['cheque_date'])) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . date("y", strtotime($rs_payment['cheque_date'])) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td height='20' align='center'>* * * " . strtoupper($rs_payment['cheque_name']) . " * * *</td>";
// eto ung voucher number
        echo "<td align='right'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $rs_payment['voucher_no'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td height='20' align='center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$amount</td>";
// eto ung cheque number
        echo "<td align='right'>";
        if ($rs_payment['old_cheque_no'] != '') {
            echo $rs_payment['cheque_no'];
        }
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td height='15'></td>";
        echo "<td align='right'>";
        if ($rs_payment['old_cheque_no'] != '') {
            echo $rs_payment['old_cheque_no'];
        } else {
            echo $rs_payment['cheque_no'];
        }
        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "</tr>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='vertical-align: top;' height='230' align='center'>";
        echo "<br>";
        echo "<table border='0' height='210' width='650' cellspacing='1'>";
        if ($rs_payment['description'] != '') {
			$sql_payment_ewt = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "' and particulars LIKE '%EWT%'");
			$sql_payment_ewt2 = mysql_query("SELECT * FROM temp_payment_others WHERE user_id='" . $_SESSION['user_id'] . "' and particulars NOT LIKE '%EWT%'");
				if(mysql_num_rows($sql_payment_ewt) > 0){
				//echo "===================";
					while($row_ewt = mysql_fetch_array($sql_payment_ewt2)){
						$sub_total += $row_ewt['amount'];
					}
				}else{		
					$sub_total = $rs_payment['grand_total'];
				}
			
            echo "<tr>";
            echo "<td height='20' width='200' style='vertical-align: bottom;'>" . strtoupper($rs_payment['description']) . "</td>";
            echo "<td width='120' style='vertical-align: bottom;'></td>";
            echo "<td width='130' align='center' style='vertical-align: bottom;'></td>";
            echo "<td width='120' align='center' style='vertical-align: bottom;'>" . number_format($sub_total, 2) . "</td>";
            echo "</tr>";
        }
        echo "<tr>";
        echo "<td style='vertical-align: top;' colspan='4'>";
        echo "<br>";
        echo "<table border='0' cellspacing='1'>";
        $sql_adj = mysql_query("SELECT * FROM payment_others WHERE payment_id='" . $_GET['payment_id'] . "'");$sql_adj = mysql_query("SELECT * FROM payment_others WHERE payment_id='" . $_GET['payment_id'] . "'");
        while ($rs_adj = mysql_fetch_array($sql_adj)) {
            if ($rs_adj['description'] != "") {
                echo "<tr>";
                echo "<td width='200' style='vertical-align: top; padding-left:20px;'>" . strtoupper($rs_adj['description']) . "</td>";
                echo "<td width='120' style='vertical-align: top;'>" . number_format($rs_adj['quantity'], 2) . "</td>";
                echo "<td width='130' align='center' style='vertical-align: top;'>" . number_format($rs_adj['unit_price'], 2) . "</td>";
                echo "<td width='120' align='right' style='vertical-align: top;'>" . number_format($rs_adj['amount'], 2) . "</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td width='200' style='vertical-align: bottom;'>TOTAL</td>";
        echo "<td width='120' style='vertical-align: bottom;'></td>";
        echo "<td width='130' align='center' style='vertical-align: bottom;'></td>";
        echo "<td width='120' align='center' style='vertical-align: bottom;'>" . number_format($rs_payment['grand_total'], 2) . "</td>";
        echo "</tr>";
        echo "</table>";
        echo "<table>";
        echo "<tr>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>";
        echo "<div style='margin-left: -20px; margin-top: 5px;'>";
        echo "<br><br><br><br>";
        $sig_array = array();
        array_push($sig_array, $rs_payment['ap']);
        array_push($sig_array, $rs_payment['verifier']);
        array_push($sig_array, $rs_payment['signatory']);
        echo "<table border='0' width='650'>";
        echo "<td>";
//    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        foreach ($sig_array as $sig) {
            $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='$sig'");
            $rs_sig = mysql_fetch_array($sql_sig);
            echo $rs_sig['initial'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        }
        echo "</div>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";
        echo "</div>";
        echo "</center>";
    }
    ?>
</body>
<!--<script>
    window.close();
</script> -->