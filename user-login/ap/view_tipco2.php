<?php require_once './../../config/query_builder_tipco.php';

$id = $_GET['scale_id'];

$result = getFirst("SELECT scale.scale_id, scale.date, scale.ws_no, scale.str_no, supplier.name AS supplier_name, scale.plate_no, company.name as delivered_to, scale.status
FROM scale
INNER JOIN supplier ON scale.supplier_id = supplier.supplier_id 
INNER JOIN company ON scale.company_id = company.company_id 
WHERE scale.scale_id = {$id};", null);

$details = fetch("SELECT c.name as grade, sd.gross, sd.tare, sd.net_weight, sd.com_remarks FROM scale_details as sd INNER JOIN commodity as c on sd.com_id = c.com_id WHERE sd.scale_id = {$id};", null);

 ?>


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

<center>
<h2>Delivery of <?php echo $result->supplier_name ?></h2>
<table class='rec'>
<tr>
<td>Date delivered: </td>
<td><?php echo $result->date ?></td>
</tr>
<tr>
<td>Plate Number: </td>
<td><?php echo $result->plate_no ?></td>
</tr>
<tr>
<td>STR Number: </td>
<td><?php echo $result->str_no ?></td>
</tr>
<tr>
<td>Delivered To: </td>
<td><?php echo $result->delivered_to ?></td>
</tr>
</table>
<br>

<table class='details' border='1'>
    <thead>
        <tr class='header'>
            <th>WP_Grade</th>
            <th>Gross</th>
            <th>Tare</th>
            <th>Weight</th>
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
            <td><?php echo $detail->com_remarks ?></td>
        </tr>   
        <?php endforeach ?>
    </tbody>
</table>

<br>
<br>


<?php if ($result->linked == 1 && $result->company_id == 1): ?>
    <span style="font-style: italic">
    This transaction already final by RMD.
    </span>
<?php elseif($result->linked == 0 && $result->company_id == 1): ?>
    <span style="font-style: italic">
    This transaction is still pending to RMD.
    </span>
<?php else: ?>
    <span style="font-style: italic">
    This transaction is for FSI..
    </span>
<?php endif; ?>

<br>

<?php if ($result->status == 'COMPLETED'): ?>  
<a href='save_tipco_data.php?scale_id=<?php echo $id; ?>'><button title='Click here to save the data to EFI Receiving.'>Save</button></a>
<?php endif ?>
