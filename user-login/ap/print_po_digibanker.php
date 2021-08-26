<style>
    table {
        letter-spacing: 2px;
        margin-top: -3px;
        font-size: 9px;
        font-weight: Light;
        font-family: Copperplate Gothic Light, Arial;
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
        $sql_payment = mysql_query("SELECT * FROM temp_payment WHERE user_id='" . $_SESSION['user_id'] . "'");
        $rs_payment = mysql_fetch_array($sql_payment);

        $sql_code = mysql_query("SELECT * FROM company WHERE id='1'");
        $rs_code = mysql_fetch_array($sql_code);

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

        echo "<center>";
        echo "<div>";
        echo "<table height='350' border='0' width='720'>";
        echo "<tr>";
        echo "<td style='vertical-align: top;' height='60' align='center'>";
        echo "<table border='0' width='700' cellspacing='1'>";
        echo "<tr>";
        echo "<td height='15'></td>";
        echo "<td></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td height='19'><b>Account Name: </b>" . strtoupper(utf8_decode($rs_payment['cheque_name'])) . "</td>";
        echo "<td><b>Date:</b> " . date("M") . "&nbsp;" . date("d") . ", " . date("Y") . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td height='19'><b>Account No.: </b> " . $rs_payment['account_number'] . "</td>";
        echo "<td><b>Voucher No.:</b> SBC_" . $rs_code['code'] . "" . $rs_payment['voucher_no'] . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td height='15'><b>Amount: </b> $amount</td>";
        echo "<td><b>Bank:</b> " . $rs_payment['cheque_no'] . "</td>";
        echo "</tr>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='vertical-align: top;' height='230' align='center'>";
        $details = $_GET['trans_id'];
        $que = preg_split("/[_]/", $details);
//    echo "<br>";
        echo "<table border='0' height='210' width='650' cellspacing='1'>";
        echo "<tr>";
        echo "<td style='vertical-align: top;' colspan='4'>";
        echo "<br><br>";
        echo "<table border='0' width='650'>";
        if (!empty($que[1])) {
            echo "<tr>";
            echo "<td colspan='2' align='center'>CONSOLIDATED PAYMENT</td>";
            echo "<td>&nbsp;</td>";
            echo "<td>&nbsp;</td>";
            echo "</tr>";
        }
        echo "<tr>";
        echo "<td><b>WP GRADE</b></td>";
        echo "<td align='right'><b>WEIGHT</b></td>";
        echo "<td align='right'><b>PRICE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "<td align='right'><b>AMOUNT</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "</tr>";
        $tr = "";
        $sub_total = 0;
        foreach ($que as $trans_id) {
            if ($tr == "") {
                $tr = "scale_receiving.trans_id=" . $trans_id;
            } else {
                $tr .= " or scale_receiving.trans_id=" . $trans_id;
            }
        }

        $sql = "SELECT material_id, sum(corrected_weight) , price, sum(amount) FROM  `scale_receiving` INNER JOIN scale_receiving_details ON scale_receiving.trans_id = scale_receiving_details.trans_id WHERE " . $tr . " GROUP BY material_id, price";
        $sql_details = mysql_query($sql);
        while ($rs_details = mysql_fetch_array($sql_details)) {
            $sql_mat = mysql_query("SELECT * FROM material WHERE material_id='" . $rs_details['material_id'] . "'");
            $rs_mat = mysql_fetch_array($sql_mat);
            echo "<tr>";
            echo "<td width='160'>" . $rs_mat['code'] . "</td>";
            echo "<td width='150' align='right'>" . number_format($rs_details['sum(corrected_weight)'], 2) . "</td>";
            if (!empty($rs_details['price'])) {
                echo "<td width='150' align='right'>" . number_format($rs_details['price'], 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            } else {
                echo "<td width='150' align='right'> </td>";
            }
            echo "<td align='right'>" . number_format($rs_details['sum(amount)'], 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
            $sub_total+=$rs_details['sum(amount)'];
            echo "</tr>";
            $total+=$rs_details['sum(corrected_weight)'];
        }
        echo "</table>";
        echo "<br>";
        echo "<table border='0' cellspacing='1'>";
        echo "<tr>";
        echo "<td align='center' colspan='4'><b>ADJUSTMENTS</b></td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td width='160' style='vertical-align: top;'><b>TYPE</b></td>";
        echo "<td width='150' align='right' style='vertical-align: top;'><b>DESC</b></td>";
        echo "<td width='150' align='right' style='vertical-align: top;'></td>";
        echo "<td width='150' align='right' style='vertical-align: top;'><b>AMOUNT</b></td>";
        echo "</tr>";
        $sql_adj = mysql_query("SELECT * FROM temp_payment_adjustment WHERE user_id='" . $_SESSION['user_id'] . "' and adj_type!=''");
        while ($rs_adj = mysql_fetch_array($sql_adj)) {
            if ($rs_adj['adj_type'] != "") {
                echo "<tr>";
                echo "<td width='160' style='vertical-align: top;'>" . strtoupper($rs_adj['adj_type']) . "</td>";
                echo "<td width='150' align='right' style='vertical-align: top;'>" . strtoupper($rs_adj['desc']) . "</td>";
                echo "<td width='130' align='right' style='vertical-align: top;'></td>";
                echo "<td width='120' align='right' style='vertical-align: top;'>";
                if ($rs_adj['adj_type'] == "deduct") {
                    if ($rs_adj['amount'] != "") {
                        echo "(" . number_format($rs_adj['amount'], 2) . ")";
                    }
                } else {
                    if ($rs_adj['amount'] != "") {
                        echo number_format($rs_adj['amount'], 2);
                    }
                }
                echo "&nbsp;&nbsp;</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td width='120' style='vertical-align: bottom;'><b>TOTAL</b></td>";
        echo "<td width='120' align='right' style='vertical-align: bottom;'>" . number_format($total, 2) . "</td>";
        echo "<td width='130' align='right' style='vertical-align: bottom;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "<td width='120' align='right' style='vertical-align: bottom;'>" . number_format($rs_payment['grand_total'], 2) . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        echo "</tr>";
        echo "</table>";
        echo "<table>";
        echo "<tr>";
        echo "</table>";
        echo "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td style='vertical-align: top;' align='center'>";
        echo "<table border='0' width='650'>";
        echo "<tr>";
        echo "<td>";
        echo "<div style='margin-left: -20px; margin-top: 0px;'>";
        echo "<br><br><br>";

        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_payment['ap'] . "'");
        $rs_sig = mysql_fetch_array($sql_sig);

        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Prepared By: </b>" . $rs_sig['initial'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

        $sql_sig = mysql_query("SELECT * FROM users WHERE user_id='" . $rs_payment['signatory'] . "'");
        $rs_sig = mysql_fetch_array($sql_sig);

        echo "<b>Audited By: </b>" . $rs_sig['initial'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
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
<!--
<script>
    window.close();
</script> -->