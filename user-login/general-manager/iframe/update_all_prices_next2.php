<link rel="stylesheet" type="text/css" href="../css/layout.css" media="screen" />
<script src="../js/jquery.min.js" type="text/javascript"></script>

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

    $(document).ready(function () {
        $('#selectall').click(function () {  //on click
            if (this.checked) { // check select status
                $('.checkbox').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"

                });
                var dataString = 'branch=<?php echo $_POST['branch']; ?>&type=checked_all';
                $.ajax({
                    type: "POST",
                    url: "update_temp_sup_id.php",
                    data: dataString,
                    cache: false
                });
            } else {
                $('.checkbox').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"
                });
                var dataString = 'type=unchecked_all';
                $.ajax({
                    type: "POST",
                    url: "update_temp_sup_id.php",
                    data: dataString,
                    cache: false
                });
            }
        });
    });

    function check(str) {
        var x = document.getElementById(str).checked;
        var data = str.split("_");
        var supplier_id = $("#val_" + data[1]).val();
        if (x == true) {
            var dataString = 'supplier_id=' + supplier_id + '&type=checked';
            $.ajax({
                type: "POST",
                url: "update_temp_sup_id.php",
                data: dataString,
                cache: false
            });
        } else {
            var dataString = 'supplier_id=' + supplier_id + '&type=unchecked';
            $.ajax({
                type: "POST",
                url: "update_temp_sup_id.php",
                data: dataString,
                cache: false
            });
        }
    }


</script>
<?php
echo "<form action='update_all_prices_next2_exec.php' method='POST'>";
$ctr = $_POST['ctr'];
$c = 0;
$c2 = 0;

while ($c < $ctr) {
    if (!empty($_POST['type' . $c]) && !empty($_POST['price' . $c])) {
        echo "<input type='hidden' name='mat_id$c2' value='" . $_POST['mat_id' . $c] . "'>";
        echo "<input type='hidden' name='type$c2' value='" . $_POST['type' . $c] . "'>";
        echo "<input type='hidden' name='price$c2' value='" . $_POST['price' . $c] . "'>";
        $c2++;
    }
    $c++;
}

echo "<input type='hidden' name='ctr' value='$c2'>";
echo "<input type='hidden' name='branch' value='" . $_POST['branch'] . "'>";
echo "<input type='hidden' name='dt_id' value='" . $_POST['dt_id'] . "'>";
echo "<input type='hidden' name='date' value='" . $_POST['date'] . "'>";


echo "<br>";
include 'config.php';

echo '<table border="1" class="data display datatable" id="example">
<thead>
<tr class="data">
            <th class="data" width="20"></th>
            <th class="data" width="20">ID</th>
            <th class="data">Supplier Name</th>
            <th class="data">Owner Name</th>
            <th class="data">Branch</th>';
echo '</tr>
        </thead>';
$ctr = 0;
$sql_sup = mysql_query("SELECT * FROM supplier WHERE branch like '%" . $_POST['branch'] . "%'");
while ($rs_sup = mysql_fetch_array($sql_sup)) {
    echo "<tr class='data'>";
    echo "<td><input type='hidden' id='val_$ctr' value='" . $rs_sup['id'] . "'><input id='supid_" . $ctr . "' class='checkbox' type='checkbox' name='supid_" . $ctr . "' value='" . $rs_sup['id'] . "' onclick='check(this.id);'></td>";
    echo "<td class='data'>" . $rs_sup['supplier_id'] . "</td>";
    echo "<td class='data'>" . $rs_sup['supplier_name'] . "</td>";
    echo "<td class='data'>" . $rs_sup['owner_name'] . "</td>";
    echo "<td class='data'>" . $rs_sup['branch'] . "</td>";
    echo "</tr>";
    $ctr++;
}



echo "</table>";
?>
<!--<font size='2' color='red'>Please clear the searchbox before submit this form.</font><br>-->
<!--<input type='hidden' name='ctrrrr' value='<?php // echo $ctr;      ?>'>-->
Select All: <input type="checkbox" id="selectall">
<input type='submit' name='submit' value='Submit'>
<?php
echo "</form>";
?>
