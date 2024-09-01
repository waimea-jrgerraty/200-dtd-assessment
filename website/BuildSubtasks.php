<?php
require_once 'components/utils.php';
$db = connectToDB();

// Build the subtask menu when the user clicks on a task

// Get the description of the task the user is opening
$getTasks = "SELECT `name`, `description` FROM `tasks` WHERE `id` = ?";
// Get the relevant information needed for all the subtasks of the task
$getSubtasks = "SELECT `id`, `task`, `image_type`, `deadline`, `completed` FROM `subtask` WHERE `linked` = ? ORDER BY `id` ASC";

// Fetch the task we are opening
try {
    $stmt = $db->prepare($getTasks);
    $stmt->execute([$_REQUEST['id']]);
    $task = $stmt->fetch();
}
catch (PDOException $e) {
    consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
    die('There was an error fetching the length of the supercategory table');
}

// Fetch all the subtasks we are accessing
try {
    $stmt = $db->prepare($getSubtasks);
    $stmt->execute([$_REQUEST['id']]);
    $subtasks = $stmt->fetchAll();
}
catch (PDOException $e) {
    consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
    die('There was an error fetching the length of the supercategory table');
}
// Turns all instances of \n into <br/> tags
$desc = htmlspecialchars_decode($task['description']);
// Build menu content
// Task name and description
echo "<h3 id='taskName'>{$task['name']}<span class='subtaskAdd'><button id='newSubtask'>+</button></span></h3>";
echo "<div id='description' class='textArea'>
    <p>{$desc}</p>
</div>";

foreach ($subtasks as $subtask) {
    // state machine to handle subtask completion status
    $completed = $subtask['completed'];
    $completedText = ($completed == 1) ? ("Completed") : ("Incompleted");
    $completionState = "unfinishedSafe";
    $uncheckedCompletionState = "unfinishedSafe";
    if ($completed == 1) {
        $completionState = "finished";
    }
    $datetime = ($subtask['deadline'] != null) ? new DateTime($subtask['deadline']) : null;
    $weekAway = new DateTime('+1 week');
    if ($datetime !== null && $datetime < $weekAway) {
        $uncheckedCompletionState = "unfinishedUnsafe";
        if ($completed != 1) {
            $completionState = "unfinishedUnsafe";
        } 
    }

    // state machine for if the subtask has an image in it
    // these state machines are used for css and are applied as classes
    $imageSet = $subtask['image_type'] !== null;
    $imageState = ($imageSet) ? "ImageSet" : "ImageUnset";
    // the completionState of the subtask will change when the checkbox is changed
    // article tags already come with some nice styling with pico css
    echo "<article class='subtask {$completionState} {$imageState}' data-id={$subtask['id']} data-uncheckedClass={$uncheckedCompletionState}>";
    echo "<button type='button' class='subtaskDelete'>🗙</button>";
    
    echo "<div class='subtaskMain'>";
    // Create a cell so we can have the image on the right and squish the text down if its present
    echo "<div class='subtaskCell'>";

    $taskName = htmlspecialchars_decode($subtask['task']);
    echo "<div class='textArea'>{$taskName}</div>";
    
    echo "<div class='subtaskTaskInfo'>";
    // Set the checkbox checked state to what is saved in the database
    $completedBool = ($completed == 1) ? "checked='checked'" : '';
    echo "<input type='checkbox' {$completedBool}>";
    echo "<label>{$completedText}</label>";

    if ($datetime !== null) {
        // The actual format will be done by javascript when converted to the users timezone
        // DateTime::ATOM is ISO 8601 format, this is what is read by js
        echo "<p class='datetimeToConvert'>" . $datetime->format(DateTime::ATOM) . "</p>";
    } else {
        echo "<p>No due date</p>";
    }
    echo "</div>";
    echo "</div>";

    // If no image is set, the first cell will fill the full width
    if ($imageSet) {
        echo "<div class='subtaskCell'>";
        echo "<img src='loadImage.php?id={$subtask['id']}'>";
        echo "</div>";
    }
    echo "</div>";

    echo "</article>";
}

// Strictly for GET requests so a return header wont work here
?>
