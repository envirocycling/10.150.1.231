<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
$action = $_GET['action'];
$cid = $_GET['cid'];
$date_effective = $_GET['date_effective'];

$sql_client = mysql_query("SELECT * from client WHERE cid='$cid'") or die(mysql_error());
$row_client = mysql_fetch_array($sql_client);
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
    .red{
        color: red;
    }
</style>
<script>
    function f_encode(action){
        var mes =  confirm("Do you want to proceed?");
       
            if(mes == true){
                var date_effective = "<?php echo $_GET['date_effective']?>";
                var cid = "<?php echo $_GET['cid']?>";
                var dataX = 'date_effective=' + date_effective + '&client_id=' + cid + '&action=' + action;
      
                    $.ajax({
                        type: 'POST',
                        url: 'exec/price_encode.php',
                        data: dataX
                    }).done(function(){
                        alert("Successful");
                        close();
                    });
            }else{
                return false;
            }
                
    }
</script>
<center>
        <table border='1'>
            <tr>
                <td colspan="2"><center><h3><?php echo strtoupper($row_client['client_name']);?> Prices</h3></center></td>
            </tr>
            <?php
            $sql_pending = mysql_query("SELECT * from client_price WHERE status='0' and date_effective='$date_effective' and client_id='$cid' ORDER BY material_id Asc") or die(mysql_error());
                while($row_pending= mysql_fetch_array($sql_pending)){
                    $sql_grade = mysql_query("SELECT * from material WHERE material_id='".$row_pending['material_id']."' ORDER BY code Asc") or die(mysql_error());
                    $row_grade = mysql_fetch_array($sql_grade);
                    echo '<tr>
                        <td style="height:10px;">'.$row_grade['code'].'</td>
                        <td><input type="number" class="'.$row_grade['material_id'].'" id="'.$ctr.'" value="'.$row_pending['price'].'" readonly></td>
                    </tr>';
                    $ctr++;
                }
            ?>
            <tr>
                <td class="red">Date Effective:</td>
                <td><input type="text" class="red" name="date" value="<?php echo date('Y/m/d', strtotime($date_effective));?>" id="myDate" readonly></td>
            </tr>
        </table>
        <br><br>
        <button class="submit" onclick="f_encode(this.id);" id="approved">Approve</button>&nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp;<button class="submit" onclick="f_encode(this.id);" id="disapproved">Disapprove</button>
</center>

</div>
