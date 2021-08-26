
<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../js/jquery.ui.core.min.js"></script>
<script src="../js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
</style>
<link href="../src/facebox_2.css" media="screen" rel="stylesheet" type="text/css" />
<script src="../src/facebox.js" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('a[rel*=facebox]').facebox({
            loadingImage: '../src/loading.gif',
            closeImage: '../src/closelabel.png'
        })
    })
</script>
<base target="_parent" />
<?php
include '../config.php';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
 <th class="data">Bank</th>
            <th class="data">From</th>
            <th class="data">To</th>
            <th class="data">Status</th>
             <th class="data">Date</th>
             <th class="data">Action</th>
        </tr>
        </thead>';
$sql_cheque = mysql_query("SELECT * FROM cheque_range");
while ($rs_cheque = mysql_fetch_array($sql_cheque)) {
    echo "<tr>";
    echo "<td>".$rs_cheque['bank_code']."</td>";
    echo "<td>".$rs_cheque['from']."</td>";
    echo "<td>".$rs_cheque['to']."</td>";
    echo "<td>".$rs_cheque['status']."</td>";
    echo "<td>".$rs_cheque['date']."</td>";
    echo "<td><a rel='facebox' href='../edit_cheque_range.php?id=".$rs_cheque['id']."'><button>Edit</button></a></td>";
    echo "</tr>";
}
echo "</table>";
?>