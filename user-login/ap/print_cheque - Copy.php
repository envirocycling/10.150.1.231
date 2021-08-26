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
<?php
@session_start();
date_default_timezone_set("Asia/Singapore");
include 'config.php';

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

if (!isset($_SESSION['user_id'])) {
    echo "<script>";
    echo "alert('Session is not set, Please refresh your browser and login again.');";
    echo "window.close();";
    echo "</script>";
} else {
    $sql_cheque = mysql_query("SELECT * FROM payment WHERE payment_id='" . $_GET['payment_id'] . "'");
    $rs_cheque = mysql_fetch_array($sql_cheque);

    $num = round($rs_cheque['grand_total'], 2);
    $paymentsss = $rs_cheque['grand_total'] . ".00";
    $php = number_format($rs_cheque['grand_total'], 2);
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
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
    } else {
        $date = '';
    }
    if (!empty($rs_cheque['cheque_date'])) {
        $date = $rs_cheque['cheque_date'];
    } else {
        $date = date("Y/m/d");
    }
// conversion
    echo "<br>";
    echo "<div style='margin-left: 80px; margin-top: 0px;'>";
    echo "<table width='600' border='0'>";
    echo "<tr height='15'>";
    echo "<td align='center'></td>";
    echo "<td><div margin-top: 10px;'>&nbsp;" . date("M", strtotime($date)) . "&nbsp;" . date("d", strtotime($date)) . ",&nbsp;" . date("Y", strtotime($date)) . "</div></td>";
    echo "</tr>";
    echo "<tr height='15'>";
    echo "<td align='center' width='480'>***" . strtoupper($rs_cheque['cheque_name']) . "***</td>";
    echo "<td width='120'>***$php***</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td align='center'>$amount</td>";
    echo "<td></td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>";
}
?>
<!--<script>
    window.close();
</script> -->