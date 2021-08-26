<?php
date_default_timezone_set("Asia/Singapore");
@session_start();
include 'config.php';
    if(isset($_POST['submit'])){
        $client_name = $_POST['client_name'];
        $contact = $_POST['contact'];
        $description = $_POST['description'];
        
        $sql_chk = mysql_query("SELECT * from client WHERE client_name = '$client_name'") or die(mysql_error());
        if(mysql_num_rows($sql_chk) == 0){
            if(mysql_query("INSERT INTO client (client_name, contact, description) VALUES ('$client_name', '$contact', '$description')") or die(mysql_error())){
                echo '<script>
                        window.close();
                        alert("Successful! Please refresh");
                    </script>';
            }
        }
    }
?>
<link rel="stylesheet" type="text/css" href="css/frm_fundtransfer2.css" />
<style>
    .input{
        text-transform: uppercase;
        height: 30px;
        width: 100%;
    }
    .submit{
        width: 100px;
        height: 40px;
    }
</style>
<center>
    <br>
    <form method="post" >
        <table class="frm_fundtransfer">
            <tr>
                <td colspan="2"><center><h2>Add Client</h2></center></td>
            </tr>
            <tr>
                <td>Client Name</td>
                <td><input type="text" class="input" name="client_name" required></td>
            </tr>
            <tr hidden>
                <td>Contact</td>
                <td><input type="text" class="input" name="contact"></td> 
            </tr>
            <tr>
                <td>Description</td>
                <td><input type="text" class="input" name="description" required></td>
            </tr>
        </table>
    <br>
    <input type="submit" class="submit" name="submit"> 
    </form>
</center>