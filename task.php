<?php
include_once('config.php');
date_default_timezone_set('asia/dhaka');

$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$connection) {
    throw new Exception('Cannot Connect to Database');
} else {

    // $action = isset($_POST['action']) ?? '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';



    if (!$action) {
        header("location: index.php");
        die();
    } else {
        if ('add' == $action) {
            $task = isset($_POST['task']) ? mysqli_real_escape_string($connection,  $_POST['task']) : '';
            $date = isset($_POST['date']) ? mysqli_real_escape_string($connection, $_POST['date']) : '';
            $time = date("H:i:s");

            if ($task &&  $date) {
                $query = "INSERT INTO " . DBNAME . "(task , date , time) VALUES ('{$task}', '{$date}' , '{$time}')";
                echo $query;
                mysqli_query($connection, $query);
                header('location: index.php?added=true');
            }
        } else if ('complete' == $action) {
            $taskid = $_POST['taskid'];
            if ($taskid) {
                $query = "UPDATE " . DBNAME . " SET complete= 1 WHERE id={$taskid} LIMIT 1";
                mysqli_query($connection, $query);
                header('location: index.php');
            }
        } else if ('delete' == $action) {
            $taskid = $_POST['dtaskid'];
            if ($taskid) {
                $query = "DELETE  FROM " . DBNAME . " WHERE id={$taskid} LIMIT 1";
                mysqli_query($connection, $query);
                header('location: index.php?delete=true');
            }
        } else if ('incomplete' == $action) {
            $taskid = $_POST['itaskid'];
            if ($taskid) {
                $query = "UPDATE " . DBNAME . " SET complete=0 WHERE id={$taskid} LIMIT 1";
                mysqli_query($connection, $query);
                header('location: index.php');
            }
        } else if ('bulkcomplete' == $action) {

            $taskids = $_POST['taskids'];
            $_taskids = join(',', $taskids);

            if ($taskids) {
                $query = "UPDATE " . DBNAME . " SET complete=1 WHERE id in ({$_taskids})";
                echo $query;
                mysqli_query($connection, $query);
                header('location: index.php');
            }
        }
    }
}


mysqli_close($connection);
