<html>
<title>WP Inventory System</title>
<link rel="shortcut icon" type="image/x-icon" href="../images/icon/logo.png" />
	<body>
<?php
include("config.php");


?>

<center>
	<div style="font-size:40px;font-weight:bold; color:#006600;">System is Updating</div>
	<div style="font-size:25px;font-weight:bold;">Supplier</div>
	<div><img src="../images/loading.gif"></div>
	<div style="font-size:25px;font-weight:bold; color:#FF0000;">Do not Close this Window</div>
</center>

<?php		

$url = 'http://ims.efi.net.ph/update_supp_pam.php';
//$url = 'http://192.168.10.200/ts/update_supp_pam.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo 'Supplier ID: '.$_POST['supplier_id'].' Supplier Name: '. $_POST['supplier_name'];
		
$supplier_id = $_POST['supplier_id'];
$supplier_name = $_POST['supplier_name'];
$classification = $_POST['classification'];
$branch = $_POST['branch'];
$street = $_POST['street'];
$municipality = $_POST['municipality'];
$province = $_POST['province'];
$owner = $_POST['owner'];
$owner_contact = $_POST['owner_contact'];	
$plate_number= $_POST['plate_number'];
$date_added= $_POST['date_added'];	
$bank = $_POST['bank'];
$account_name = $_POST['account_name'];
$account_number = $_POST['account_number'];
		
$sql_supp = mysql_query("SELECT * FROM supplier WHERE supplier_id='$supplier_id' ") or die (mysql_error()); 
		
if(mysql_num_rows($sql_supp) == 1) {

	if(mysql_query("UPDATE supplier SET supplier_name='$supplier_name', classification='$classification', branch='$branch', street='$street', municipality='$municipality', province='$province', owner_name='$owner', owner_contact='$owner_contact', date_added='$date_added', bank='$bank', account_name='$account_number', account_number='$account_number' WHERE supplier_id='$supplier_id'")or die(mysql_error())){

            if (200 == $retcode) {?><script>
		window.top.location.href=("http://ims.efi.net.ph/update_supp_pam.php?up_supp=<?php echo $supplier_id.'&branch=Pampanga';?>");
		</script>
				<?php
						}else{
						$page = 'http://ims.efi.net.ph/update_supp_pam.php?up_supp='.$supplier_id.'&branch=Pampanga';
						$sec = "5";
						header("Refresh: $sec; url=$page");
					}
				}
			}else if(mysql_num_rows($sql_supp) == 0 && !empty($supplier_id)){
					if(mysql_query("INSERT INTO supplier (supplier_id,supplier_name, classification, branch, street, municipality, province, owner_name, owner_contact, date_added,bank,account_name,account_number) VALUES('$supplier_id','$supplier_name','$classification','$branch','$street','$municipality','$province','$owner','$owner_contact','$date_added','$bank','$account_name','$account_number')")or die(mysql_error())){
						if (200 == $retcode) {?><script>
								window.top.location.href=("http://ims.efi.net.ph/update_supp_pam.php?up_supp=<?php echo $supplier_id.'&branch=Pampanga';?>");
						</script>
					<?php
						}else{
						$page = 'http://ims.efi.net.ph/update_supp_pam.php?up_supp='.$supplier_id.'&branch=Pampanga';
						$sec = "5";
						header("Refresh: $sec; url=$page");
					}
				}
                        }else if(empty($supplier_id)){
                            echo '<script>
                                window.top.location.href="http://ims.efi.net.ph/update_supp_pam.php?branch=Pampanga";
                            </script>';
                            
                        }
	


?>
	</body>
</html>
