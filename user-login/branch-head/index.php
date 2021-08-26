<?php 

require_once '/var/www/html/paymentsystem/config/query_builder.php'; // change this

@session_start();

if (!isset($_SESSION['bh_id'])) {
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

$from_m = date('Y/m', strtotime($from));
$to_m = date('Y/m', strtotime($to));


$total_target = array();
$total_actual = array();

$target_per_branch = array();
$actual_per_branch = array();

$target_grand_total = 0;
$actual_grand_total = 0;

$total_target_per_branch = array();
$total_actual_per_branch = array();

$queryGrades = "SELECT * FROM material WHERE class='1';";
$_grades = fetch($queryGrades, null);

$grades = array_map(function($grade) {
    return strtolower($grade->code);
}, $_grades);


$queryBranches = "SELECT * FROM branches where status != 'inactive';";
$_branches = fetch($queryBranches, null);

$branches = array_map(function($branch) {
    return strtolower($branch->branch_name);
}, $_branches);



$sqlOutgoingStr = "
SELECT scale_outgoing.*, 
branches.branch_name AS `branch`, 
dt.name AS `client`, 
sod.corrected_weight as `weight`, 
m.code AS `grade` 
FROM scale_outgoing 
INNER JOIN branches ON branches.branch_id=scale_outgoing.branch_id 
INNER JOIN delivered_to AS dt ON dt.dt_id=scale_outgoing.dt_id 
INNER JOIN scale_outgoing_details AS sod ON sod.trans_id=scale_outgoing.trans_id 
INNER JOIN material AS m ON m.material_id=sod.material_id 
WHERE scale_outgoing.date >= '{$from}' AND scale_outgoing.date <= '{$to}';
";
$outgoings = fetch($sqlOutgoingStr, null);

//Set initial value for total target and actual
foreach ($grades as $grade) {

    $total_target[$grade] = 0;

    if(($grade=== 'lcmw') || ($grade === 'lcocc')) {
        $total_actual[$grade]['x'] = 0;
        $total_actual[$grade]['fsi'] = 0;
    } else {
        $total_actual[$grade] = 0;
    }

}


// Set initial value for target and actual per branch
foreach ($branches as $branch) {

    $total_target_per_branch[$branch] = 0;
    $total_actual_per_branch[$branch] = 0;

    foreach ($grades as $grade) {

        $target_per_branch[$branch][$grade] = 0;

        if(($grade === 'lcmw') || ($grade === 'lcocc')) {
            $actual_per_branch[$branch][$grade]['x'] = 0;
            $actual_per_branch[$branch][$grade]['fsi'] = 0;
        } else {
            $actual_per_branch[$branch][$grade] = 0;
        }

    }
    
}


// Compute target
foreach($_branches as $branch) {

    $b = strtolower($branch->branch_name);

    foreach($_grades as $grade) {

        $g = strtolower(grade($grade->code));

        $sqlTargetStr = "SELECT SUM(target.target) as target, branches.branch_name as branch, material.code as grade FROM monthly_target AS target 
        INNER JOIN branches ON branches.branch_id = target.branch_id 
        INNER JOIN material ON material.material_id = target.material_id 
        WHERE branches.branch_id = {$branch->branch_id} AND material.material_id = {$grade->material_id} AND (target.month >= '{$from_m}' AND target.month <= '{$to_m}')";

        $target = getFirst($sqlTargetStr, null);

        if($target) {

            $_b = strtolower($target->branch);
            $_g = strtolower(grade($target->grade));

            $target_per_branch[$_b][$_g] += $target->target;
            $total_target_per_branch[$_b] += $target->target;
            $total_target[$_g] += $target->target;
            $target_grand_total += $target->target;

        } 
    }
}


if(count($outgoings)){


    // Compute actual in each branch per grade...
    foreach($outgoings as $outgoing) {

        $branch = strtolower($outgoing->branch);
        $deliveredTo = strtolower($outgoing->client);
        $grade = strtolower(grade($outgoing->grade));
        $weight = $outgoing->weight;

    
        if(($grade === 'lcmw') || ($grade === 'lcocc')) {

            if($deliveredTo == 'fsi') {

                $total_actual[$grade]['fsi'] += $weight;

                $actual_per_branch[$branch][$grade]['fsi'] += $weight;

            
            } else {

                $total_actual[$grade]['x'] += $weight;

                $actual_per_branch[$branch][$grade]['x'] += $weight;

            }
        
        } else {


            $total_actual[$grade] += $weight;

            $actual_per_branch[$branch][$grade] += $weight;

        }


        $actual_grand_total += $weight;

        $total_actual_per_branch[$branch] += $weight;
    }


} 


//dd_p($actual_per_branch);



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
                text-align: center;
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

                <form method="POST">
                    From: 
                    <input class="tcal" type="text" name="from" value="<?php echo $from; ?>" size="10" required>
                     To: <input class="tcal" type="text" name="to" value="<?php echo $to; ?>" size="10" required> <input type="submit" name="submit" value="Submit">
                </form>
                <br>

                <table border='1'>

                    <tr class='blue'>
                        <td colspan="<?php echo count($grades) + 6 ?>" align='center'>
                            EFI Overall as for 
                            <?php echo date("F d", strtotime($from)) . " to " . date("d, Y", strtotime($to)) ?>
                        </td>
                    </tr>

                    <tr class='grey'>
                        <td></td>

                        <?php foreach ($grades as $grade): ?>

                            <?php if (($grade === 'lcmw') || ($grade === 'lcocc')): ?>
                            <td><?php echo strtoupper($grade) ?> - TIPCO</td>
                            <td><?php echo strtoupper($grade) ?> - FSI</td>
                            <?php else: ?>
                            <td><?php echo strtoupper($grade)?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>

                        <td class='yellow'>TOTAL</td>

                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>TARGET</td>
                        <?php foreach ($grades as $grade): ?>

                            <?php if (($grade === 'lcmw') || ($grade === 'lcocc')): ?>
                            <td colspan='2'><?php echo round($total_target[$grade], 3)  ?></td>
                            <?php else: ?>
                            <td><?php echo $total_target[$grade] ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>

                        <td class='yellow'><?php echo $target_grand_total; ?></td>
                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>ACTUAL</td>
                        <?php foreach ($grades as $grade): ?>

                            <?php if (($grade === 'lcmw') || ($grade === 'lcocc')): ?>
                            <td><?php echo round(($total_actual[$grade]['x'] / 1000), 3) ?></td>
                            <td><?php echo round(($total_actual[$grade]['fsi'] / 1000), 3) ?></td>
                            <?php else: ?>
                            <td><?php echo round(($total_actual[$grade] / 1000), 3) ?></td>
                            <?php endif; ?>
                        
                        <?php endforeach ?>
                        <td class='yellow'><?php echo round(($actual_grand_total / 1000), 3); ?></td>
                    </tr>
                </table>


                <br>
                <br>
                <br>

                <table border="1">

                    <!-- Per Branch -->
                    <?php foreach ($branches as $branch): ?>

                    <tr class='blue'>
                        <td colspan="<?php echo count($grades) + 4 ?>" align='center'><?php echo strtoupper($branch) ?> MTD</td>
                    </tr>

                    <tr class='grey'>
                        <td></td>

                        <?php foreach ($grades as $grade): ?>

                        <?php if (($grade === 'lcmw') || ($grade === 'lcocc')): ?>
                        <td><?php echo strtoupper($grade) ?> - TIPCO</td>
                        <td><?php echo strtoupper($grade) ?> - FSI</td>
                        <?php else: ?>
                        <td><?php echo strtoupper($grade) ?></td>
                        <?php endif; ?>

                        <?php endforeach ?>

                        <td class='yellow'>TOTAL</td>    
                        <!-- code by john felix :D // bbnnhhh.khhhhh<!-- ?\ -->

                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>TARGET</td>
                        <?php foreach ($grades as $grade): ?>

                        <?php if (($grade === 'lcmw') || ($grade === 'lcocc')): ?>
                        <td colspan='2'><?php echo $target_per_branch[$branch][$grade] ?></td>
                        <?php else: ?>
                        <td><?php echo $target_per_branch[$branch][$grade] ?></td>
                        <?php endif; ?>

                        <?php endforeach ?>

                        <td class='yellow'><?php echo $total_target_per_branch[$branch]; ?></td>
                    </tr>

                    <tr class='grey2'>
                        <td class='td_bold'>ACTUAL</td>
                        <?php foreach ($grades as $grade): ?>

                        <?php if (($grade === 'lcmw') || ($grade === 'lcocc')): ?>
                        <td><?php echo round(($actual_per_branch[$branch][$grade]['x'] / 1000), 3) ?></td>
                        <td><?php echo round(($actual_per_branch[$branch][$grade]['fsi'] / 1000), 3) ?></td>
                        <?php else: ?>
                        <td><?php echo round(($actual_per_branch[$branch][$grade] / 1000), 3)  ?></td>
                        <?php endif; ?>

                        <?php endforeach ?>
                        <td class='yellow'><?php echo round(($total_actual_per_branch[$branch] / 1000), 3); ?></td>
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

