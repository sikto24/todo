<?php

include_once('config.php');
$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (!$connection) {
    throw new Exception('Cannot Connect to Database');
} else {
    $result = mysqli_query($connection, "SELECT * FROM " . DBTABLE);

    if ($result) {
        $datas = mysqli_fetch_assoc($result);
        foreach ($datas as $data) {
            echo "<pre>";
            print_r($data);
            echo "</pre>";
        }
    } else {
        // Handle the query error
        echo "Error: " . mysqli_error($connection);
    }
}
