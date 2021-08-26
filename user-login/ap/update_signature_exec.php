<?php
@session_start();
$file = $_FILES['attachment']['tmp_name'];
$image = addslashes(file_get_contents($_FILES['attachment']['tmp_name']));
$image_name = addslashes($_FILES['attachment']['name']);
$image_size = getimagesize($_FILES['attachment']['tmp_name']);

move_uploaded_file($_FILES["attachment"]["tmp_name"], "../../signatures/" . $_SESSION['initial'] . ".jpg");
?>
<script>
    alert('Successfully Updated.');
    location.replace('initial_settings.php');
</script>