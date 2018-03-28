<?php

// An array to deliver messages to the user.
$messages = array();

// Record a message to display to the user.
function record_message($message) {
  global $messages;
  array_push($messages, $message);
}

// Write out any messages to the user.
function print_messages() {
  global $messages;
  foreach ($messages as $message) {
    echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
  }
}

// show database errors during development.
function handle_db_error($exception) {
  echo '<p><strong>' . htmlspecialchars('Exception : ' . $exception->getMessage()) . '</strong></p>';
}

// execute an SQL query and return the results.
function exec_sql_query($db, $sql, $params = array()) {
  try {
    $query = $db->prepare($sql);
    if ($query and $query->execute($params)) {
      return $query;
    }
  } catch (PDOException $exception) {
    handle_db_error($exception);
  }
  return NULL;
}

// YOU MAY COPY & PASTE THIS FUNCTION WITHOUT ATTRIBUTION.
// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename) {
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db_init_sql = file_get_contents($init_sql_filename);
    if ($db_init_sql) {
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return NULL;
}

//user-defined function that takes in an array of square images and
//formats them together in a grid
//$images must be an associate array
function gallery($images){
  $image_count = 1;
  $number_of_images = count($images);
  $number_of_columns = 3;
  $images_in_column = $number_of_images / $number_of_columns;

  foreach($images as $image){
    if ($image_count == 1) { echo "<div class='column'>"; }
    echo "<img src='". $image["image_path"] . "' >";
    if ($image_count > $images_in_column) { echo "</div>"; $image_count = 0; }

    $image_count += 1;
  }
}

// open connection to database
$db = open_or_init_sqlite_db("gallery.sqlite", "init/init.sql");


function check_login() {
  global $db;

  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];

    $sql = "SELECT * FROM users WHERE session = :session_id;";
    $params = array (
      ":session_id" => $session,
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      $account = $records[0];
      return $account["username"];
    }
  }
  return NULL;
}

function user_id() {
  global $db;
  global $current_user;

  $sql = "SELECT id FROM users WHERE username = '$current_user'";
  $params = array();
  $records = exec_sql_query($db, $sql, $params)->fetchAll();

  if ($records) {
    $account = $records[0];
    return $account['id'];
  }
}

// based off lecture 15
function log_in($username, $password) {
  global $db;

  if ($username && $password) {
    // check to see if username exists
    $sql = "SELECT * FROM users WHERE username = :username;";
    $params = array(
      ':username' => $username
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();

    if ($records) {
      // Username is UNIQUE, so there should only be 1 record.
      $account = $records[0];

      // check to make sure hashed password is right
      if (password_verify($password, $account['password'])) {

        // Generate session
        // Warning: Not a secure method for generating a session id
        $session = uniqid();
        $sql = "UPDATE users SET session = :session WHERE id = :user_id;";
        $params = array (
          ":user_id" => $account['id'],
          ":session" => $session
        );

        $result = exec_sql_query($db, $sql, $params);
        if ($result) {
          // Success, session stored in DB
          // Send this back to the client
          setcookie("session", $session, time()+3600);
          return $username;
        }
      } else {
        record_message("Invalid username or password.");
      }
    } else {
      record_message("Invalid username or password.");
    }
  } else {
    record_message("No username or password given.");
  }
  return NULL;
}

// based off lecture 15
function log_out() {
  global $current_user;
  global $db;

  if ($current_user) {
    $sql = "UPDATE users SET session = :session WHERE username = :username;";
    $params = array (
      ":username" => $current_user,
      ":session" => NULL
    );

    exec_sql_query($db, $sql, $params);

    if (!exec_sql_query($db, $sql, $params)) {
      record_message("log out failed.");
    }

    setcookie("session", "", time()-3600);
    $current_user = NULL;
    record_message("log out successful.");
  }
}

// check if logged in
$current_user = check_login();
?>
