<?php

include_once('config.php');

$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$connection) {
    throw new Exception('Cannot Connect to Database');
} else {
    echo 'Data Base Connected';
}


$date = date("Y-m-d");
$time = date("H:i:s");

// echo mysqli_query($connection, 'INSERT INTO ' . DBTABLE . ' (task , date , time ) VALUES ("DO SOMETHING More", "' . $date . '" , "' . $time . '")');


$result = mysqli_query($connection, "SELECT * FROM " . DBTABLE);
while ($data = mysqli_fetch_assoc($result)) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

mysqli_close($connection);
