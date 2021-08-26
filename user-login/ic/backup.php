<?php

include('config.php');
ini_set('memory_limit', '1024M');

$date_now = date("Y/m/d");
$datemonth_now = date("Y/m");
$sql_check = mysql_query("SELECT * FROM db_backup WHERE date like '%$datemonth_now%'");
$rs_check_num = mysql_num_rows($sql_check);
if ($rs_check_num == 0) {

    function backup_db() {
        /* Store All Table name in an Array */
        $allTables = array();
        $result = mysql_query('SHOW TABLES');
        while ($row = mysql_fetch_row($result)) {
            $allTables[] = $row[0];
        }

        foreach ($allTables as $table) {
            $result = mysql_query('SELECT * FROM ' . $table);
            $num_fields = mysql_num_fields($result);

            @$return.= 'DROP TABLE IF EXISTS ' . $table . ';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
            $return.= "\n\n" . $row2[1] . ";\n\n";

            for ($i = 0; $i < $num_fields; $i++) {
                while ($row = mysql_fetch_row($result)) {
                    $return.= 'INSERT INTO ' . $table . ' VALUES(';
                    for ($j = 0; $j < $num_fields; $j++) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        if (isset($row[$j])) {
                            $return.= '"' . $row[$j] . '"';
                        } else {
                            $return.= '""';
                        }
                        if ($j < ($num_fields - 1)) {
                            $return.= ',';
                        }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n";
        }
// Create Backup Folder
        $folder = '../../db/';
        if (!is_dir($folder))
            mkdir($folder, 0777, true);
        chmod($folder, 0777);
        $timezone = +8;
        $date = gmdate('m-d-Y_H-i A', time() + 3600 * ($timezone + date("I")));
        $filename = "ts-db-backup-" . $date;

        $handle = fopen($filename . '.sql', 'w+');
        fwrite($handle, $return);
        fclose($handle);

        $move_from = $filename . '.sql';
        $move_to = '../../db/' . $filename . '.sql';

        rename($move_from, $move_to);
    }

// Call the function
    $timezone = +8;
    $date = gmdate('m-d-Y_H-i A', time() + 3600 * ($timezone + date("I")));
    $namess = "ts-db-backup-" . $date . '.sql';
    backup_db();

    mysql_query("INSERT INTO db_backup (date, name) VALUES('$date_now','$namess')") or die(mysql_error());
}
?>
