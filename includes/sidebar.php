<?php

// UPLOAD PICTURE
const UPLOADS_PATH = "uploads/images/";

if (isset($_POST["upload"]) and isset($_FILES["picture_file"])) {
  $upload_info = $_FILES["picture_file"];
  $credit = filter_input(INPUT_POST, 'credit', FILTER_SANITIZE_STRING);
  $tags = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
  var_dump($tags);

  $user_id = user_id($current_user);

  if ($_FILES["picture_file"]['error'] == 'UPLOAD_ERROR_OK') {
    $file_name = basename($upload_info["name"]);
    $file_path = UPLOADS_PATH . $file_name;
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $user_id = user_id($current_user);

    if ($user_id) { // must be logged in
      // insert picture into PHOTOS table
      $sql = "INSERT INTO photos(user_id, image_path, credit) VALUES (:user_id, :image_path, :credit);";
      $params = array(
        ":user_id"=>$user_id,
        ":image_path"=>$file_path,
        ":credit"=>$credit
      );
      $result = exec_sql_query($db, $sql, $params);

      if ($result) {
        array_push($messages, "File has been successfully uploaded.");
        $path = UPLOADS_PATH . $file_name;
        $file_id = $db->lastInsertId("id");
        move_uploaded_file($upload_info['tmp_name'],$path);
      } else {
        array_push($messages, "failed to upload file.");
      }
      // insert picture into PHOTO_TAGS table
      $tag_array = explode(",",$tags); // break up tags

      foreach ($tag_array as $tag) {
        $tag = trim(filter_var($tag,FILTER_SANITIZE_STRING));

        if ($tag) {

          add_tag($tag);
          $tag_id = tag_id_query($tag);

          // query for photo.id
          $sql = "SELECT * FROM photos WHERE photos.image_path = :file_path;";
          $params = array(":file_path"=>$file_path);
          $records = exec_sql_query($db, $sql, $params)->FetchAll();
          $photo_id = $records[0]["id"];

          add_relationship($tag_id, $photo_id);
        }
      }
      /*header("Location: index.php");
      exit;*/

    } else {
      array_push($messages, "must be logged in.");
    }
  } else {
    record_message("invalid file upload.");
  }
}

// LOGIN
// Check if we should login the user
if (isset($_POST['login'])) {
  $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING));
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

  $current_user = log_in($username, $password);
}

// LOGOUT
if (isset($_POST['logout'])) {
  log_out();
}

// GET TAGS
$sql = "SELECT tag FROM tags ORDER BY tag ASC;";
$params = array();
$tagdisplay_records = exec_sql_query($db, $sql, $params)->FetchAll();

// GET CREDITS
$sql = "SELECT DISTINCT credit FROM photos ORDER BY credit ASC;";
$params = array();
$creditdisplay_records = exec_sql_query($db, $sql, $params)->FetchAll();
?>
<!DOCTYPE html>

<aside>
  <div id="sidebar">

  <!--TITLE-->
  <a href="index.php" id="title"><h1>My Gallery</h1></a>

  <?php print_messages(); ?>

  <!--LOGIN-->
  <?php if (!$current_user) { ?>
    <div class="form-div">
      <h2>Log in</h2>
      <form id="login_form" action="index.php" method="post">
        <input class="input-spacing" type="text" name="username" placeholder="username" required/>
        <input class="input-spacing" type="password" name="password" placeholder="password" required/><br>
        <button name="login" type="submit">Log In</button>
      </form>
    </div>
  <?php } ?>

  <!--LOGOUT-->
  <?php if ($current_user) { ?>
    <div class="form-div">
      <p class="no-margin">Logged in as <?php echo $current_user; ?>.</p>
      <form id="logout_form" action="index.php" method="post">
        <button name="logout" type="submit">Log Out</button>
      </form>
    </div>
  <?php } ?>

  <!--TAGS-->
  <div class="form-div">
    <h2>Tags</h2>
    <div id="tag-box">

    <?php if ($tagdisplay_records) { // should never be empty because of seed data
      $tag_links = array();

      foreach ($tagdisplay_records as $tag) {
        $tag_name = $tag["tag"];

        $tag_link = "<a href= index.php?tag=" . htmlspecialchars($tag_name) . " class = 'tag-link'>" . $tag_name . "</a>";
        array_push($tag_links, $tag_link);
        echo "<p>" . $tag_link . "</p>";
      }
    }
    ?>
    </div>
  </div>

  <!--UPLOAD-->
  <?php if ($current_user) { ?>
    <form class="form-div" enctype="multipart/form-data" action="" method="POST">
      <h2>Upload a Picture</h2>
      <input class="input-spacing" type="text" name="tag" placeholder="tag(s)" required/><br>
      <input class="input-spacing" type="text" name="credit" placeholder="photo credit"/><br>
      <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
      <input name="picture_file" type="file" required></input>
      <button name="upload" value="foo" type="submit">Upload</button>
    </form>
  <?php } ?>

  <!--CREDIT-->
  <div class="form-div">
    <h2>Credits</h2>
    <div id="tag-box">
    <?php if ($creditdisplay_records) {
      foreach ($creditdisplay_records as $credit) {
        $credit_name = $credit["credit"];
        echo "<p class='small'>" . htmlspecialchars($credit_name) . "</p>";
      }
    }
    ?>
    </div>
  </div>

  <!--PSEUDO FOOTER-->
  <div id="footer">
    <h3>Autumn C. Watt Designs</h3>
  </div>
</div>
</aside>
