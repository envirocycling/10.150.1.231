<?php require_once '/var/www/html/paymentsystem/config/query_builder.php';

//dd($_GET);

$type = $_GET['type'];
$from = $_GET['from'];
$to = $_GET['to'];

if($type == 'all') {
    $where = "AND sr.status != 'void';";
} else if($type == 'paid') {
    $where = "AND sr.status = 'paid';";
} else if($type == 'unpaid') {
    $where = "AND sr.status = 'generated';";
}

$queryStr = "SELECT 
	sr.date AS `date_delivered`,
	sr.date_paid,
	sr.str_no,
	sr.plate_number,
	sr.voucher_no,
	sr.cheque_no,
    sr.status,
	CONCAT(s.supplier_id, '_', s.supplier_name) as supplier,
	m.code AS `grade`,
	srd.corrected_weight,
	srd.price,
	srd.amount 
FROM scale_receiving sr 
INNER JOIN supplier s ON s.id = sr.supplier_id 
INNER JOIN scale_receiving_details srd ON srd.trans_id = sr.trans_id 
INNER JOIN material m ON m.material_id = srd.material_id 
WHERE (sr.date >= '{$from}' AND sr.date <= '{$to}') {$where}";


$paper_buying = fetch($queryStr, null);


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
    });</script>
                    <script type="text/javascript">             var tableToExcel = (function () {
            var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                                        , base64 = function (s) {
            return window.btoa(unescape(encodeURIComponent(s)))
                }
        , format = function (s, c) {
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


<table class="data display datatable" id="example">
    <thead>
        <tr class="data">
            <th class="data">Date Delivered</th>
            <th class="data">Date Paid</th>
            <th class="data">STR #</th>
            <th class="data">Cheque #</th>
            <th class="data">Supplier Name</th>
            <th class="data">Plate #</th>
            <th class="data">WP Grade</th>
            <th class="data">Status</th>
            <th class="data">Net Weight</th>
            <th class="data">Unit Cost</th>
            <th class="data">Paper Buying</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($paper_buying as $pb): ?>
        <tr>
            <td class="data"><?php echo $pb->date_delivered; ?></td>
            <td class="data"><?php echo $pb->date_paid; ?></td>
            <td class="data"><?php echo $pb->str_no; ?></td>
            <td class="data"><?php echo $pb->cheque_no; ?></td>
            <td class="data"><?php echo $pb->supplier; ?></td>
            <td class="data"><?php echo $pb->plate_number; ?></td>
            <td class="data"><?php echo $pb->grade ?></td>
            <td class="data"><?php echo ($pb->status == 'paid') ? 'Paid': 'Unpaid'; ?></td>
            <td class="data"><?php echo $pb->corrected_weight ?></td>
            <td class="data"><?php echo $pb->price ?></td>
            <td class="data"><?php echo $pb->amount ?></td>
        </tr>  
    <?php endforeach ?>

        <tr class='total'>
            <td>!TOTAL!</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>0</td>
            <td></td>
            <td>0</td>
        </tr>
    </tbody>

</table>

<input type="button" onclick="tableToExcel('example', 'W3C Example Table')" value="Export XLS">
