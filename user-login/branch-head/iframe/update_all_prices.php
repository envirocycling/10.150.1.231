<script type="text/javascript" src="jsDatePick.min.1.3.js"></script>
<script>
    function OnSubmitForm() {
        var type = document.forms["myForm"]["form_type"].value;
        if (type === '1') {
            document.getElementById("myForm").action = "update_all_prices_next.php";
        }
        else if (type === '2') {
            document.getElementById("myForm").action = "update_all_prices_next2.php";
        }
        return true;
//        return false;
    }
</script>

<style>
    body {
        margin-top: 10px;
        font: 12px/18px Arial, sans-serif;
    }
    .date{
        width: 120px;
    }
</style>
<form  id="myForm" name='myForm' onsubmit='return OnSubmitForm();' method="POST">
    <table border='1'>
        <?php
        include 'config.php';
        $ctr = 0;
        $sql_mat = mysql_query("SELECT * FROM `material` WHERE status=''");
        while ($rs_mat = mysql_fetch_array($sql_mat)) {
            echo "<tr>";
            echo "<td><input type='hidden' name='mat_id$ctr' value='" . $rs_mat['material_id'] . "'>" . $rs_mat['code'] . "</td>";
            echo "<td>
                                    <select name='type$ctr'>
                                    <option value=''></option>                                    
                                    <option value='LESS'>LESS</option>          
                                    <option value='ADD'>ADD</option>
                                    <option value='SET'>SET</option>
                                    </select>
                                    </td>";
            echo "<td><input type='text' name='price$ctr' value=''></td>";
            echo "</tr>";
            $ctr++;
        }
        echo "<input type='hidden' name='ctr' value='$ctr'>";
        ?>
    </table>
    <br>
    Type: <select name="form_type" required>
        <option value=""></option>
        <option value="1">Update All</option>
        <option value="2">Select Suppliers</option>
    </select>
    <br>
    <br>
    Branch: 
    <?php
    echo "<select name='branch'>";
    echo "<option value=''>All Branches</option>";
    $sql_branch = mysql_query("SELECT * FROM supplier WHERE branch!='' GROUP BY branch");
    while ($rs_branch = mysql_fetch_array($sql_branch)) {
        echo "<option value='" . $rs_branch['branch'] . "'>" . $rs_branch['branch'] . "</option>";
    }
    echo "</select> ";
    ?>
    <br>
    <br>
    Delivered To: 
    <?php
    echo "<select name='dt_id'>";
    echo "<option value='1'>TIPCO/MULTIPLY</option>";
    echo "<option value='3'>FSI</option>";
    echo "</select> ";
    ?>
    <br>
    <br>
    Date: <input class="date" type="text" name="date" value="<?php echo date("Y/m/d"); ?>" required><br>
    <font size='2' color='red'>Format (YYYY/MM/DD)</font><br>
    <input type='submit' name='submit' value='Submit' onclick="return confirm('When you click [Ok] you cant undo this action.')">

</form>