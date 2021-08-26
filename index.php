<?php
@session_start();

if (isset($_SESSION['ic_id'])) {
    echo "<script>location.replace('user-login/ic/');</script>";
}
if (isset($_SESSION['ap_id'])) {
    echo "<script>location.replace('user-login/ap/');</script>";
}
if (isset($_SESSION['bh_id'])) {
    echo "<script>location.replace('user-login/branch-head/');</script>";
}
if (isset($_SESSION['admin_id'])) {
    echo "<script>location.replace('user-login/admin/');</script>";
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Envirocycling Fiber Inc.</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="shortcut icon" href="images/efi_ico.png" />
        <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    </head>
    <body>
        <form action="validation.php" class="login" method="POST">
            <div style="margin-top: -7px; margin-left: -8px; position: absolute;"><img src="images/efi_ico.png" height="30" width="30"></div><h1>&nbsp;&nbsp;&nbsp;&nbsp;Envirocycling Fiber Inc.</h1>
        <!-- <input type="email" name="email" class="login-input" placeholder="Email Address" autofocus> -->
            <input type="text" name="username" class="login-input" placeholder="Username" autofocus>
            <input type="password" name="password" class="login-input" placeholder="Password">
            <input type="submit" value="Login" class="login-submit">
            <?php
            if (isset($_GET['error'])) {
                echo '<p class = "login-help"><font color="red">Wrong username or password.</font></p>';
            }
            ?>
        </form>

    <!-- <section class="about">
      <p class="about-links">
        <a href="http://www.cssflow.com/snippets/facebook-login-form" target="_parent">View Article</a>
        <a href="http://www.cssflow.com/snippets/facebook-login-form.zip" target="_parent">Download</a>
      </p>
      <p class="about-author">
        &copy; 2013 <a href="http://thibaut.me" target="_blank">Thibaut Courouble</a> -
        <a href="http://www.cssflow.com/mit-license" target="_blank">MIT License</a><br>
        Original PSD by <a href="http://dribbble.com/shots/808325-Facebook-Login-Freebie" target="_blank">Alex Montague</a>
      </p>
    </section> -->
    </body>
</html>
