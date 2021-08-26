
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

<script type="text/javascript">
    var tableToExcel = (function () {

        var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>', 
            base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            }, 
            format = function (s, c) {
            return s.replace(/{(\w+)}/g, function (m, p) {
                return c[p];
            })
        }

        return function (table, name) {
            if (!table.nodeType)
                table = document.getElementById(table)
            var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
            window.location.href = uri + base64(format(template, ctx))
        }

    })()
</script>

<style>
    #example{
        border-width:50%;
        font-size: 13px;
    }
    .total{
        background-color: yellow;
        font-weight: bold;
    }
</style>

<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">

<?php

require_once '/var/www/html/paymentsystem/config/query_builder.php'; // change this

$delivered_to = $_GET['delivered_to'];
$from = $_GET['from'];
$to = $_GET['to'];
$branch = $_GET['branch'];


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
AND so.dt_id LIKE '%{$delivered_to}%' 
AND so.branch_id LIKE '%{$branch}%';";

$records = fetch($sql, null);

?>


<table class="data display datatable" id="example" style="text-align: center">
    <thead>
        <tr class="data">
            <th class="data">Date</th>
            <th class="data" style="width: 280px">Supplier Name</th>
            <th class="data">Branch</th>
            <th class="data">Plate No</th>
            <th class="data" style="width: 150px">Str No</th>
            <th class="data" style="width: 130px">TR No</th>
            <th class="data" style="width: 150px">Delivered To</th>
            <th class="data" style="width: 150px">Series No</th>
            <th class="data">Grade</th>
            <th class="data">Net Weight</th>
            <th class="data">Moisture</th>
            <th class="data">Dirt</th>
            <th class="data">Corrected Weight</th>
            <th class="data" style="width: 180px">Remarks</th>
        </tr>
    </thead>

    <tbody>

    <?php if(count($records) > 0): ?>
        <?php foreach($records as $record): ?>
        <?php 

        $supplier = getFirst("SELECT * FROM `supplier` WHERE id = '{$record->supplier_id}';", null);
            
        $series_no = $record->series_no ? $record->series_no : $record->str_no;
        $supplier_name = $supplier ? $supplier->supplier_name : '';
        
        ?>
        <tr>
            <td><?php echo $record->date ?></td>
            <td><?php echo $supplier_name ?></td>
            <td><?php echo $record->branch_name ?></td>
            <td><?php echo $record->plate_number ?></td>
            <td><?php echo $record->str_no ?></td>
            <td><?php echo $record->tr_no ?></td>
            <td><?php echo $record->client ?></td>
            <td><?php echo $series_no ?></td>
            <td><?php echo $record->grade ?></td>
            <td><?php echo $record->net_weight ?></td>
            <td><?php echo $record->moisture ?></td>
            <td><?php echo $record->dirt ?></td>
            <td><?php echo $record->weight ?></td>
            <td><?php echo $record->remarks ?></td>
        </tr>
        <?php endforeach;?>
    <?php endif; ?>
    </tbody>

</table>

<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">

