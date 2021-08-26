<?php

session_start();
date_default_timezone_set("Asia/Singapore");
$date = date("Y-m-d H:i:s");

include '../config.php';
//
//function getName($user_id) {
//    $sql = mysql_query("SELECT * FROM users WHERE user_id='$user_id'");
//    $rs = mysql_fetch_array($sql);
//    $name = strtoupper($rs['initial']) . "" . strtolower(substr($rs['lastname'], 1));
//    return $name;
//}

if (isset($_POST['action']) && $_POST['action'] == 'select') {
    $sql = mysql_query("SELECT * FROM comments WHERE tbl='" . $_POST['tbl'] . "' and row_id='" . $_POST['row_id'] . "' and status!='deleted'");
    while ($rs = mysql_fetch_array($sql)) {

//        $username = getName($rs['user_id']);
        $comment[] = array(
            'comment_id' => $rs['comment_id'],
            'username' => $rs['user_initial'],
            'comment' => $rs['comment'],
            'date' => date("F d, Y h:i a", strtotime($rs['date']))
        );
    }
    echo json_encode($comment);
}

if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $comment = mysql_real_escape_string($_POST['comment']);

    $sql_bi = mysql_query("SELECT branch_id FROM adv WHERE ac_id='" . $_POST['row_id'] . "'");
    $rs_bi = mysql_fetch_array($sql_bi);

    mysql_query("INSERT INTO `comments`(`tbl`, `row_id`, `user_id`, `branch_id`, `user_initial`, `comment`, `date`)
            VALUES ('" . $_POST['tbl'] . "','" . $_POST['row_id'] . "','" . $_SESSION['user_id'] . "','" . $rs_bi['branch_id'] . "','" . $_POST['username'] . "','$comment','$date')");

    $sql_max = mysql_query("SELECT max(comment_id) FROM comments WHERE tbl='" . $_POST['tbl'] . "' and row_id='" . $_POST['row_id'] . "'");
    $rs_max = mysql_fetch_array($sql_max);

    echo $rs_max['max(comment_id)'];
}

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    mysql_query("UPDATE comments SET status='deleted' WHERE comment_id='" . $_POST['comment_id'] . "'");
}
?>