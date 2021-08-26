<?php

$url = 'http://ims.efi.net.ph/actual_module_new1.php';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo $retcode.'=====';
//if (200 == $retcode) {
//    // All's well
//} else {
//    // not so much
//}
?>
