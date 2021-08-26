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
    <h2>Weekly Budget</h2>
<?php
include('config.php');
$from = $_GET['from'];
$to = $_GET['to'];
$ctr = 1;
?>
    
    
    <script src="js/jquery.min.js" type="text/javascript"></script>
    <script>
    function f_submit(){
        var ctr = Number(document.getElementById('ctr').value);
        var f_ctr = 1;
        var from = "<?php echo $from?>";
        var to = "<?php echo $to?>";
        while(f_ctr < ctr){
            var budget = document.getElementById('budget_' + f_ctr).value;
            var branch_id = document.getElementById(f_ctr).value;
            var dataX = 'branch_id=' + branch_id + '&budget=' + budget + '&from=' + from  + '&to=' + to;
                $.ajax({
                    type: 'POST',
                    url: 'exec/fund_weekly_budget.php',
                    data: dataX
                });
            f_ctr++;
        }
        alert('Successful. Please refresh');
        window.close();
    }
</script>  

<?php
echo '<table border="1">';
    $sql_branch = mysql_query("SELECT * from branches WHERE branch_id !='7'") or die(mysql_error());
    while($row_branch = mysql_fetch_array($sql_branch)){
        $sql_chk = mysql_query("SELECT * from fund_weeklybudget WHERE `branch_id`='".$row_branch['branch_id']."' and `from`='$from' and `to`='$to'") or die(mysql_error());
        $row_chk = mysql_fetch_array($sql_chk);
        echo '<tr>';
            echo '<td>'.$row_branch['branch_name'].'</td>';
            echo '<td><input type="number" id="budget_'.$ctr.'" value="'.$row_chk['budget'].'"></td>';
            echo '<input type="hidden" value="'.$row_branch['branch_id'].'" id="'.$ctr.'">';
        echo '</tr>';
        $ctr++;
    }
echo '</table><br>';
echo '<input type="hidden" id="ctr" value="'.$ctr.'">';
echo '<input type="button" id="submit" onclick="f_submit();" value="Encode">';
?>
</center>