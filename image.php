<?php include('includes/init.php');

// check to see if there is an image GET requested

if ((isset($_GET["id"]) or !empty($_GET["id"])) && photo_exists($_GET["id"])) {
  $photo_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
  $single_view_image = "$photo_id";
} else {
  header("Location: index.php");
  exit;
}

// QUERY DATABASE
$sql = "SELECT users.username, photos.image_path, photos.credit FROM photos INNER JOIN users ON photos.user_id = users.id WHERE photos.id = :photo_id";
$params = array(":photo_id"=>$photo_id);
$photo_records = exec_sql_query($db, $sql, $params)->fetchAll();

$sql = "SELECT tags.tag FROM photo_tags INNER JOIN tags ON photo_tags.tag_id = tags.id WHERE photo_tags.photo_id = :photo_id";
$params = array(":photo_id"=>$photo_id);
$tag_records = exec_sql_query($db, $sql, $params)->fetchAll();


// ADD TAG

if (isset($_POST["add_tag"]) && !empty($_POST["add_tag"])) {
  $existing_tag = filter_input(INPUT_POST, 'existing_tags', FILTER_SANITIZE_STRING);
  $new_tags = filter_input(INPUT_POST, 'new_tags', FILTER_SANITIZE_STRING);

  if ($existing_tag){
    $tag_id = tag_id_query($existing_tag);
    add_relationship($photo_id, $tag_id);

  } elseif ($new_tags) {
    $tag_array = explode(",",$new_tags); // break up tags

    foreach ($tag_array as $tag) {
      $tag = trim(filter_var($tag,FILTER_SANITIZE_STRING));

      if ($tag) {
        add_tag($tag);// add tag
        $tag_id = tag_id_query($tag);
        add_relationship($photo_id, $tag_id);
      }
    }
  } else {
    array_push($messages, "no tag recorded");
  }
}

function delete_empty_tag($tag) {
  global $db;

  $tag_id = tag_id_query($tag);
  $sql = "SELECT * FROM photo_tags WHERE photo_tags.tag_id = :tag_id;";
  $params = array(":tag_id"=>$tag_id);
  $records = exec_sql_query($db, $sql, $params)->FetchAll();

  if (empty($records)) { // if no results returned, tag is not connected to any photos
    $sql = "DELETE FROM tags WHERE tags.id = :tag_id;";
    $params = array(":tag_id"=>$tag_id);
    exec_sql_query($db, $sql, $params)->FetchAll();
  }
}

// REMOVE TAG
if (isset($_POST["remove_tag"]) && !empty($_POST["remove_tag"])) {
  $tag = filter_input(INPUT_POST, 'tags_for_photo', FILTER_SANITIZE_STRING);
  $tag_id = tag_id_query($tag);

  // check if tag photo relationship file_exists
  // this should never be a problem because select menu only shows tags for that photo
  $sql = "SELECT * FROM photo_tags WHERE photo_tags.tag_id = :tag_id AND photo_tags.photo_id = :photo_id;";
  $params = array(":photo_id"=>$photo_id, ":tag_id"=>$tag_id);
  $records = exec_sql_query($db, $sql, $params)->FetchAll();

  // if $records not empty, relationship exists
  if ($records) {

    $sql = "DELETE FROM photo_tags WHERE photo_tags.tag_id = :tag_id AND photo_tags.photo_id = :photo_id;";
    $params = array(":photo_id"=>$photo_id, ":tag_id"=>$tag_id);

    try {
      exec_sql_query($db, $sql, $params)->FetchAll();
      array_push($messages, "tag removal successful");
      delete_empty_tag($tag);

    } catch (Exception $e) {
      array_push($messages, "error occured");
    }
  } else {
    array_push($messages, "relationship does not exist");
  }
}

// DELETE PHOTO
if (isset($_POST["delete_image"]) && !empty($_POST["delete_image"])) {
  // query for all tags related to image
  $sql = "SELECT tags.tag FROM tags INNER JOIN photo_tags
                ON photo_tags.tag_id = tags.id
                      WHERE photo_tags.photo_id = :photo_id";
  $params = array(":photo_id"=>$photo_id);
  $records = exec_sql_query($db, $sql, $params)->fetchAll();

  // delete all photo and tag relationships from table PHOTO_TAGS
  if ($records) { // else image has no tags
    foreach ($records as $record) {
      $tag = $record[0];
      $tag_id = tag_id_query($tag);
      $sql = "DELETE FROM photo_tags WHERE photo_tags.tag_id = :tag_id AND photo_tags.photo_id = :photo_id;";
      $params = array(":photo_id"=>$photo_id, ":tag_id"=>$tag_id);
      exec_sql_query($db, $sql, $params)->FetchAll();

      // Delete tag if only tag for that
      delete_empty_tag($tag);
    }
  }

  // delete photo from PHOTOS table
  $sql = "DELETE FROM photos WHERE photos.id = :photo_id;";
  $params = array(":photo_id"=>$photo_id);
  exec_sql_query($db, $sql, $params)->FetchAll();

  // TODO: Delete file

}



?>

<!DOCTYPE html>
<html>

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />

    <title>Image View</title>
  </head>

  <body class="no-margin">
    <?php include("includes/sidebar.php"); ?>

    <div id = "main-div">
      <div id = "singleview-div">
      <?php

      if (isset($photo_records) and !empty($photo_records)) {
        single_view($photo_records, $tag_records);?>

        <!--ADD TAG FORM-->
        <form class="single-view-form" action="" method="post">

          <select name="existing_tags">
              <option value="" selected disabled>Existing Tags</option>

              <?php
              $sql = "SELECT tag FROM tags";
              $params = array();
              $records = exec_sql_query($db, $sql, $params)->fetchAll();

              foreach ($records as $record) {
                echo "<option value='" . $record[0] . "'>" . $record[0] . "</option>";
              }
              ?>
          </select>
          <span> OR </span>
          <input id="single-view-input" type="text" name="new_tags" placeholder="new tag(s)"/><br>
          <button class="single-view-button" name="add_tag" value="foo" type="submit">Add Tag</button>
        </form>

        <!--REMOVE TAG FORM if logged in-->
        <?php if ($current_user) { ?>
          <form class="single-view-form" action="" method="post">

            <select name="tags_for_photo" required>
                <option value="" selected disabled>Existing Tags for Photo</option>

                <?php
                $sql = "SELECT tags.tag FROM tags INNER JOIN photo_tags
                              ON photo_tags.tag_id = tags.id
                                    WHERE photo_tags.photo_id = :photo_id";
                $params = array(":photo_id"=>$photo_id);
                $records = exec_sql_query($db, $sql, $params)->fetchAll();

                foreach ($records as $record) {
                  echo "<option value='" . $record[0] . "'>" . $record[0] . "</option>";
                }
                ?>
            </select><br>
            <button class="single-view-button" name="remove_tag" value="foo" type="submit">Remove Tag</button>
          </form>
        <?php } ?>

        <!--DELETE IMAGE FORM if logged in-->
        <?php if ($current_user) { ?>
        <form class="single-view-form" action="" method="post">
          <button class="single-view-button" name="delete_image" value="foo" type="submit">Delete Image</button>
        </form>
        <?php } ?>

      <?php
      }
      else {
        array_push($messages, "Not a valid image view.");
      }
      ?>
      </div>
    </div>
  </body>
</html>
