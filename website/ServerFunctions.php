<?php
require_once 'components/utils.php';
$db = connectToDB();
$type = (isset($_POST['type'])) ? $_POST['type'] : $_REQUEST['type'];

function updateTaskCompletion($id) {
    $get = "SELECT `completed` FROM `subtask` WHERE `linked` = ?";
    $db = connectToDB();

    try {
        $stmt = $db->prepare($get);
        $stmt->execute([$id]);
        $subtasks = $stmt->fetchAll();
    }
    catch (PDOException $e) {
        consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
        die('There was an error updating task completion');
    }

    $total = 0;
    $count = 0;

    foreach($subtasks as $subtask) {
        $count++;
        $total += $subtask['completed'];
    }

    $fraction = $total / max($count, 1) * 100;

    $upd = "UPDATE `tasks` SET `completion` = ? WHERE `id` = ?";

    try {
        $stmt = $db->prepare($upd);
        $stmt->execute([$fraction, $id]);
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
    case "sCategoryReorder":
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
    case "sCategoryRemove":
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
    case "categoryReorder":
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
    case "taskReorder":
        $upd = "UPDATE `tasks` SET `order` = ?, `category` = ? WHERE `id` = ?";

        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$_POST['order'],$_POST['cat'],$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        break;
    case "taskArchive":
        $upd = "UPDATE `tasks` SET `category` = 2 WHERE `id` = ?"; // Archive has index 2

        print_r($_POST);
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
        // max 16MB
        
        if (array_key_exists("image", $_FILES) && !$_FILES['image']['error']) {
            if ($_FILES["image"]["size"] <= 16777215) { 
                [
                    'data' => $imageData,
                    'type' => $imageType
                ] = uploadedImageData($_FILES['image']);
            }
        }

        $ins = "INSERT INTO `subtask` (`task`, `image_type`, `image_data`, `deadline`, `linked`, `completed`) VALUES (?,?,?,?,?,?)";
        
        $deadline = ($_POST["deadline"] === '') ? null : $_POST["deadline"];

        try {
            $stmt = $db->prepare($ins);
            $stmt->execute([$_POST['task'], $imageType, $imageData, $deadline, $_POST["linked"], 0]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error adding a new subtask');
        }
        break;
    case "subtaskDelete":
        $getVal = "SELECT `linked` FROM `subtask` WHERE id = ?";
        $rem = "DELETE FROM `subtask` WHERE `id` = ?";
        
        try {
            $stmt = $db->prepare($getVal);
            $stmt->execute([$_POST['id']]);
            $prev = $stmt->fetch()['linked'];
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        try {
            $stmt = $db->prepare($rem);
            $stmt->execute([$_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        updateTaskCompletion($prev);

        break;
    case "subtaskCompletion":
        $getVal = "SELECT `completed`, `linked` FROM `subtask` WHERE id = ?";
        $upd = "UPDATE `subtask` SET `completed` = ? WHERE id = ?";
        
        try {
            $stmt = $db->prepare($getVal);
            $stmt->execute([$_POST['id']]);
            $prev = $stmt->fetch();
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error removing data from the database');
        }

        $newval = ($prev['completed'] == 1) ? 0 : 1;
        print($prev['completed']);
        print($newval);
        try {
            $stmt = $db->prepare($upd);
            $stmt->execute([$newval, $_POST['id']]);
        }
        catch (PDOException $e) {
            consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
            die('There was an error updating data from the database');
        }

        updateTaskCompletion($prev['linked']);

        break;
}

header('Location: ' . $_SERVER['HTTP_REFERER']); ?>
