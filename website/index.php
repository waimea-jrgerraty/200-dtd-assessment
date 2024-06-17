<?php
require_once 'components/utils.php';
include_once 'partials/header.php';
include_once 'partials/dragAndDrop.php';
?>

<!-- Drag and drop test -->
<div id="div1" ondrop="drop(event)" ondragover="allowDrop(event)">
  <img src="img_w3slogo.gif" draggable="true" ondragstart="drag(event)" id="drag1" width="88" height="31">
</div>

<div id="div2" ondrop="drop(event)" ondragover="allowDrop(event)"></div>