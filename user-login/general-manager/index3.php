<?php 

//include('/../../query_builder/queryBuilder.php');

$hostname = '10.151.16.58';
$dbname = 'efi_pamp';
$username = 'branches';
$password = 'enviro101';

$dsn = "mysql:host={$hostname};dbname={$dbname}";

$pdo = new PDO($dsn, $username, $password);

$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

function fetch($query, $params) {

    global $pdo;

    $q = $pdo->prepare($query);
    $q->execute($params);
    return $q->fetchAll();

}

function getFirst($query, $params) {

    global $pdo;

    $q = $pdo->prepare($query);
    $q->execute($params);
    $response = $q->fetchAll();

    if (count($response) > 0) {
        return $response[0];
    }

    return null;

}

@session_start();

if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}


if (isset($_POST['submit'])) {
    $from = $_POST['from'];
    $to = $_POST['to'];
    $month = date('Y/m', strtotime($to));
} else {
    $from = date('Y/m/d');
    $to = date("Y/m/d");
    $month = date("Y/m");
}

$target_array = array();
$branch_total_target = array();
$branch_standings = array();

$actual_array = array();
$branch_total_actual = array();

$grand_total_actual = array();
$grand_total_target = array();

$target_total = 0;
$actual_total = 0;


$queryGrades = "SELECT * FROM material WHERE class='1';";
$grades = fetch($queryGrades, null);

$queryBranches = "SELECT * FROM branches;";
$branches = fetch($queryBranches, null);


function getTargetByBranch($branch, $grade, $month) {

    return getFirst("SELECT sum(target) as target FROM monthly_target WHERE branch_id='" . $branch->branch_id . "' and material_id='" . $grade->material_id . "' and month='$month'", null);
}


function tipcoActual($branch, $grade, $from, $to) {

    $query = "
    SELECT sum(details.corrected_weight) as actual FROM scale_outgoing as outgoing
    INNER JOIN scale_outgoing_details as details ON outgoing.trans_id=details.trans_id 
    WHERE outgoing.checked='1' and outgoing.branch_id='" . $branch->branch_id . "' 
    AND outgoing.dt_id != 3 
    AND (details.material_id='" . $grade->material_id . "' OR details.material_id IN (
        SELECT material_id FROM material WHERE class = 2 AND under_by = '".$grade->material_id."'
    )) 
    AND (outgoing.date>='$from' AND outgoing.date<='$to')";


    return getFirst($query, null);
}

function fsiActual($branch, $grade, $from, $to) {

    $query = "
    SELECT sum(details.corrected_weight) as actual FROM scale_outgoing as outgoing
    INNER JOIN scale_outgoing_details as details ON outgoing.trans_id=details.trans_id 
    WHERE outgoing.checked='1' and outgoing.branch_id='" . $branch->branch_id . "' 
    AND outgoing.dt_id = 3 
    AND (details.material_id='" . $grade->material_id . "' OR details.material_id IN (
        SELECT material_id FROM material WHERE class = 2 AND under_by = '".$grade->material_id."'
    )) 
    AND (outgoing.date>='$from' AND outgoing.date<='$to')";


    return getFirst($query, null);
}

foreach ($branches as $branch) {
    $branch_total_target[$branch->branch_name] = 0;
    $branch_total_actual[$branch->branch_name] = 0;
}

foreach ($grades as $grade) {
    $grand_total_target[$grade->code] = 0;
    $grand_total_actual['fsi'][$grade->code] = 0;
    $grand_total_actual['tipco'][$grade->code] = 0;
}


// Set and compute all the values
foreach ($branches as $branch) {

    foreach ($grades as $grade) {


        // ============== Target Sales ==========================

        $target = getTargetByBranch($branch, $grade, $month);

        if($target->target) {
            $target_array[$branch->branch_name][$grade->code] = $target->target;
        } else {
            $target_array[$branch->branch_name][$grade->code] = 0;
        }

        $branch_total_target[$branch->branch_name] += (int) $target_array[$branch->branch_name][$grade->code];

        $grand_total_target[$grade->code] += (int) $target_array[$branch->branch_name][$grade->code];


        $target_total += (int) $target_array[$branch->branch_name][$grade->code];
        // =============== End Target Sales ======================

        // =============== Actual Sales ==========================
        $actualByTipco = tipcoActual($branch, $grade, $from, $to);
        $actualByFsi = fsiActual($branch, $grade, $from, $to);


        if($actualByTipco->actual) {
            $actual_array['tipco'][$branch->branch_name][$grade->code] = round($actualByTipco->actual / 1000) ;
            $branch_total_actual[$branch->branch_name] += $actual_array['tipco'][$branch->branch_name][$grade->code];

            $grand_total_actual['tipco'][$grade->code] += $actual_array['tipco'][$branch->branch_name][$grade->code];

            $actual_total += $actual_array['tipco'][$branch->branch_name][$grade->code];

        } else {
            $actual_array['tipco'][$branch->branch_name][$grade->code] = 0;
        }

        if($actualByFsi->actual) {
            $actual_array['fsi'][$branch->branch_name][$grade->code] = round($actualByFsi->actual / 1000);
            $branch_total_actual[$branch->branch_name] += $actual_array['fsi'][$branch->branch_name][$grade->code];

            $grand_total_actual['fsi'][$grade->code] += $actual_array['fsi'][$branch->branch_name][$grade->code];

            $actual_total += $actual_array['fsi'][$branch->branch_name][$grade->code];
        } else {
            $actual_array['fsi'][$branch->branch_name][$grade->code] = 0;
        }


        // =============== End Actual Sales ======================

    }
    
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
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <script src="js/pending.js" type="text/javascript"></script>
        <script src="js/pending_outgoing.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="css/tcal.css" />
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="./MyMenu1/MyMenu1.js"></script>
        <link href='css/sNotify.css' rel='stylesheet' type='text/css' />
        <script src="js/sNotify.js" type="text/javascript"></script>

        <script>
            sNotify.addToQueue("You have 1 advances request to verify");
        </script>
        <style>
            table{
                font-size: 15px;
            }
            td{
                padding-left: 7px;
                padding-right: 7px;
            }
            .td_bold{
                font-weight: bold;
            }
            .blue{
                font-weight: bold;
                background-color: #8ea9db;
            }
            .yellow{
                font-weight: bold;
                background-color: #ffff00;
            }
            .orange{
                font-weight: bold;
                background-color: #ffc000;
            }
            .peach{
                background-color: #fce4d6;
            }
            .grey{
                font-weight: bold;
                background-color: #dbdbdb
            }
            .grey2{
                background-color: #dbdbdb
            }
        </style>
    </head>
    <body>

        <div class="wrapper">

            <header class="header">
                <?php
                include 'template/header.php';
                ?>
            </header>

            <div class="middle" align="center">
                <?php
                include 'template/menu.php';
                ?>
                <br>
                <h2>EFI Delivery Performance</h2>
                (TIPCO, MULTIPLY & FSI)
                <br>
                <br>
                <form action="index3.php" method="POST">
                    From: 
                    <input class="tcal" type="text" name="from" value="<?php echo $from; ?>" size="10" required>
                     To: <input class="tcal" type="text" name="to" value="<?php echo $to; ?>" size="10" required> <input type="submit" name="submit" value="Submit">
                </form>
                <br>

                <table border='1'>

                    <tr class='blue'>
                        <td colspan="<?php echo count($grades) + 4 ?>" align='center'>
                        EFI Overall as for 
                        <?php echo date("F d", strtotime($from)) . " to " . date("d, Y", strtotime($to)) ?>
                    </td>
                    </tr>

                    <tr class='grey'>
                        <td></td>

                        <?php foreach ($grades as $grade): ?>

                            <?php if ($grade->code === 'LCMW'): ?>
                            <td><?php echo $grade->code ?> - TIPCO</td>
                            <td><?php echo $grade->code ?> - FSI</td>
                            <?php else: ?>
                            <td><?php echo $grade->code ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>

                        <td class='yellow'>TOTAL</td>
                        <td class='orange'></td>

                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>TARGET</td>
                        <?php foreach ($grades as $grade): ?>

                            <?php if ($grade->code === 'LCMW'): ?>
                            <td colspan='2'><?php echo $grand_total_target[$grade->code] ?></td>
                            <?php else: ?>
                            <td><?php echo $grand_total_target[$grade->code] ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>

                        <td class='yellow'><?php echo round($target_total) ?></td>
                        <td class='orange'>0</td>
                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>ACTUAL</td>
                        <?php foreach ($grades as $grade): ?>

                            <?php if ($grade->code === 'LCMW'): ?>
                            <td><?php echo $grand_total_actual['tipco'][$grade->code] ?></td>
                            <td><?php echo $grand_total_actual['fsi'][$grade->code] ?></td>
                            <?php else: ?>
                            <td><?php echo $grand_total_actual['tipco'][$grade->code] ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>
                        <td class='yellow'><?php echo round($actual_total) ?></td>
                        <td class='orange'>
                        <?php

                        $_standing_total = 0;

                        if($actual_total > 0 && $target_total > 0) {
                            $_standing_total = round(($actual_total / $target_total) * 100);
                        }
                        

                        echo $_standing_total . ' %';

                        ?>
                        </td>
                    </tr>
                </table>


                <br>
                <br>
                <br>


                <table border="1">

                    <!-- Per Branch -->
                    <?php foreach ($branches as $branch): ?>

                    <tr class='blue'>
                        <td colspan="<?php echo count($grades) + 4 ?>" align='center'><?php echo $branch->branch_name ?> MTD</td>
                    </tr>

                    <tr class='grey'>
                        <td></td>

                        <?php foreach ($grades as $grade): ?>

                            <?php if ($grade->code === 'LCMW'): ?>
                            <td><?php echo $grade->code ?> - TIPCO</td>
                            <td><?php echo $grade->code ?> - FSI</td>
                            <?php else: ?>
                            <td><?php echo $grade->code ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>

                        <td class='yellow'>TOTAL</td>    
                        <!-- code by john felix :D // bbnnhhh.khhhhh<!-- ?\ -->
                        <td class='orange'></td>

                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>TARGET</td>
                        <?php foreach ($grades as $grade): ?>

                            <?php if ($grade->code === 'LCMW'): ?>
                            <td colspan='2'><?php echo $target_array[$branch->branch_name][$grade->code] ?></td>
                            <?php else: ?>
                            <td><?php echo $target_array[$branch->branch_name][$grade->code] ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>

                        <td class='yellow'><?php echo round($branch_total_target[$branch->branch_name]) ?></td>
                        <td class='orange'></td>
                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>ACTUAL</td>
                        <?php foreach ($grades as $grade): ?>

                            <?php if ($grade->code === 'LCMW'): ?>
                            <td><?php echo $actual_array['tipco'][$branch->branch_name][$grade->code] ?></td>
                            <td><?php echo $actual_array['fsi'][$branch->branch_name][$grade->code] ?></td>
                            <?php else: ?>
                            <td><?php echo $actual_array['tipco'][$branch->branch_name][$grade->code] ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>
                        <td class='yellow'><?php echo round($branch_total_actual[$branch->branch_name]) ?></td>
                        <td class='orange'>
                        <?php
                        $_target = (int) $branch_total_target[$branch->branch_name];
                        $_actual = (int) $branch_total_actual[$branch->branch_name];
                        $_standing = 0;


                        if($_actual > 0 && $_target > 0) {
                            $_standing = round(($_actual/$_target) * 100);
                        }
                        

                        echo $_standing . ' %';

                        ?>
                            
                        </td>
                    </tr>
                    <?php endforeach ?>


                </table>


                <br>
                <br>
            </div><!-- .middle-->

            <footer class="footer">
                <?php include 'template/footer.php'; ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
