<?php 

require_once './../../../config/query_builder.php';
require_once './../../../config/curl_api.php';

$branch_id = $_GET['branch_id'];

$branch = getFirst("SELECT * FROM `branches` WHERE branch_id={$branch_id}", null);
$ip = $branch->ip_address;

$params = array(
    'start' => $_GET['from'],
    'end' => $_GET['to']
);

//dd("http://{$ip}/ts_api/api/scalereceiving/receipts");


try {

    $results = callApi("GET", "http://{$ip}/ts_api/api/scalereceiving/receipts", $params);
    
    $receipts = json_decode($results['data']);
    
} catch (Exception $e) {

    echo "<script>alert('Internal Server Error. Please check the {$branch->branch_name} server')</script>";
    
}

 ?>


<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });
</script>

<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .submitq {
        height: 20px;
        width: 60px;
    }
    .total {
        background-color: yellow;
        font-weight: bold;
    }
</style>

<header style="padding: 10px; text-align: center;">
    <h1><?php echo $branch->branch_name ?> Receiving</h1>
</header>

<table class="data display datatable" id="example" style="text-align: center">
    <thead>
        <tr class="data">
            <th class="data">Branch</th>
            <th class="data">Date</th>
            <th class="data">Ref #</th>
            <th class="data">Priority #</th>
            <th class="data">Supplier Name</th>
            <th class="data">Plate #</th>
            <th class="data">Grade</th>
            <th class="data">Weight</th>
            <th class="data">Unit Cost</th>
            <th class="data">Less Weight</th>
            <th class="data">Corrected Weight</th>
            <th class="data">Remarks</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($receipts->data as $receipt): ?>
        <tr class="data">
            <td class="data"><?php echo $branch->branch_name ?></td>
            <td class="data"><?php echo $receipt->date ?></td>
            <td class="data"><?php echo $receipt->ref_no ?></td>
            <td class="data"><?php echo $receipt->priority_no ?></td>
            <td class="data"><?php echo $receipt->supplier_name ?></td>
            <td class="data"><?php echo $receipt->plate_number ?></td>
            <td class="data"><?php echo $receipt->code ?></td>
            <td class="data"><?php echo $receipt->net_weight ?></td>
            <td class="data"><?php echo $receipt->price ?></td>
            <td class="data"><?php echo $receipt->less_weight ?></td>
            <td class="data"><?php echo $receipt->corrected_weight ?></td>
            <td class="data"><?php echo $receipt->remarks ?></td>
        </tr>    
    <?php endforeach ?>

    <tr style="background: yellow;">
        <td>~Total</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><?php echo $receipts->total_weight ?></td>
        <td></td>
        <td><?php echo $receipts->total_less_weight ?></td>
        <td><?php echo $receipts->total_corrected_weight ?></td>
        <td></td>
    </tr>

    </tbody>
</table>
