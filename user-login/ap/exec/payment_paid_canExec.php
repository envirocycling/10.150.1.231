<?php

include '../config.php';
if ($_GET['payment'] == 'canSup') {
    $err = 0;
    $sql_check = mysql_query("SELECT * FROM payment_adjustment WHERE payment_id='" . $_POST['payment_id'] . "'");
    while ($rs_check = mysql_fetch_array($sql_check)) {
        if ($rs_check['ac_id'] != '0' && $rs_check['adj_type'] == 'add') {
            $sql_adv_less = mysql_query("SELECT * FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_check['ac_id'] . "' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
            $count_adv_less = mysql_num_rows($sql_adv_less);

            $sql_adv_pay = mysql_query("SELECT * FROM adv_payment WHERE ac_id='" . $rs_check['ac_id'] . "' and status!='cancelled'");
            $count_adv_pay = mysql_num_rows($sql_adv_pay);

            $total_count = $count_adv_less + $count_adv_pay;

            if ($total_count > 0) {
                $err++;
            }
        }
    }
    
    if ($err == 0) {

        mysql_query("UPDATE payment SET status='cancelled' WHERE payment_id='" . $_POST['payment_id'] . "'");

        $sql_check = mysql_query("SELECT * FROM payment_adjustment WHERE payment_id='" . $_POST['payment_id'] . "'");
        while ($rs_check = mysql_fetch_array($sql_check)) {
            if ($rs_check['ac_id'] != '0') {
                if ($rs_check['adj_type'] == 'add') {
                    mysql_query("UPDATE adv SET payment_id='0', status='approved', date_processed='0000-00-00 00:00:00' WHERE ac_id='" . $rs_check['ac_id'] . "'");
                } else {
                    $sql_adv = mysqli_query($conn, "SELECT * FROM adv WHERE ac_id='" . $rs_check['ac_id'] . "'");
                    $rs_count = mysqli_num_rows($sql_adv);
                    $rs_adv = mysqli_fetch_array($sql_adv);
                    if ($rs_count > 0) {
                        $sql_adv_less = mysqli_query($conn, "SELECT sum(amount) FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_check['ac_id'] . "' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
                        $rs_adv_less = mysqli_fetch_array($sql_adv_less);

                        $sql_adv_pay = mysqli_query($conn, "SELECT sum(amount) FROM adv_payment WHERE ac_id='" . $rs_check['ac_id'] . "' and status!='cancelled'");
                        $rs_adv_pay = mysqli_fetch_array($sql_adv_pay);

                        $total_less = $rs_adv_pay['sum(amount)'] + $rs_adv_less['sum(amount)'];

                        $total = $rs_adv['amount'] - $total_less;
                        if ($total <= 0) {
                            mysqli_query($conn, "UPDATE adv SET status='paid', date_paid='$date_time' WHERE ac_id='" . $rs_check['ac_id'] . "'");
                        } else {
                            mysqli_query($conn, "UPDATE adv SET status='issued' WHERE ac_id='" . $rs_check['ac_id'] . "'");
                        }
                    }
                }
            }
        }

        mysql_query("UPDATE scale_receiving SET status='generated' WHERE payment_id='" . $_POST['payment_id'] . "'");
        echo "successed";
    } else {
        echo "failed_2";
    }
}
if ($_GET['payment'] == 'canOth') {
    mysql_query("UPDATE payment SET status='cancelled' WHERE payment_id='" . $_POST['payment_id'] . "'");

    echo "successed";
}
if ($_GET['payment'] == 'canAdv') {
    $sql_check = mysql_query("SELECT * FROM adv WHERE payment_id='" . $_POST['payment_id'] . "'");
    $rs_check = mysql_fetch_array($sql_check);

    if ($rs_check['branch_id'] == '7') {
        $sql_adv_less = mysql_query("SELECT * FROM payment_adjustment INNER JOIN payment ON payment_adjustment.payment_id=payment.payment_id WHERE payment_adjustment.ac_id='" . $rs_check['ac_id'] . "' and payment_adjustment.adj_type='deduct' and payment.status!='cancelled' and payment.status!='deleted'");
        $count_adv_less = mysql_num_rows($sql_adv_less);

        $sql_adv_pay = mysql_query("SELECT * FROM adv_payment WHERE ac_id='" . $rs_check['ac_id'] . "' and status!='cancelled'");
        $count_adv_pay = mysql_num_rows($sql_adv_pay);

        $total_count = $count_adv_less + $count_adv_pay;
        if ($total_count > 0) {
            echo "failed_2";
        } else {
            mysql_query("UPDATE payment SET status='cancelled' WHERE payment_id='" . $_POST['payment_id'] . "'");

            mysql_query("UPDATE adv SET status='approved' WHERE payment_id='" . $_POST['payment_id'] . "'");
            echo "successed";
        }
    } else {
        echo "failed";
    }
}
?>
