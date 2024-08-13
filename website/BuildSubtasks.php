<?php
require_once 'components/utils.php';
$db = connectToDB();

$getTasks = "SELECT `name`, `description` FROM `tasks` WHERE `id` = ?";
$getSubtasks = "SELECT `id`, `task`, `image_type`, `deadline`, `completed` FROM `subtask` WHERE `linked` = ? ORDER BY `id` ASC";

try {
    $stmt = $db->prepare($getTasks);
    $stmt->execute([$_REQUEST['id']]);
    $task = $stmt->fetch();
}
catch (PDOException $e) {
    consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
    die('There was an error fetching the length of the supercategory table');
}
try {
    $stmt = $db->prepare($getSubtasks);
    $stmt->execute([$_REQUEST['id']]);
    $subtasks = $stmt->fetchAll();
}
catch (PDOException $e) {
    consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
    die('There was an error fetching the length of the supercategory table');
}
$desc = nl2br($task['description']);
// Build menu content
echo "<h3 id='taskName'>{$task['name']}</h3>";
echo "<button id='newSubtask'>+</button>";
echo "<div id='description' class='textArea'>
    <p>{$desc}</p>
</div>";
foreach ($subtasks as $subtask) {
    $completed = $subtask['completed'];
    $completedText = ($completed == 1) ? ("Completed") : ("Incompleted");
    $completionState = "unfinishedSafe"; //TODO state complete if finished and unfinishedUnsafe if almost or already due but not finished
    $imageSet = $subtask['image_type'] !== null;
    $imageState = ($imageSet) ? "ImageSet" : "ImageUnset";
    echo "<article class='subtask {$completionState} {$imageState}' data-id='{$subtask['id']}'>";
    echo "<button type='button' class='subtaskDelete'>🗙</button>";
    
    echo "<div class='subtaskCell'>";

    $taskName = nl2br($subtask['task']);
    echo "<div class='textarea'>{$taskName}</div>";
    
    $completedBool = ($completed == 1) ? "checked='checked'" : '';
    echo "<label>{$completedText}</label>";
    echo "<input type='checkbox' {$completedBool}>";

    $datetime = ($subtask['deadline'] != null) ? new DateTime($subtask['deadline']) : null;
    if ($datetime !== null) {
        echo "<p>" . $datetime->format('Y/m/d h:i a') . "</p>";
    } else {
        echo "<p>No due date</p>";
    }
    echo "</div>";

    if ($imageSet) {
        echo "<div class='subtaskCell'>";
        echo "<img src='loadImage.php?id={$subtask['id']}'>";
        echo "</div>";
    }

    echo "</article>";
}

// Strictly for GET requests so a return header wont work here
?>
