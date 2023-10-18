<?php

include_once('config.php');
$connection = @mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);

if (!$connection) {
    throw new Exception('Cannot Connect to Database');
}

// $query  = "SELECT * FROM tasks WHERE complete = 0 ORDER BY date ";
$query = "SELECT * FROM " . DBTABLE . " WHERE complete = 0 ORDER BY date";
$result = mysqli_query($connection, $query);


// Complete Quary
$completeQuary = "SELECT * FROM " . DBTABLE . " WHERE complete = 1 ORDER BY date";
$completeResult = mysqli_query($connection, $completeQuary);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo Lists</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
</head>

<body>

    <div class="todo-area-wrapper"></div>
    <div class="container">
        <div class="row">
            <div class="column">
                <div class="content-area">
                    <a href="index.php">
                        <img src="assets/img/logo.png" alt="todolist">
                    </a>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis eligendi at earum impedit deleniti voluptatum quam enim molestias animi expedita?</p>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php

    ?>

    <div class="task-complete-area">
        <div class="container">
            <div class="row">
                <div class="column">
                    <?php if (mysqli_num_rows($completeResult) > 0) : ?>
                        <h2>Completed Tasks</h2>

                        <table>

                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Id</th>
                                    <th>Task</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                <?php
                                while ($cData = mysqli_fetch_assoc($completeResult)) :

                                    $cformatTime = strtotime($cData['time']);
                                    $cnewTime = date("h:i a", $cformatTime);

                                ?>
                                    <tr>
                                        <td><input type="checkbox" id="confirmField" value="<?php echo $cData['id'] ?>"></td>
                                        <td><?php echo $cData['id'] ?></td>
                                        <td><?php echo $cData['task'] ?></td>
                                        <td><?php echo $cData['date'] ?></td>
                                        <td><?php echo $cnewTime ?></td>
                                        <td><a class="delete" data-taskid="<?php echo $cData['id'] ?>" href="#">Delete</a> <span class="incomplete" data-taskid="<?php echo $cData['id'] ?>"><i class="fa fa-times" aria-hidden="true"></i></span></td>
                                    </tr>


                                <?php endwhile; ?>
                            <?php endif; ?>

                            </tbody>
                        </table>


                </div>
            </div>
            <div class="row">
                <div class="column upcoming-task-area">

                    <?php if (mysqli_num_rows($result) > 0) : ?>
                        <h2>UpComing Tasks</h2>

                        <form action="task.php" method="POST">
                            <table>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Id</th>
                                        <th>Task</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php while ($data = mysqli_fetch_assoc($result)) : ?>

                                        <?php
                                        $formatTime = strtotime($data['time']);
                                        $newTime = date("h:i a", $formatTime);

                                        ?>
                                        <tr>
                                            <td><input name="taskids[]" type="checkbox" id="confirmField" value="<?php echo $data['id'] ?>"></td>
                                            <td><?php echo $data['id'] ?></td>
                                            <td><?php echo $data['task'] ?></td>
                                            <td><?php echo $data['date'] ?></td>
                                            <td><?php echo $newTime ?></td>
                                            <td><a class="delete" data-taskid="<?php echo $data['id'] ?>" href="#">Delete</a>|<a class="complete" data-taskid="<?php echo $data['id'] ?>" href="#">Complete</a></td>
                                        </tr>
                                    <?php

                                    endwhile;
                                    mysqli_close($connection); ?>



                                </tbody>
                            </table>



                            <select id="action" name="action">
                                <option value="0">With Selected</option>
                                <option value="bulkdelete">Delete</option>
                                <option value="bulkcomplete">Mark As Complete</option>
                            </select>
                            <input class="button-primary" id="bulksubmit" type="submit" value="Submit">
                        </form>
                    <?php else : ?>
                        <p>No Tasks Found</p>
                    <?php endif; ?>



                </div>
            </div>
        </div>
    </div>

    <div class="task-add-wrapper">
        <div class="container">
            <div class="row">
                <div class="column">
                    <div class="content-area todoadd">
                        <h1>Add Todo Lists</h1>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corporis eligendi at earum impedit deleniti voluptatum quam enim molestias animi expedita?</p>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="column">
                    <form method="POST" action="task.php">
                        <?php
                        $added = $_GET['added'] ?? '';
                        if ($added) {
                            echo "<p>Task Added Succesfully.</p>";
                        }
                        ?>
                        <fieldset>
                            <input type="text" placeholder="Task Name" id="task" name="task">
                            <input type="date" id="date" name="date">
                            <input class="button-primary" type="submit" value="Send">
                            <input type="hidden" name="action" value="add">
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- FOR COMPLETE -->
    <form action="task.php" method="post" id="completeform">
        <input type="hidden" name="action" value="complete">
        <input type="hidden" name="taskid" id="taskid">
    </form>

    <!-- FOR DELETE -->
    <form action="task.php" method="post" id="deleteform">
        <input type="hidden" name="action" value="delete">
        <input type="hidden" name="dtaskid" id="dtaskid">
    </form>


    <!-- FOR INCOMPLETE -->
    <form action="task.php" method="post" id="incompleteform">
        <input type="hidden" name="action" value="incomplete">
        <input type="hidden" name="itaskid" id="itaskid">
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        ;
        (function($) {
            $(document).ready(function() {
                $(".complete").on('click', function() {
                    var id = $(this).data('taskid');
                    $("#taskid").val(id);
                    $("#completeform").submit();
                });

                $('.delete').on('click', function() {

                    if (confirm("are you sure you want to remove ?")) {
                        var id = $(this).data('taskid');
                        $('#dtaskid').val(id);
                        $('#deleteform').submit();
                    }
                });

                $('.incomplete').on('click', function() {
                    var id = $(this).data('taskid');
                    $('#itaskid').val(id);
                    $('#incompleteform').submit();

                });


            });
        })(jQuery);
    </script>
</body>

</html>