<?php
//define("MAJOR", 'pounds');
//define("MINOR", 'p');
//class toWords {
//    var $pounds;
//    var $pence;
//    var $major;
//    var $minor;
//    var $words = '';
//    var $number;
//    var $magind;
//    var $units = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
//    var $teens = array('ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
//    var $tens = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
//    var $mag = array('', 'thousand', 'million', 'billion', 'trillion');
//
//    function toWords($amount, $major = MAJOR, $minor = MINOR) {
//        $this->__toWords__((int)($amount), $major);
//        $whole_number_part = $this->words;
//        #$right_of_decimal = (int)(($amount-(int)$amount) * 100);
//        $strform = number_format($amount,2);
//        $right_of_decimal = (int)substr($strform, strpos($strform,'.')+1);
//        $this->__toWords__($right_of_decimal, $minor);
//        $this->words = $whole_number_part . ' ' . $this->words;
//    }
//
//    function __toWords__($amount, $major) {
//        $this->major  = $major;
//        #$this->minor  = $minor;
//        $this->number = number_format($amount, 2);
//        list($this->pounds, $this->pence) = explode('.', $this->number);
//        $this->words = " $this->major";
//        if ($this->pounds == 0)
//            $this->words = "Zero $this->words";
//        else {
//            $groups = explode(',', $this->pounds);
//            $groups = array_reverse($groups);
//            for ($this->magind = 0; $this->magind < count($groups); $this->magind++) {
//                if (($this->magind == 1) && (strpos($this->words, 'hundred') === false) && ($groups[0] != '000'))
//                    $this->words = ' and ' . $this->words;
//                $this->words = $this->_build($groups[$this->magind]) . $this->words;
//            }
//        }
//    }
//
//    function _build($n) {
//        $res = '';
//        $na  = str_pad("$n", 3, "0", STR_PAD_LEFT);
//        if ($na == '000')
//            return '';
//        if ($na{0} != 0)
//            $res = ' ' . $this->units[$na{0}] . ' hundred';
//        if (($na{1} == '0') && ($na{2} == '0'))
//            return $res . ' ' . $this->mag[$this->magind];
//        $res .= $res == '' ? '' : ' and';
//        $t = (int) $na{1};
//        $u = (int) $na{2};
//        switch ($t) {
//            case 0:
//                $res .= ' ' . $this->units[$u];
//                break;
//            case 1:
//                $res .= ' ' . $this->teens[$u];
//                break;
//            default:
//                $res .= ' ' . $this->tens[$t] . ' ' . $this->units[$u];
//                break;
//        }
//        $res .= ' ' . $this->mag[$this->magind];
//        return $res;
//    }
//}
//$amount = 12345;
//$obj    = new toWords($amount);
//echo $obj->words; // gives twelve thousand three hundred and forty five  pounds  sixty seven  p
//echo '<br/>';
//echo $obj->number; // gives 12,345.67



?>

<?php

function convertNumber($number) {
    list($integer, $fraction) = explode(".", (string) $number);

    $output = "";

    if ($integer{0} == "-") {
        $output = "negative ";
        $integer    = ltrim($integer, "-");
    }
    else if ($integer{0} == "+") {
        $output = "positive ";
        $integer    = ltrim($integer, "+");
    }

    if ($integer{0} == "0") {
        $output .= "zero";
    }
    else {
        $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
        $group   = rtrim(chunk_split($integer, 3, " "), " ");
        $groups  = explode(" ", $group);

        $groups2 = array();
        foreach ($groups as $g) {
            $groups2[] = convertThreeDigit($g{0}, $g{1}, $g{2});
        }

        for ($z = 0; $z < count($groups2); $z++) {
            if ($groups2[$z] != "") {
                $output .= $groups2[$z] . convertGroup(11 - $z) . (
                        $z < 11
                                && !array_search('', array_slice($groups2, $z + 1, -1))
                                && $groups2[11] != ''
                                && $groups[11]{0} == '0'
                        ? " and "
                        : ", "
                );
            }
        }

        $output = rtrim($output, ", ");
    }

    if ($fraction > 0) {
        $output .= " and ";
        for ($i = 0; $i < strlen($fraction); $i++) {
            $output .= $fraction{$i};
        }
    }

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
            $buffer .= " and ";
        }
    }

    if ($digit2 != "0") {
        $buffer .= convertTwoDigit($digit2, $digit3);
    }
    else if ($digit3 != "0") {
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

$num = 500254.89;
$cents = "/100 only";
echo $num."<br>";

$test = strtoupper(convertNumber($num));
$test .=strtoupper($cents);

echo $test;

?>
