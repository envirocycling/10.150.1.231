<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
$action = $_GET['action'];
$cid = $_GET['cid'];
?>
<link rel="stylesheet" type="text/css" href="css/tcal.css" />
<script type="text/javascript" src="js/tcal.js"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/layout.css" media="screen" />
<script src="js/jquery-1.6.4.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.ui.core.min.js"></script>
<script src="js/setup.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setupLeftMenu();
        $('.datatable').dataTable();
        setSidebarHeight();
    });
</script>
<style>
    .input{
        text-transform: uppercase;
        width: 100%;
    }
    .submit{
        width: 100px;
        height: 40px;
    }
    table{
       border-collapse: collapse;
    }
</style>
<script>
    function f_encode(){
       var h_ctr = Number($('#h_ctr').val());
       var f_ctr = 1;
       var date = $('#myDate').val();
       var cid = "<?php echo $cid?>";
       if(date != ''){
            while(f_ctr < h_ctr){
                var price = $('#' + f_ctr).val();
                var grade = $('#' + f_ctr).attr('class');
                var dataX = 'wp_grade=' + grade + '&price=' + price + '&date=' + date + '&client_id=' + cid;
                
                    $.ajax({
                        type: 'POST',
                        url: 'exec/price_encode.php',
                        data: dataX
                    });
                
                f_ctr++;
            }
            alert('Successful');
            window.close();
        }else{
            alert("Please input date effective.");
        }
    }
</script>
<center>
    <?php
    if($action == 'price'){
    ?>
        <table border='1'>
            <tr>
                <td colspan="2"><center><h3>Client Current Prices</h3></center></td>
            </tr>
            <?php
            $sql_grade = mysql_query("SELECT * from material WHERE status='' ORDER BY code Asc") or die(mysql_error());
            $ctr = 1;
                while($row_grade = mysql_fetch_array($sql_grade)){
                    $sql_clientPrice = mysql_query("SELECT * from client_price WHERE client_id='$cid' and material_id='".$row_grade['material_id']."' and status='1' ORDER BY date_effective Desc LIMIT 1") or die(mysql_error());
                    $row_clientPrice = mysql_fetch_array($sql_clientPrice);
                    echo '<tr>
                        <td style="height:10px;">'.$row_grade['code'].'</td>
                        <td><input type="number" class="'.$row_grade['material_id'].'" id="'.$ctr.'" value="'.$row_clientPrice['price'].'"></td>
                    </tr>';
                    $ctr++;
                }
               echo '<input type="hidden" value="'.$ctr.'" id="h_ctr">';
            ?>
            <tr>
                <td>Date Effective:</td>
                <td><input class="tcal" type="text" name="date" value="<?php echo $myDate;?>" id="myDate" readonly></td>
            </tr>
        </table>
        <br>
</center>
<button class="submit" onclick="f_encode();">Update</button>
<?php }else{?>
        <table border='1' align="left">
            <tr>
                <td colspan="2"><center><h3>Client Current Prices</h3></center></td>
            </tr>
            <?php
            $sql_grade = mysql_query("SELECT * from material WHERE status='' ORDER BY code Asc") or die(mysql_error());
            $ctr = 1;
                while($row_grade = mysql_fetch_array($sql_grade)){
                    $sql_clientPrice = mysql_query("SELECT * from client_price WHERE client_id='$cid' and material_id='".$row_grade['material_id']."' and status='1' ORDER BY date_effective Desc LIMIT 1") or die(mysql_error());
                    $row_clientPrice = mysql_fetch_array($sql_clientPrice);
                    echo '<tr>
                        <td style="height:10px;">'.$row_grade['code'].'</td>
                        <td><input type="number" class="'.$row_grade['material_id'].'" id="'.$ctr.'" value="'.$row_clientPrice['price'].'" readonly></td>
                    </tr>';
                    $ctr++;
                }
            ?>
        </table>    
<?php
$sql_clientPrice = mysql_query("SELECT * from client_price WHERE client_id='$cid' ORDER BY date_effective") or die(mysql_error());

?>
<div style="width:500px; position: absolute; top: 0;right: 0;">
    <table class="data display datatable" id="example">
        <thead>
            <tr>
            <th class="data">Date Effective</th>
            <th class="data">Grade</th>
            <th class="data">Price</th>
            <th class="data">Status</th>
            </tr>
        </thead>
        <?php
        while($row_clientPrice = mysql_fetch_array($sql_clientPrice)){
            $sql_grade = mysql_query("SELECT * from material WHERE material_id='".$row_clientPrice['material_id']."'") or die (mysql_error());
            $row_grade = mysql_fetch_array($sql_grade);
            $status = 'Approved';
            if($row_clientPrice['status'] == 0){
                $status = '<font color="red"><i>Pending to GM</i></font>';
            }
            echo '<tr class="data">
                    <td>'.$row_clientPrice['date_effective'].'</td>
                    <td>'.$row_grade['code'].'</td>
                    <td>'.$row_clientPrice['price'].'</td>
                    <td>'.$status.'</td>
            </tr>';
        }
        ?>
    </table>
</div>
<?php
}
?>