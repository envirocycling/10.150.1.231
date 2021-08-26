<?php
date_default_timezone_set("Asia/Manila");
session_start();

require_once '/var/www/html/paymentsystem/config/query_builder.php'; // change this

if (!isset($_SESSION['gm_id'])) {
    echo "<script>location.replace('../../');</script>";
}


$clients = fetch("SELECT * FROM `delivered_to`;", null);
$branches = fetch("SELECT * FROM `branches` WHERE `status` != 'inactive';", null);
$grades = fetch("SELECT * FROM material WHERE class='1';", null);

if(isset($_POST['submit'])) {

    $dt = $_POST['dt'];
    $b = $_POST['b'];
    $from = $_POST['from'];
    $to = $_POST['to'];

} else {

    $dt = '';
    $b = '';
    $from = date('Y/m/d');
    $to = date('Y/m/d');
}

$src = "iframe/query_delivery_performance.php?from={$from}&to={$to}&branch={$b}&delivered_to={$dt}";

// compute summary here
$summaries = array();
$total = 0;

// set initial value
foreach($grades as $grade) {
    $summaries[strtoupper($grade->code)] = 0;
}

$sql = "SELECT 
so.date,
so.supplier_id,
b.branch_name,
so.plate_number,
so.str_no,
so.tr_no,
so.series_no,
dt.name AS `client`,
m.code AS `grade`,
sod.net_weight AS `net_weight`,
sod.mc as `moisture`,
sod.dirt as `dirt`,
sod.corrected_weight AS `weight`,

sod.remarks
FROM scale_outgoing AS so 
INNER JOIN branches AS b ON b.branch_id = so.branch_id 
INNER JOIN delivered_to AS dt ON dt.dt_id = so.dt_id 
INNER JOIN scale_outgoing_details AS sod ON sod.trans_id = so.trans_id 
INNER JOIN material AS m ON m.material_id = sod.material_id 
WHERE (so.date >= '{$from}' AND so.date <= '{$to}') 
AND so.dt_id LIKE '%{$dt}%' 
AND so.branch_id LIKE '%{$b}%';";

$records = fetch($sql, null);

if(count($records) > 0) {
    foreach($records as $record) {

        $grade = grade($record->grade);
        $weight = $record->weight;

        $summaries[$grade] += $weight;

        $total += $weight;

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
        <link rel="shortcut icon" href="images/ts_logo.png"/>
        <link rel="stylesheet" type="text/css" href="css/tcal.css"/>
        <script type="text/javascript" src="js/tcal.js"></script>
        <script type="text/javascript" src="MyMenu1/MyMenu1.js"></script>

        <style>
            .summary_box {
                display: flex;
                justify-content: left;
                padding-left: 20px;
                width: 100%;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            <header class="header">
            <?php include 'template/header.php'; ?>
            </header><!-- .header-->

            <div class="middle" align="center">

                <?php include 'template/menu.php'; ?>

                <br>

                <h2>Delivery Performance Reports</h2>

                <br>

                <form method="POST">

                    <label for="delivered_to">Deliveries To: </label>
                    <select id='delivered_to' name='dt'>
                        <option value="">All</option>
                        <?php foreach($clients as $client): ?>
                        <option <?php echo $client->dt_id == $dt ? 'selected' : ''?> value="<?php echo $client->dt_id?>">
                        <?php echo $client->name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>

                     
                    <label for="branch">Branch:</label>
                    <select id='branch' name='b'>
                        <option value="">All</option>
                        <?php foreach($branches as $branch): ?>
                        <option <?php echo $branch->branch_id == $b ? 'selected' : ''?> value="<?php echo $branch->branch_id?>">
                        <?php echo $branch->branch_name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>


                    <label for="from">From:</label>
                    <input id="from" class="tcal" type="text" name="from" value="<?php echo $from; ?>" size="10" required />

                    <label for="to">To:</label>
                    <input id="to" class="tcal" type="text" name="to" value="<?php echo $to; ?>" size="10" required />

                    <input type="submit" name='submit' value="Generate Report">

                </form>


                <br>
                <br>

                <?php if(isset($_POST['submit'])): ?>

                <div class="summary_box">

                    <table border="1">
                        <thead style="background: skyblue;">
                            <tr><th colspan="2">WP SUMMARY</th></tr>
                            <tr>
                                <th>WP GRADE</th>
                                <th>WEIGHT</th>
                            </tr>
                        </thead>

                        <tbody style="text-align: center;">
                            <?php foreach($summaries as $g => $v): ?>
                            <tr>
                                <td width="140px"><?php echo $g; ?></td>
                                <td width="120px"><?php echo round($v, 3); ?></td>
                            </tr>
                            <?php endforeach; ?>

                            <tr style="background: yellow">
                                <td>TOTAL</td>
                                <td><?php echo round($total, 3); ?></td>
                            </tr>
                        </tbody>
                    </table>
                
                </div>

                <br><br>


                <iframe src="<?php echo $src ?>" width="1160" height="500" scrolling="yes"></iframe>
                <?php else: ?>
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                <?php endif; ?>

            </div><!--.middle-->

            <footer class = "footer">
                <?php include 'template/footer.php';
                ?>
            </footer><!-- .footer -->

        </div><!-- .wrapper -->

    </body>
</html>
