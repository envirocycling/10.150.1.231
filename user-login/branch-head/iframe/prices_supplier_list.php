
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
    .button {
        width: 90px;
        height: 20px;
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
<script type="text/javascript">
    function Check(chk)
    {
        if(document.myform.Check_All.value=="Check All"){
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true ;
            document.myform.Check_All.value="UnCheck All";
        }else{

            for (i = 0; i < chk.length; i++)
                chk[i].checked = false ;
            document.myform.Check_All.value="Check All";
        }
    }
    function check_indi(str){
        if (document.getElementById(str).checked) {
            alert(str);
            var id = $(str).val();
            var dataString = 'id='+id;
            $.ajax({
                type: "POST",
                url: "prices_session.php",
                data: dataString,
                cache: false
            });
        } else {
            var id = $(str).val();
            var dataString = 'id='+id+'&uncheck=1';
            $.ajax({
                type: "POST",
                url: "prices_session.php",
                data: dataString,
                cache: false
            });
        }

    }
</script>
<?php
include 'config.php';
echo '<form name="myform" action="checkboxes.asp" method="post">';
echo '<input type="button" name="Check_All" value="Check All"
onClick="Check(document.myform.check_list)">';
echo '<table class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="20"></th>
            <th class="data">ID</th>
            <th class="data">Supplier Name</th>
            <th class="data">Owner</th>
            <th class="data">Action</th>';
echo '</tr>
</thead>';
$sql_sup = mysql_query("SELECT * FROM supplier");
while ($rs_sup = mysql_fetch_array($sql_sup)) {
    echo "<tr class='data'>";
    echo "<td class='data'><input id='".$rs_sup['id']."' type='checkbox' name='check_list' value='".$rs_sup['id']."' onclick='check_indi(this.id);'></td>";
    echo "<td class='data'>".$rs_sup['supplier_id']."</td>";
    echo "<td class='data'>".$rs_sup['supplier_name']."</td>";
    echo "<td class='data'>".$rs_sup['owner_name']."</td>";
    echo "<td class='data'><a rel='facebox' href='../breaktime_edit.php'><button class='button'>View Price</button></a></td>";
    echo "</tr>";
}

echo "</table>";
echo "</form>";
?>