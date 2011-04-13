<?php
function authentic_users() {
  return db()->table($GLOBALS['AUTHENTIC']['table']);
}

function authorise_user_by_credentials() {
  $args = func_get_args();
  $password = array_pop($args);
  $user = authentic_users()->fetch(array_combine($GLOBALS['AUTHENTIC']['login_columns'], $args));
  if ($user && $user->checkPassword($password)) {
    session()->set('current_user_id', $user->id);
    $GLOBALS['current_user'] = $user;
    if (in_array('last_logged_in_at', authentic_users()->getColumnNames())) {
      $user->lastLoggedInAt = date("Y-m-d H:i:s");
      authentic_users()->saveOrFail($user);
    }
    return true;
  }
  $GLOBALS['current_user'] = null;
  session()->set('current_user_id', null);
}

function current_user() {
  if (!array_key_exists('current_user', $GLOBALS)) {
    $id = session()->get('current_user_id');
    $GLOBALS['current_user'] = $id ? authentic_users()->find($id) : null;
  }
  return $GLOBALS['current_user'];
}

function require_valid_user() {
  if (!current_user()) {
    throw new http_Unauthorized();
  }
}