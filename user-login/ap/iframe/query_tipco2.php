<?php require './../../../config/query_builder_tipco.php';

$from = $_GET['from'];
$to = $_GET['to'];

$results = fetch("SELECT scale.scale_id, scale.date, scale.ws_no, scale.str_no, supplier.name AS supplier_name, scale.plate_no, company.name as delivered_to, scale.status
FROM scale
INNER JOIN supplier ON scale.supplier_id = supplier.supplier_id 
INNER JOIN company ON scale.company_id = company.company_id 
WHERE (scale.company_id='1' or scale.company_id='2' or scale.company_id='5') AND 
scale.status!='DELETED' AND 
(scale.date >= '{$from}' and scale.date <= '{$to}') AND 
supplier.owner='EFI';", null);


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

<script>
    function openWindow(str) {
        window.open("../view_tipco2.php?scale_id=" + str, 'mywindow', 'width=1020,height=600,left=150,top=20');

    }
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

<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>

<table class="data display datatable" id="example">
    <thead>
        <tr class="data">
            <th class="data" width="40">Date</th>
            <th class="data" width="80">WS #</th>
            <th class="data" width="80">STR #</th>
            <th class="data" width="80">Supplier Name</th>
            <th class="data">Plate #</th>            
            <th class="data">Delivered To</th>
            <th class="data">Status</th>
            <th class="data">Action</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($results as $data): ?>

        <?php

        $ws_no = $data->ws_no == 0 ? '' : 'WS'.sprintf("%06s", $data->ws_no);
        $status = $data->status == '' ? 'PENDING' : $data->status;

        ?>

        <tr class='data'>
            <td class='data'><?php echo $data->date; ?></td>
            <td class='data'><?php echo $ws_no; ?></td>
            <td class='data'><?php echo $data->str_no; ?></td>
            <td class='data'><?php echo $data->supplier_name; ?></td>
            <td class='data'><?php echo $data->plate_no; ?></td>
            <td class='data'><?php echo $data->delivered_to; ?></td>
            <td class='data'><?php echo $status; ?></td>
            <td class='data'>
                <button id='<?php echo $data->scale_id ?>' onclick='openWindow(this.id);' class='button'>View</button>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

