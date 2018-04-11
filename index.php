<?php include('includes/init.php');

// check to see if there is a tag GET requested
if (isset($_GET["tag"]) or !empty($_GET["tag"])) {
  $tag = filter_input(INPUT_GET, 'tag', FILTER_SANITIZE_STRING);

  // QUERY DATABASE

  $sql = "SELECT photos.id, photos.image_path
          FROM photos
              INNER JOIN photo_tags
                  ON photos.id = photo_tags.photo_id
              INNER JOIN tags
                  ON photo_tags.tag_id = tags.id
                      WHERE tags.tag = :tag";
  $params = array(":tag"=>$tag);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();

  if (empty($records)) { // if no results
    array_push($messages, "no photos match tag");
  }
} else { // DEFAULT IF NO TAGS SELECTED, SHOW ALL IMAGES
  $sql = "SELECT * FROM photos";
  $params = array();
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
}
?>

<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

    <title>Home</title>
  </head>

  <body class="no-margin">
    <div id = "container">
      <?php include("includes/sidebar.php"); ?>

      <div id = "main-div">
        <?php
        if (isset($records) and !empty($records)) {
          gallery($records);
        }
        else {
          array_push($messages, "No images found.");
        }
        ?>
      </div>
    </div>
  </body>
</html>
