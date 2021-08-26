<?php
@session_start();
include 'config.php';
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $initial = $_POST['initial'];
    $position = $_POST['position'];
    $usertype = $_POST['usertype'];

    $sql_check = mysql_query("SELECT * FROM users WHERE username='$username'");
    $rs_check = mysql_num_rows($sql_check);

    if ($rs_check > 0) {
        echo "<script>
        alert('Failed to add, the username is already taken.');
        location.replace('users.php');
        </script>";
    } else {

        mysql_query("INSERT INTO `users`(`username`, `password`, `firstname`, `lastname`, `initial`, `branch`, `position`, `usertype`)
        VALUES
        ('$username','$password','$firstname','$lastname','$initial','" . $_SESSION['branch'] . "','$position','$usertype')");

		if ($usertype == 4){
		$sql_max = mysql_query("SELECT max(user_id) FROM users");
        $rs_max = mysql_fetch_array($sql_max);

        mysql_query("INSERT INTO temp_payment (user_id) VALUES ('" . $rs_max['max(user_id)'] . "')");

        $c = 1;
        while ($c <= 5) {
            mysql_query("INSERT INTO temp_payment_adjustment (user_id, adj_count) VALUES ('" . $rs_max['max(user_id)'] . "', '$c')");
            $c++;
        }

        $c = 1;
        while ($c <= 20) {
            mysql_query("INSERT INTO temp_payment_others (user_id, others_count) VALUES ('" . $rs_max['max(user_id)'] . "', '$c')");
            $c++;
        }

        $c = 1;
        while ($c <= 20) {
            mysql_query("INSERT INTO temp_payment_price_adj (user_id, price_adj_count) VALUES ('" . $rs_max['max(user_id)'] . "', '$c')");
            $c++;
        }
		}
        echo "<script>
        alert('Successfully Added');
        location.replace('users.php');
        </script>";
    }
}
?>
<style>
    input {
        height: 20px;
        width: 200px;
    }
    .table{
        font-size: 18px;
    }
    .submit{
        height: 20px;
        width: 80px;
        font-size: 12px;
    }
</style>
<table width="360">
    <tr>
        <td></td>
        <td>
            <form action="add_new_user.php" method="POST">
                <table class="table">
                    <tr>
                        <td colspan="2" align="center"><h3>User Info</h3></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Username:</td>
                        <td><input type="text" name="username" value="" required></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input type="password" name="password" value="" required></td>
                    </tr>
                    <tr>
                        <td>First Name:</td>
                        <td><input type="text" name="firstname" value="" required></td>
                    </tr>
                    <tr>
                        <td>Last Name:</td>
                        <td><input type="text" name="lastname" value="" required></td>
                    </tr>
                    <tr>
                        <td>Initial:</td>
                        <td><input type="text" name="initial" value="" required></td>
                    </tr>
                    <tr>
                        <td>Position:</td>
                        <td><input type="text" name="position" value="" required></td>
                    </tr>
                    <tr>
                        <td>UserType:</td>
                        <td><select name="usertype" required="">
                                <option value=""></option>
                                <option value="2">BH</option>
                                <option value="3">IC</option>
                                <option value="4">AP</option>
                                <option value="5">Rpt Viewer</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><input class="submit" type="submit" name="submit" value="Submit">
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>