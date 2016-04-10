<?php
    include "dbcon.php";

    $data['time_ymd'] = date('ymd');
    $data['time_his'] = date('His');
    $data['temp'] = $_GET['temp'];

    $query  = "INSERT INTO `farm`(`time_ymd`, `time_his`, `temp`, `regi_date`)";
    $query .= " VALUES('".$data['time_ymd']."','".$data['time_his']."','".$data['temp']."', now())";

    @mysql_query($query, $conn);
?>
