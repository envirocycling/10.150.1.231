<?php
@session_start();
include 'config.php';
if (isset($_POST['submit'])) {
    $supplier_id = $_POST['supplier_id'];
    $supplier_name = $_POST['supplier_name'];
    $classification = $_POST['classification'];
    $owner_name = $_POST['owner_name'];
    $owner_contact = $_POST['contact'];
    $branch = $_POST['branch'];
    $street = $_POST['street'];
    $municipality = $_POST['municipality'];
    $province = $_POST['province'];
    $bank = $_POST['bank'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];
    $date_added = date("Y/m/d");
    mysql_query("INSERT INTO `supplier`(`supplier_id`, `supplier_name`, `branch`, `classification`, `owner_name`, `owner_contact`, `street`, `municipality`, `province`, `bank`, `account_name`, `account_number`, `date_added`)
        VALUES
        ('$supplier_id','$supplier_name','$branch','$classification','$owner_name','$owner_contact','$street','$municipality','$province','$bank','$account_name','$account_number','$date_added')");

    echo "<script>
        alert('Successfully Added');
        location.replace('suppliers.php');
        </script>";
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
            <form action="add_new_supplier.php" method="POST">
                <table class="table">
                    <tr>
                        <td colspan="2" align="center"><h3>Personal Info</h3></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Supplier ID:</td>
                        <td><input type="text" name="supplier_id" value=""></td>
                    </tr>
                    <tr>
                        <td>Supplier Name:</td>
                        <td><input type="text" name="supplier_name" value="" required></td>
                    </tr>
                    <tr>
                        <td>Owner Name:</td>
                        <td><input type="text" name="owner_name" value="" required></td>
                    </tr>
                    <tr>
                        <td>Contact Number:</td>
                        <td><input type="text" name="contact" value="" required></td>
                    </tr>
                    <tr>
                        <td>Branch</td>
                        <td><input type="text" name="branch" value="" required></td>
                    </tr>
                    <tr>
                        <td>Classification:</td>
                        <td>
                            <select name="classification" required>
                                <option value=""></option>
                                <option value="PM">PM</option>
                                <option value="C1">C1</option>
                                <option value="C2">C2</option>
                                <option value="C3">C3</option>
                                <option value="T1">T1</option>
                                <option value="T2">T2</option>
                                <option value="T3">T3</option>
                                <option value="J1">J1</option>
                                <option value="J2">J2</option>
                                <option value="J3">J3</option>
                                <option value="S1">S1</option>
                                <option value="S2">S2</option>
                                <option value="S3">S3</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><h3>Address Info</h3></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Street:</td>
                        <td><input type="text" name="street" value="" required></td>
                    </tr>
                    <tr>
                        <td>Municipality:</td>
                        <td><input type="text" name="municipality" value="" required></td>
                    </tr>
                    <tr>
                        <td>Province:</td>
                        <td><input type="text" name="province" value="" required></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center"><h3>Banking Info</h3></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Bank:</td>
                        <td><input type="text" name="bank" value=""></td>
                    </tr>
                    <tr>
                        <td>Account Name:</td>
                        <td><input type="text" name="account_name" value=""></td>
                    </tr>
                    <tr>
                        <td>Account Number:</td>
                        <td><input type="text" name="account_number" value=""></td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <font size="1" color="red">
                            Note: This is a temporary adding a supplier, you need to get id on IMS.
                            <!--<br>
                            (To get id you can <a href="">click here</a> or go to supplier list.)
                            <br> -->
                            </font>
                            <input class="submit" type="submit" name="submit" value="Submit">
                        </td>
                    </tr>
                </table>
            </form>
        </td>
    </tr>
</table>