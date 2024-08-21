<?php
require_once 'components/utils.php';
$db = connectToDB();
// Type defines what operation we are doing, and means I can have a single script do most of the functionality.
$type = (isset($_POST['type'])) ? $_POST['type'] : $_REQUEST['type'];

// Updates the completion value of a task based on the percentage of it's subtasks that are completed.
function updateTaskCompletion($id) {
    // Get the subtaks linked to this task
    $get = "SELECT `completed` FROM `subtask` WHERE `linked` = ?";
    $db = connectToDB(); // function has a different scope so we need to connect to the DB again

    try {
        $stmt = $db->prepare($get);
        $stmt->execute([$id]);
        $subtasks = $stmt->fetchAll();
    }
    catch (PDOException $e) {
        consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
        die('There was an error updating task completion');
    }

    // Calculate the fraction of completed subtasks
    $total = 0;
    $count = 0;

    foreach($subtasks as $subtask) {
        $count++;
        $total += $subtask['completed'];
    }

    // Multiply by 100 to get a percentage
    $percentage = $total / max($count, 1) * 100;

    $upd = "UPDATE `tasks` SET `completion` = ? WHERE `id` = ?";

    // Update the completion value to the percentage
    // This will be rounded to 0 dp when displayed
    try {
        $stmt = $db->prepare($upd);
        $stmt->execute([$percentage, $id]);
    }
    catch (PDOException $e) {
        consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
        die('There was an error updating task completion');
    }
}

switch ($type) {
    case "sCategory": // Add new supercategory
        // find the max priority from the database
        $get = "SELECT COUNT(`id`) AS `len` FROM `supercategory`";
        try {
            $stmt = $db->prepare($get);
            $stmt->execute();
            $len = $stmt->fetch()['len'];
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error fetching the length of the supercategory table');
        }
        // len includes the archive (priority not counted) but we need +1 anyway for the new table so it works fine
        $ins = "INSERT INTO `supercategory` (`name`, `order`) VALUES (?,$len)";
        try {
            $stmt = $db->prepare($ins);
            $stmt->execute([$_POST['name']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error adding a new supercategory');
        }
        break;
    case "sCategoryReorder": // Reorder a supercategory when dragged and dropped in the list
        $upd = "UPDATE `supercategory` SET `order` = ? WHERE `id` = ?";

        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$_POST['order'],$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        break;
    case "sCategoryRemove": // Remove a supercategory from the database
        $rem = "DELETE FROM `supercategory` WHERE `id` = ?"; // All descendant tables should be ON DELETE CASCADE

        try {
            $stmt = $db->prepare($rem);
            $stmt->execute([$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        break;
    case "category": // Add new category
        // find the max priority from the database
        $sCategory = $_POST["supercategory"];
        $get = "SELECT COUNT(`id`) AS `len` FROM `category` WHERE `supercategory` = ?";
        try {
            $stmt = $db->prepare($get);
            $stmt->execute([$sCategory]);
            $len = $stmt->fetch()['len'];
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error fetching the length of the category table');
        }

        // Insert at max priority
        $ins = "INSERT INTO `category` (`name`, `order`, `supercategory`) VALUES (?,?,?)";
        
        try {
            $stmt = $db->prepare($ins);
            $stmt->execute([$_POST['name'], $len + 1, $sCategory]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error adding a new category');
        }
        break;
    case "categoryRemove": // delete a category
        if ($_POST['id'] == 2) {
            die("Cannot delete archived category");
        }
        $rem = "DELETE FROM `category` WHERE `id` = ?"; // All descendant tables should be ON DELETE CASCADE
        
        try {
            $stmt = $db->prepare($rem);
            $stmt->execute([$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }
        break;
    case "categoryReorder": // Reorder a category in the list when dragged and dropped
        $upd = "UPDATE `category` SET `order` = ? WHERE `id` = ?";

        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$_POST['order'],$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        break;
    case "task": // Add new task
        // find the max priority from the database
        $category = $_POST["category"];
        $get = "SELECT COUNT(`id`) AS `len` FROM `tasks` WHERE `category` = ?";
        try {
            $stmt = $db->prepare($get);
            $stmt->execute([$category]);
            $len = $stmt->fetch()['len'];
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error fetching the length of the category table');
        }

        // Insert at max priority
        $ins = "INSERT INTO `tasks` (`name`, `description`, `order`, `category`) VALUES (?,?,?,?)";
        
        try {
            $stmt = $db->prepare($ins);
            $stmt->execute([$_POST['name'], $_POST['description'], $len + 1, $category]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error adding a new task');
        }
        break;
    case "taskRemove": // delete a category
        $rem = "DELETE FROM `tasks` WHERE `id` = ?"; // All descendant tables should be ON DELETE CASCADE
        
        try {
            $stmt = $db->prepare($rem);
            $stmt->execute([$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }
        break;
    case "taskReorder": // change the order of this task when dragged and dropped in the list
        $upd = "UPDATE `tasks` SET `order` = ?, `category` = ? WHERE `id` = ?";

        // We may have to change category too, so also update the category foreign key
        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$_POST['order'],$_POST['cat'],$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        break;
    case "taskArchive": // Move a task to the archive
        $upd = "UPDATE `tasks` SET `category` = 2 WHERE `id` = ?"; // Archive category has index 2

        // Move the task into the archive category (which is in the archive supercategory)
        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        break;
    case "subtask": // Add new subtask
        $imageData = null;
        $imageType = null;
        // max 16MB (there is a php file transfer limit as well I think so oh well)
        
        // If the user provided an image, convert it to binary data to save into the database
        if (array_key_exists("image", $_FILES) && !$_FILES['image']['error']) {
            if ($_FILES["image"]["size"] <= 16777215) { 
                [
                    'data' => $imageData,
                    'type' => $imageType
                ] = uploadedImageData($_FILES['image']);
            }
        }

        $ins = "INSERT INTO `subtask` (`task`, `image_type`, `image_data`, `deadline`, `linked`, `completed`) VALUES (?,?,?,?,?,?)";
        
        // Get the users local timezone, so we can subtract the offset from their date to get it into UTC
        $timezoneOffset = $_POST["timezone"];
        // If the javascript that sets the timezone doesn't work then other things will probably break too.
        // Therefore we tell the user to enable javascript or upgrade their browser if for some reason it doesn't support something
        if ($timezoneOffset === null) {
            die("Timezone not set, please enable javascript on this site, or upgrade your browser");
        }
        $timezoneOffset = intval($timezoneOffset); // will be negative if ahead of UTC, and positive if behind UTC.
        $deadline = ($_POST["deadline"] === '') ? null : $_POST["deadline"];
        
        // Deadlines are optional, so only do this if the user submits a deadline
        if ($deadline !== null) {
            $deadline = new DateTime($deadline, new DateTimeZone('UTC'));
            
            // Adjust the datetime based on the timezone offset
            $timezoneOffsetInverval = new DateInterval('PT' . abs($timezoneOffset) . 'M');
            
            // Check if the offset is positive or negative and apply it
            if ($timezoneOffset < 0) {
                $deadline->sub($timezoneOffsetInverval); // Subtract if positive (behind UTC)
            } else {
                $deadline->add($timezoneOffsetInverval); // Add if negative (ahead of UTC)
            }

            // The datetime is now in UTC, format it for SQL insertion
            $deadline = $deadline->format('Y-m-d H:i:s');
        }

        // Save the subtask to the database
        try {
            $stmt = $db->prepare($ins);
            $stmt->execute([$_POST['task'], $imageType, $imageData, $deadline, $_POST["linked"], 0]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error adding a new subtask');
        }

        break;
    case "subtaskDelete": // Delete a subtask from the database
        $getVal = "SELECT `linked` FROM `subtask` WHERE id = ?";
        $rem = "DELETE FROM `subtask` WHERE `id` = ?";
        
        // Get the task the subtask was linked to so we can update that tasks completion
        try {
            $stmt = $db->prepare($getVal);
            $stmt->execute([$_POST['id']]);
            $prev = $stmt->fetch()['linked'];
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        // Actually remove that task
        try {
            $stmt = $db->prepare($rem);
            $stmt->execute([$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        // Update the completion status of the linked task
        updateTaskCompletion($prev);

        break;
    case "subtaskCompletion":
        $getVal = "SELECT * FROM `subtask` WHERE id = ?";
        $upd = "UPDATE `subtask` SET `completed` = ? WHERE id = ?";
        
        // Get the current value and linked task
        try {
            $stmt = $db->prepare($getVal);
            $stmt->execute([$_POST['id']]);
            $curr = $stmt->fetch();
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        // Invert the current value
        $newval = ($curr['completed'] == 1) ? 0 : 1;

        // Replace the saved value with the inverted value
        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$newval, $_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        // Update the completion status of the linked task
        updateTaskCompletion($curr['linked']);

        break;
}

header('Location: ' . $_SERVER['HTTP_REFERER']); ?>
