<style>
    table{
        border-collapse: collapse; 
        font-size: 20px;
    }
    td, input{
        height: 30px; 
        font-size: 20px;
    }
</style>  
<center>
    <h2>SBC Budget</h2>
    <?php
    include('config.php');
    date_default_timezone_set("Asia/Singapore");
    $date = date('Y/m/d');
    $ctr = 1;

    if (isset($_POST['submit'])) {
        $ctr2 = 1;
        $nCtr = $_POST['ctr'];
        while ($ctr2 <= $nCtr) {
            $branch_id = $_POST['b_'.$ctr2];
            $bud = $_POST['budget_'.$ctr2];
            mysql_query("UPDATE branches SET sbc_budget = '$bud' WHERE branch_id='".$branch_id."'");
//            echo "UPDATE branches sbc_budget = '$bud' WHERE branch_id='".$branch_id."'<br>";
            $ctr2++;
        }
        
    }

    echo '<form method="post">';
    echo '<table border="1">';
    $sql_branch = mysql_query("SELECT * from branches WHERE status!='n/a'") or die(mysql_error());
    while ($row_branch = mysql_fetch_array($sql_branch)) {
//        $sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='" . $row_branch['branch_id'] . "' and `from`='$from' and `to`='$to'") or die(mysql_error());
//        $row_chk = mysql_fetch_array($sql_chk);
//        if(){
//            
//        }
        echo '<tr>';
        echo '<td>' . $row_branch['branch_name'] . '</td>';
        echo '<td><input type="number" name="budget_' . $ctr . '" value="' . $row_branch['sbc_budget'] . '"></td>';
        echo '<input type="hidden" value="' . $row_branch['branch_id'] . '" name="b_' . $ctr . '">';
        echo '</tr>';
        $ctr++;
    }
    echo '</table><br>';
    echo '<input type="hidden" name="ctr" value="' . $ctr . '">';
    echo '<input type="submit" name="submit" value="Encode">';
    ?>
</form>
</center>