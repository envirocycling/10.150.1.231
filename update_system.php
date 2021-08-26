
<style>
    body{
        background-color: #2e5e79;
    }
    div {
        background-color: white;
        font-family: arial;
        font-size: 20px;
        /*height: 200px;*/
        width: 450px;
        border: 2px solid;
        border-radius: 25px;
    }
    button{
        font-size: 15px;
        height: 30px;
        width: 100px;
    }
</style>


<?php
include 'config.php';
//$page = $_SERVER['PHP_SELF'];
//$sec = "300";
//header("Refresh: $sec; url=$page");
$msg = '';

if (isset($_POST['month'])) {
    if ($_POST['month'] != '') {
        $month = $_POST['month'];
        $branch = $_POST['branch'];
        $wp_grade = $_POST['wp_grade'];
        $target = $_POST['target'];

        $sql_branch = mysql_query("SELECT * FROM branches WHERE branch_name='$branch'");
        $rs_branch = mysql_fetch_array($sql_branch);

        if ($wp_grade != 'LCWL' && $wp_grade != 'CHIPBOARD') {
            $wp_grade = "LC" . $wp_grade;
        }

        $sql_mat = mysql_query("SELECT * FROM material WHERE code='$wp_grade'");
        $rs_mat = mysql_fetch_array($sql_mat);

        mysql_query("INSERT INTO monthly_target (`month`, `branch_id`, `material_id`, `target`) VALUES ('$month','" . $rs_branch['branch_id'] . "','" . $rs_mat['material_id'] . "','$target')");

        $msg = 'Updating Monthly Target.';
    }
}

?>

<center>
    <br><br><br><br><br>
    <div align='center'>

        <table border="0">
            <tr height="15px">
                <td align="center"><h1>Importing Data to EFI System.</h1><img src="images/ajax-loader.gif"></td>
            </tr>
            <tr>
                <td align="center"><?php echo $msg; ?></td>
            </tr>
            <tr>
                <td align="center"><font color='red'><h3>Please don't close this window.</h3></font>
                </td>
            </tr>
        </table>
    </div>
</center>
<?php
if (isset($_GET['go'])) {
    $sql_com = mysql_query("SELECT * FROM company WHERE id='1'");
    $rs_com = mysql_fetch_array($sql_com);

    $branch = $rs_com['branch'];

    $lcurl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    $url = 'http://ims.efi.net.ph/update_efi_pamp.php';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if (200 == $retcode) {
        ?>
        <form action="http://ims.efi.net.ph/update_efi_pamp.php" method="POST" name="myForm" id="myForm">
            <input type="hidden" name="branch" value="<?php echo $branch; ?>">
            <input type="hidden" name="url" value="<?php echo $_POST['url']; ?>">
        </form>
        <script>
            document.myForm.submit();
        </script>
        <?php
    } else {
        ?>
        <script>
            location.replace('http://<?php echo $_POST['url']; ?>');
        </script>
        <?php
    }
} else {

    $lcurl = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    ?>
    <script src="js/jquery.min.js" type="text/javascript"></script>

    <form action="update_system.php?go=1" method="POST" name="myForm" id="myForm">
        <input type="hidden" name="url" value="<?php echo $lcurl; ?>">
    </form>
    <script>
            //        document.myForm.submit();
            $(document).ready(function () {
                setTimeout(function () {
                    $('#myForm').submit();
                }, 3000);
            });
    </script>
    <?php
}
?>
