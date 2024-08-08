<?php
require_once 'components/utils.php';
$db = connectToDB();

$getTasks = "SELECT `name`, `description` FROM `tasks` WHERE `id` = ?";
$getSubtasks = "SELECT * FROM `subtask` WHERE `linked` = ? ORDER BY `id` ASC";

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
    echo "<div class='subtask {$completionState}'>";
    
    echo "<label>{$completedText}</label>";
    echo "<input type='checkbox'>";
    $datetime = isset($subtask['datetime']) ? $subtask['datetime'] : "No due date"; //datetime can be NULL, if so, 
    echo "<p>" . isset($subtask['datetime']) ? $datetime->format('Y-m-d') : $datetime . "</p>";
    echo "</div>";
}

// Strictly for GET requests so a return header wont work here
?>