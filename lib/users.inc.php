<?php
class UsersGateway extends pdoext_TableGateway {
  protected function validate($data) {
    if (!preg_match('~[^@]+@[^@]+\.[^@]{2,}~', $data->email)) {
      $data->_errors[] = "You must enter a valid email address";
    }
    if ($data->password_repeat != null && !$data->checkPassword($data->password_repeat)) {
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
    $this->_data['password_salt'] = md5(rand());
    $this->_data['encrypted_password'] = sha1($this->password_salt . $value);
  }
  function checkPassword($value) {
    return $this->encrypted_password == sha1($this->password_salt . $value);
  }
  function generateResetPasswordToken() {
    $this->reset_password_token = md5(rand());
  }
}
