<?php include('includes/init.php');?>
<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

    <title>Home</title>
  </head>

  <body class="no-margin">
    <?php include("includes/sidebar.php"); ?>

    <div id = "main-div">
      <?php
      // DEFAULT IF NO TAGS SELECTED, SHOW ALL IMAGES
      $sql = "SELECT * FROM photos";
      $params = array();
      $records = exec_sql_query($db, $sql, $params)->fetchAll();

      if (isset($records) and !empty($records)) {
        gallery($records);
      }
      else {
        array_push($messages, "No images found.");
      }
      ?>
    </div>
  </body>
</html>
