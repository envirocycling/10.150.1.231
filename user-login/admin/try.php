<?php
session_start();
echo $tid = $_SESSION['tid'];
	echo $strno = $_SESSION['strno'];
	if(isset($_SESSION['update']))
	{
	echo "asds";
	}
?>
<form action="" method="post">
	<input type="submit" name="submit">
</form>
<?php
if(isset($_POST['submit'])){
?>
<script>
	location.replace("http://ims.efi.net.ph/try.php");
</script>
<?php }?>