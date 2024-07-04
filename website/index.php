<?php
require_once 'components/utils.php';

$db = connectToDB();
$query = 'SELECT `id`, `name` FROM supercategory ORDER BY `order` ASC, `name`';

try {
  $stmt = $db->prepare($query);
  $stmt->execute();
  $sCategories = $stmt->fetchAll();
} catch (PDOException $e) {
  consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
  die('There was an error getting data from the database');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Game Development Task Manager</title>

  <script src="components/dnd.js"></script>
  <script src="components/modalForm.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.min.css">
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header>
    <nav>
      <ul>
        <li class="centeredHeader"><strong>Game Development Task Manager</strong></li>
      </ul>
      <ul>
        <li><a href="">Alerts</a></li>
        <li><a href="">Archive</a></li>
      </ul>
    </nav>

    <div class="SideNav">
      <div id="content">
        <div id="header">
          <h3>Supercategories</h3>
          <button type="button" id="newSCat">+</button>
        </div>
        <!-- Generate our supercategories -->
        <?php
        foreach ($sCategories as $sCategory) {
          if ($sCategory["name"] != "archived") {
            echo
            "<div class ='sCategory' draggable='true' data-id='{$sCategory['id']}'>
                {$sCategory['name']}
              </div>";
          }
        }
        ?>
      </div>
    </div>
  </header>

  <main>

    <!-- Create the form modals here -->
    <div class="formModal" id="sCategoryForm">
      <div class="formContent">
        <h3>New Supercategory</h3>
        <form method="POST" action="ServerFunctions.php">
          <input name="type" type="hidden" value="sCategory">

          <label>Name</label>
          <input name="name" type="text" required>

          <button id="SCcancel" type="reset">Cancel</button>
          <button id="SCsubmit" type="submit">Submit</button>
        </form>
      </div>
    </div>

    <!-- Main page content -->
    <div class="container">
      <div draggable="true" class="box">A</div>
      <div draggable="true" class="box">B</div>
      <div draggable="true" class="box">C</div>
    </div>

  </main>
</body>