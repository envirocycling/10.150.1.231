
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
<table class="data display datatable" id="example">
    <thead>
        <tr class="data">
            <th class="data" width="40">Id</th>
            <th class="data" width="80">Code</th>
            <th class="data">Details</th>
            <th class="data">Action</th>
        </tr>
    </thead>
    <?php
    include 'config.php';

    if (isset($_GET['mat_id'])) {
        mysql_query("UPDATE material SET status='deleted' WHERE material_id='".$_GET['mat_id']."'");
    }
    $sql_material = mysql_query("SELECT * FROM material WHERE status!='deleted'");
    while ($rs_material = mysql_fetch_array($sql_material)) {
        echo "<tr>";
        echo "<td>".$rs_material['material_id']."</td>";
        echo "<td>".$rs_material['code']."</td>";
        echo "<td>".$rs_material['details']."</td>";
        echo "<td>";
        echo "<a rel='facebox' href='../material_edit.php?mat_id=".$rs_material['material_id']."'><button>Edit</button></a>";
        ?>
    <a href="material.php?mat_id=<?php echo $rs_material['material_id']; ?>" onclick="return confirm('Are you sure you want to delete?')"><button>Delete</button></a>
        <?php
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>