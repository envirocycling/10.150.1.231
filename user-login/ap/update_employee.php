<center>
    <table>
        <tr>
            <td style="color: red;" align="center"><h2>Updating Employees</h2></td>
        </tr>
        <tr>
            <td><img src="images/updating.gif"></td>
        </tr>
        <tr>
            <td align="center" style="font-size: 18px;"><h3>Please wait.</h3></td>
        </tr>
    </table>
</center>

<?php
session_start();
$branch = $_SESSION['branch'];

$url = "http://mmsv2.efi.net.ph/mms_update_employee.php?branch=$branch";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo $ctr = $_POST['ctr'];
if ($ctr != 1 && !empty($ctr)) {
    include('config.php');
    $num = 1;
    while ($num < $ctr) {
//        $emp_num = $_POST['emp_num' . $num];
        $name = $_POST['name' . $num];
        $designation = $_POST['position' . $num];
        $department = $_POST['company' . $num];
        $branch = $_POST['branch' . $num];
        $sql_max = mysql_query("SELECT max(emp_id) as id from employee") or die(mysql_error());
        $row_max = mysql_fetch_array($sql_max);
        $emp_num = $row_max['id'] + 1;

        $sql_chk = mysql_query("SELECT * from employee WHERE name='$name'") or die(mysql_error());
        $row_chk = mysql_fetch_array($sql_chk);
        if (mysql_num_rows($sql_chk) > 0) {
            mysql_query("UPDATE employee SET name = '$name', designation = '$designation', department = '$department', branch = '$branch' WHERE name='$name'") or die(mysql_error());
        } else {
            mysql_query("INSERT INTO employee (name, designation, department, branch, emp_id) VALUES('$name', '$designation', '$department', '$branch', '$emp_num')") or die(mysql_error());
        }

        $num++;
    }
    echo '<script>
                alert("Update Successful.");
                location.replace("employee_advances_form.php");
        </script>';
} else

if ($retcode == 200) {
    echo '<script>
		window.top.location.href=("' . $url . '");
	</script>';
} else {
    echo '<script>
                alert("Server is down. Please try again later.");
                window.history.back();
        </script>';
}
?>