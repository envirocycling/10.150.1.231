<?php

include('config.php');
$trans_id = $_GET['trans_id'];

$url = 'http://ims.efi.net.ph/delete_outgoing_new.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if (200 == $retcode) {

    $select = mysql_query("SELECT * from scale_outgoing WHERE  trans_id='$trans_id' and branch_id!='7'") or die(mysql_error());
    $row = mysql_fetch_array($select);
    $out_trans_id = $row['trans_id'];

    if (mysql_num_rows($select) > 0) {
        $del_out = mysql_query("DELETE from scale_outgoing WHERE trans_id='$trans_id'");
        $del_out = mysql_query("DELETE from scale_outgoing_details WHERE trans_id='$trans_id'");

        echo "<form action='http://ims.efi.net.ph/delete_outgoing_new.php' method='POST' name='myForm'>";
        echo "<input type='hidden' value='$trans_id' name='trans_id'>";
        echo"</form>";
        echo "<script>
    			document.myForm.submit();
			</script>";
    } else {
        ?>
        <script>
            alert("No Data Found!");
            top.location.href = 'outgoing.php';
        </script>
        <?php

    }
} else {
    ?>
    <script>
        alert("System is Down. Try again Later.");
        location.replace("outgoing.php");
    </script>
<?php

}?>