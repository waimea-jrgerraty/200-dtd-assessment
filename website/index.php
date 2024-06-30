<?php
require_once 'components/utils.php';
include_once 'partials/header.php';
include_once 'partials/dnd.php';

$db = connectToDB();
$query = 'SELECT * FROM supercategory ORDER BY `order` DESC, `name`';

try {
  $stmt = $db->prepare($query);
  $stmt->execute();
  $sCategories = $stmt->fetchAll();
}
catch (PDOException $e) {
  consoleLog($e->getMessage(), 'DB List Fetch', ERROR);
  die('There was an error getting data from the database');
}
?>

<div class="SideNav">
  <div id="content">
    <div id="header">
      <h3>Supercategories</h3>
      <button type="button">+</button>
    </div>
    <!-- Generate our supercategories -->
    <?php
      foreach($sCategories as $sCategory) {
        if ($sCategory["name"] != "archived") {
          echo "<button type='button' value='{$sCategory['id']}'' class='sCategory'>{$sCategory['name']}</button>";
        }
      }
    ?>
  </div>
</div>

<div class="container">
  <div draggable="true" class="box">A</div>
  <div draggable="true" class="box">B</div>
  <div draggable="true" class="box">C</div>
</div>

</main>
</body>