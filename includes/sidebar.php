<?php

// UPLOAD PICTURE
const UPLOADS_PATH = "uploads/";

if (isset($_POST["upload"]) and isset($_FILES["picture_file"])) {
  $upload_info = $_FILES["picture_file"];
  $credit = filter_input(INPUT_POST, 'credit', FILTER_SANITIZE_STRING);
  $tag = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
  if ($_FILES["picture_file"]['error'] == 'UPLOAD_ERROR_OK') {
    $file_name = basename($upload_info["name"]);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $user_id = user_id($current_user);

    // TODO: NEED TO INSERT TAG AND STUFF INTO tables

    if ($user_id) {
      $sql = "INSERT INTO photos(user_id, image_path, credit) VALUES (:user_id, :image_path, :credit);";
      $params = array(
        ":user_id"=>$user_id,
        ":image_path"=>$file_name,
        ":credit"=>$credit
      );

      $result = exec_sql_query($db, $sql, $params);
      if ($result) {
        array_push($messages, "File has been successfully uploaded.");
        $file_id = $db->lastInsertId("id");
        $path = UPLOADS_PATH . $file_name;
        move_uploaded_file($upload_info['tmp_name'],$path);
      } else {
        array_push($messages, "failed to upload file.");
      }
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
?>
<!DOCTYPE html>

<aside>
  <div id="sidebar">

  <!--TITLE-->
  <h1>My Gallery</h1>

  <?php print_messages(); ?>

  <!--TAGS-->

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

  <!--UPLOAD-->
  <?php if ($current_user) { ?>
    <form class="form-div" enctype="multipart/form-data" action="index.php" method="POST">
      <h2>Upload a Picture</h2>
      <input class="input-spacing" type="text" name="tag" placeholder="tag" required/><br>
      <input class="input-spacing" type="text" name="credit" placeholder="photo credit"/><br>
      <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
      <input name="picture_file" type="file" required></input>
      <button name="upload" type="submit">Upload</button>
    </form>
  <?php } ?>

  <!--ADD TAG-->

  <!--REMOVE TAG-->

  <!--possibly REMOVE PHOTO-->

  <!--PSEUDO FOOTER-->
</div>
</aside>
