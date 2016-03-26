<?php
// Last commit $Id$
// Version Location $HeadURL$

//http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL

function login($email, $password, $mysqli) {
  // Using prepared statements means that SQL injection is not possible.
  if ($stmt = $mysqli->prepare("SELECT id, password
        FROM members
       WHERE email = ?
        LIMIT 1")) {
    $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
    $stmt->execute();    // Execute the prepared query.
    $stmt->store_result();

    // get variables from result.
    $stmt->bind_result($user_id, $db_password);
    $stmt->fetch();
    echo '';

    if ($stmt->num_rows == 1) {
      // Get the user-agent string of the user.
      $user_browser = $_SERVER['HTTP_USER_AGENT'];

      // Check if the password in the database matches
      // the password the user submitted. We are using
      // the password_verify function to avoid timing attacks.
      if (password_verify($password, $db_password)) {
        // Password is correct!
        // XSS protection as we might print this value
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['login_string'] = hash('sha512',
          $db_password . $user_browser);
        // Login successful.
        return true;
      } else {
        // Password is not correct
        // We record this attempt in the database
        $mysqli->query("INSERT INTO login_attempts(user_id, time, success, user_agent)
                                    VALUES ('$user_id', NOW(), 0, '$user_browser')");
        return false;
      }
    } else {
      // No user exists.
      return false;
    }
  }
}



function login_check($mysqli) {
  // Check if all session variables are set
  if (isset($_SESSION['user_id'],
    $_SESSION['user_email'],
    $_SESSION['login_string'])) {

    $user_id = $_SESSION['user_id'];
    $login_string = $_SESSION['login_string'];

    // Get the user-agent string of the user.
    $user_browser = $_SERVER['HTTP_USER_AGENT'];

    if ($stmt = $mysqli->prepare("SELECT password
                                      FROM members
                                      WHERE id = ? LIMIT 1")) {
      // Bind "$user_id" to parameter.
      $stmt->bind_param('i', $user_id);
      $stmt->execute();   // Execute the prepared query.
      $stmt->store_result();

      if ($stmt->num_rows == 1) {
        // If the user exists get variables from result.
        $stmt->bind_result($password);
        $stmt->fetch();
        $login_check = hash('sha512', $password . $user_browser);

        if (hash_equals($login_check, $login_string) ){
          // Logged In!!!!
          return true;
        } else {
          // Not logged in
          return false;
        }
      } else {
        // Not logged in
        return false;
      }
    } else {
      // Not logged in
      return false;
    }
  } else {
    // Not logged in
    return false;
  }
}

if(!function_exists('hash_equals')) {
  function hash_equals($str1, $str2) {
    if(strlen($str1) != strlen($str2)) {
      return false;
    } else {
      $res = $str1 ^ $str2;
      $ret = 0;
      for($i = strlen($res) - 1; $i >= 0; $i--) $ret |= ord($res[$i]);
      return !$ret;
    }
  }
}

function destroySession() {
  // Unset all session values
  $_SESSION = array();

// get session parameters
  $params = session_get_cookie_params();

// Delete the actual cookie.
  setcookie(session_name(),
    '', time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]);

// Destroy session
  session_destroy();
}
