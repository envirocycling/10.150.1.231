<style>
    .data{
        width: 100px;
    }
    .header{
        font-weight: bold;
    }
    .Remarks{
        width: 100px;
    }
    .details td{
        padding-left: 3px;
        padding-right: 3px;
    }
</style>

<?php require_once '/var/www/html/paymentsystem/config/query_builder.php';

$id = $_GET['trans_id'];

$sqlOut = "SELECT 
so.date, 
so.plate_number, 
so.str_no, 
CONCAT(s.supplier_id,'_',s.supplier_name) as `supplier`, 
dt.name as `client` 
FROM scale_outgoing as so 
INNER JOIN supplier as s on s.id = so.supplier_id 
INNER JOIN delivered_to as dt on dt.dt_id = so.dt_id 
WHERE so.trans_id = {$id};";

$outgoing = getFirst($sqlOut, null);


if($outgoing) {

    $sqlDetails = "SELECT details.*, material.code as `grade` FROM scale_outgoing_details as details 
    INNER JOIN material on material.material_id = details.material_id 
    WHERE details.trans_id = {$id}";

    $details = fetch($sqlDetails, null);


}

?>



<center>

    <?php if($outgoing): ?>
    <h2>Delivery of <?php echo $outgoing->supplier ?></h2>

    <table class='rec'>
        <tr>
            <td>Date delivered: </td>
            <td><?php echo $outgoing->date ?></td>
        </tr>

        <tr>
            <td>Plate Number: </td>
            <td><?php echo $outgoing->plate_number ?></td>
        </tr>

        <tr>
            <td>STR Number: </td>
            <td><?php echo $outgoing->str_no ?></td>
        </tr>

        <tr>
            <td>Delivered To: </td>
            <td><?php echo $outgoing->client ?></td>
        </tr>
    </table>
    <?php endif; ?>

    <br>
    <?php if(count($details) > 0): ?>
    <table class='details' border='1'>
        <thead>
            <tr class='header'>
                <th>WP_Grade</th>
                <th>Gross</th>
                <th>Tare</th>
                <th>Weight</th>
                <th>MC</th>
                <th>Dirt</th>
                <th>Net Weight</th>
                <th>Remarks</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($details as $detail): ?>
            <tr>
                <td><?php echo $detail->grade ?></td>
                <td><?php echo $detail->gross ?></td>
                <td><?php echo $detail->tare ?></td>
                <td><?php echo $detail->net_weight ?></td>
                <td><?php echo $detail->mc ?></td>
                <td><?php echo $detail->dirt ?></td>
                <td><?php echo $detail->corrected_weight ?></td>
                <td><?php echo $detail->remarks ?></td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <?php endif;?>
</center>    