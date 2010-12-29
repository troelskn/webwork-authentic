<?php
class UsersGateway extends pdoext_TableGateway {
  function __construct($db) {
    parent::__construct('users', $db);
  }
  function load($row) {
    if (is_array($row)) {
      return new User($row, $this->tablename);
    }
  }
  protected function validate($data) {
    if (!preg_match('~[^@]+@[^@]+\.[^@]{2,}~', $data->email)) {
      $data->_errors[] = "You must enter a valid email address";
    }
    if (!$data->checkPassword($data->password_repeat)) {
      $data->_errors[] = "You must enter the same value for password and password repeat";
    }
  }
  protected function validateInsert($data) {
    if ($this->fetch(array('email' => $data->email))) {
      $data->_errors[] = "A user already exists with that email address";
    }
  }
}

class User extends pdoext_DatabaseRecord {
  function setPassword($value) {
    $this->_row['password_salt'] = md5(rand());
    $this->_row['encrypted_password'] = sha1($this->password_salt . $value);
  }
  function checkPassword($value) {
    return $this->encrypted_password == sha1($this->password_salt . $value);
  }
}

function authorise_user_by_credentials($email, $password) {
  $user = db()->users->fetch(array('email' => $email));
  if ($user && $user->checkPassword($password)) {
    session()->set('current_user_id', $user->id);
    $GLOBALS['current_user'] = $user;
    return true;
  }
}

function current_user() {
  if (!array_key_exists('current_user', $GLOBALS)) {
    $id = session()->get('current_user_id');
    $GLOBALS['current_user'] = $id ? db()->users->find($id) : null;
  }
  return $GLOBALS['current_user'];
}

function require_valid_user() {
  if (!current_user()) {
    throw new http_Unauthorized();
  }
}