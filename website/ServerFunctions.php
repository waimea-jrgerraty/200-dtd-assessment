<?php
require_once 'components/utils.php';
$db = connectToDB();
$type = $_POST ? $_POST['type'] : $_REQUEST['type'];

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
            die('There was an error adding a new category');
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
    case "subtask": // Add new subtask
        //TODO
        // need to handle the image uploads here
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
            die('There was an error adding a new category');
        }
        break;
}

header('Location: ' . $_SERVER['HTTP_REFERER']); ?>